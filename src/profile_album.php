<?php

include 'koneksi.php';

if (!isset($_GET["category"])) {
	$_GET["category"] = "all";
}

if (!isset($_GET["username"]) && isset($_SESSION["Username"])) {
	$_GET["username"] = $_SESSION["Username"];
}

?>

<!DOCTYPE html>
<html data-theme="light" lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>phpDaisyTemplate</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="assets/fontawesome/css/all.css" />
</head>

<body>
	<?php
	include 'navbar.php';
	?>
	<div class="flex justify-center gap-3 my-36">
		<div class="w-3/5">
			<?php
			include 'user_profile.php';
			?>
			<div class="flex justify-between">
				<div class="grid grid-cols-2 divide-x w-fit border rounded-xl text-violet-600 font-bold overflow-hidden mb-6">
					<a href="profile_album.php?username=<?= $_GET["username"] ?>" class="px-3 py-2 bg-gray-100">Album</a>
					<a href="favorite.php?username=<?= $_GET["username"] ?>" class="px-3 py-2 ">My Favorite</a>
				</div>
				<div>
					<a href="album_create.php" class="bg-violet-600 text-white p-3 rounded-lg">Create album</a>
				</div>
			</div>
			<div class="grid grid-cols-5 gap-6">
				<?php

				$query = $dbh->prepare("SELECT user.Username, album.AlbumID, album.NamaAlbum, COUNT(foto.FotoID) AS JumlahFoto, GROUP_CONCAT(foto.LokasiFile) AS LokasiFile FROM album LEFT JOIN foto ON foto.AlbumID = album.AlbumID INNER JOIN user ON user.UserID = album.UserID WHERE user.Username = :username GROUP BY album.AlbumID");
				$query->bindParam(":username", $_GET["username"]);
				$query->execute();
				$albums = $query->fetchAll(PDO::FETCH_ASSOC);

				foreach ($albums as $album) {
				?>
					<div class="flex flex-col items-start">
						<div class="relative w-full border rounded-lg">
							<?php
							$photos = explode(',', $album["LokasiFile"]);
							?>
							<div class="grid grid-cols-2 w-full h-full rounded-lg overflow-hidden">
								<?php
								$photos = explode(',', $album["LokasiFile"]);
								for ($i = 0; $i < 4; $i++) {
								?>
									<div class="h-28 w-full overflow-hidden">
										<img class="object-cover h-28" src="<?= $photos[$i] ?>" alt="">
									</div>
								<?php
								}
								?>
							</div>
							<div class="w-full h-full top-0 left-0 absolute opacity-0 hover:opacity-100 rounded-lg overflow-hidden">
								<div class="bg-black w-full h-full opacity-40">
								</div>
								<div class="absolute flex flex-col gap-3 justify-center items-center top-0 left-0 w-full h-full text-white">
									<a href="profile_album_detail.php?id=<?= $album["AlbumID"] ?>" class="bg-violet-600 px-2 rounded-lg">Show</a>
									<?php if ($_GET["username"] == $_SESSION["Username"]) { ?>
										<div class="flex gap-2">
											<a href="album_edit.php?albumid=<?= $album["AlbumID"] ?>" class="bg-yellow-500 rounded-lg px-2">Edit</a>
											<form action="album_delete.php" method="post">
												<input type="hidden" name="albumid" value="<?= $album["AlbumID"] ?>">
												<button type="submit" class="bg-red-600 rounded-lg px-2">
													Hapus
												</button>
											</form>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="text-lg font-bold"><?= $album["NamaAlbum"] ?></div>
						<div><?= $album["JumlahFoto"] ?> Photos</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	</div>
</body>

<script>
	// var previewFile = function(e) {
	// 	let preview = document.getElementById('preview');
	// 	preview.src = URL.createObjectURL(e.target.files[0]);
	// 	preview.onload = function() {
	// 		URL.revokeObjectURL(preview.src);
	// 	};
	// }

	var loadPreview = function(event) {
		let output = document.getElementById('preview');
		document.getElementById("uploadLogo").style.display = "none";
		document.getElementById("preview").style.display = "block";
		output.src = URL.createObjectURL(event.target.files[0]);
		output.onload = function() {
			URL.revokeObjectURL(output.src) // free memory
		}
	};
</script>

</html>