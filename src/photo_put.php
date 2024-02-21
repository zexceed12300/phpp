<?php

include 'koneksi.php';

include 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$validatedData = validate($_POST, [
		"fotoid" => "required|number",
		"judul" => "required|min:2|max:32",
		"deskripsi" => "required|min:2|max:64",
		"albumid" => "required|number",
	]);

	$query = $dbh->prepare("SELECT * FROM foto WHERE FotoID = :fotoid AND UserID = :userid");
	$query->bindParam(":fotoid", $validatedData["fotoid"]);
	$query->bindParam(":userid", sanitizeInput($_SESSION["UserID"]));
	$query->execute();
	$foto = $query->fetch();
	if (empty($foto)) {
		redirect("profile.php");
		exit();
	}

	if (isset($_FILES['image']) && $_FILES["image"]["error"] == 0) {
		$targetDir = "./uploads/";
		$imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
		$targetFile = $targetDir . "IMG-" . date("Y-m-d_H-i-s") . "." . $imageFileType;
		$allowedTypes = array("jpg", "png", "jpeg", "gif");

		$query = $dbh->prepare("SELECT * FROM foto WHERE FotoID = :fotoid");
		$query->bindParam(":fotoid", $_POST["id"]);
		$query->execute();
		$old = $query->fetch();

		if ($_FILES["image"]["size"] <= 2000000) {
			if (in_array($imageFileType, $allowedTypes)) {
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
					if (file_exists($old["LokasiFile"])) {
						unlink($old["LokasiFile"]);
					}

					$query = $dbh->prepare("UPDATE foto SET JudulFoto = :judul, DeskripsiFoto = :deskripsi, TanggalUnggah = now(), LokasiFile = '$targetFile', AlbumID = :albumid WHERE FotoID = :fotoid");
					$query->bindParam(':judul', $validatedData['judul']);
					$query->bindParam(':deskripsi', $validatedData['deskripsi']);
					$query->bindParam(':albumid', $validatedData['albumid']);
					$query->bindParam(':fotoid', $validatedData['fotoid']);
					$query->execute();
					redirect("profile_album.php?id={$old['AlbumID']}");
				}
			} else {
				$_SESSION['errors'] = "Allowed types is JPG,JPEG,PNG,GIF";
				redirect("photo_create.php");
			}
		} else {
			$_SESSION['errors'] = "Image terlalu besar >2MB";
			redirect("photo_create.php");
		}
	} else {
		$query = $dbh->prepare("UPDATE foto SET JudulFoto = :judul, DeskripsiFoto = :deskripsi, TanggalUnggah = now(), AlbumID = :albumid WHERE FotoID = :fotoid");
		$query->bindParam(':judul', $validatedData['judul']);
		$query->bindParam(':deskripsi', $validatedData['deskripsi']);
		$query->bindParam(':albumid', $validatedData['albumid']);
		$query->bindParam(':fotoid', $validatedData['fotoid']);
		$query->execute();
		redirect("profile_album.php?id={$old['AlbumID']}");
	}
}
