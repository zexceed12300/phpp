<?php

include 'koneksi.php';

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
	<div class="flex flex-wrap justify-center gap-6 my-20">
		<span class="text-4xl font-bold">Discover album</span>
	</div>
	<div class="grid grid-cols-5 gap-6 mx-28">
		<?php

		$query = $dbh->prepare("SELECT user.Username, album.AlbumID, album.NamaAlbum, COUNT(foto.FotoID) AS JumlahFoto, GROUP_CONCAT(foto.LokasiFile) AS LokasiFile FROM album LEFT JOIN foto ON foto.AlbumID = album.AlbumID INNER JOIN user ON user.UserID = album.UserID GROUP BY album.AlbumID");
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
</body>

</html>