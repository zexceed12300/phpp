<?php

include 'koneksi.php';

include 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$validatedData = validate($_POST, [
		"judul" => "required|min:2|max:32",
		"deskripsi" => "required|min:2|max:64",
		"albumid" => "required|number"
	]);

	if (isset($_FILES['image']) && $_FILES["image"]["error"] == 0) {
		$targetDir = "./uploads/";
		$imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
		$targetFile = $targetDir . "IMG-" . date("Y-m-d_H-i-s") . "." . $imageFileType;
		$allowedTypes = array("jpg", "png", "jpeg", "gif");

		if ($_FILES["image"]["size"] <= 2000000) {
			if (in_array($imageFileType, $allowedTypes)) {
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
					$query = $dbh->prepare("INSERT INTO foto VALUES (NULL, :judul, :deskripsi, now(), '$targetFile', :albumid, :userid)");
					$query->bindParam(':judul', $validatedData["judul"]);
					$query->bindParam(':deskripsi', $validatedData["deskripsi"]);
					$query->bindParam(':albumid', $validatedData["albumid"]);
					$query->bindParam(':userid', sanitizeInput($_SESSION["UserID"]));
					$query->execute();
					redirect("index.php");
				}
			} else {
				$_SESSION['errors'] = "Allowed types is JPG,JPEG,PNG,GIF";
				redirect("photo_create.php");
			}
		} else {
			$_SESSION['errors'] = "Image terlalu besar >2MB";
			redirect("photo_create.php");
		}
	}
}
