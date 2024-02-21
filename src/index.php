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
		<span class="text-4xl font-bold">Discover photo</span>
	</div>
	<div class="mx-28 my-16 columns-5 gap-16">
		<?php
		$query = $dbh->prepare("SELECT foto.FotoID, foto.LokasiFile, foto.JudulFoto, foto.DeskripsiFoto, user.Username, album.NamaAlbum, album.AlbumID, COUNT(DISTINCT likefoto.LikeID) AS likee, COUNT(DISTINCT komentarfoto.KomentarID) AS komentar FROM foto INNER JOIN user ON foto.UserID = user.UserID INNER JOIN album ON foto.AlbumID = album.AlbumID LEFT JOIN likefoto ON foto.FotoID = likefoto.FotoID LEFT JOIN komentarfoto ON foto.FotoID = komentarfoto.FotoID GROUP BY foto.FotoID");
		$query->execute();
		$photos = $query->fetchAll(PDO::FETCH_ASSOC);

		foreach ($photos as $photo) {
		?>
			<div href="photo_detail.php?id=<?= $photo["FotoID"] ?>" class="relative w-fit overflow-hidden">
				<div class="flex justify-between items-center bg-white px-4 py-2 mb-1 font-bold">
					<a href="profile_album.php?username=<?= $photo["Username"] ?>">@<?= $photo["Username"] ?></a>
					<a href="profile_album_detail.php?id=<?= $photo["AlbumID"] ?>" class="text-violet-600"><?= $photo["NamaAlbum"] ?></a>
				</div>
				<a href="photo_detail.php?id=<?= $photo["FotoID"] ?>">
					<div class="w-72 max-h-96 rounded-xl overflow-hidden">
						<img class="w-72 object-cover max-h-96 transition duration-300 ease-in-out hover:scale-110" src="<?= $photo["LokasiFile"] ?>" alt="">
					</div>
				</a>
				<div class="flex flex-col gap-2 justify-between items-center text-lg py-2 px-3">
					<span class="text-sm">
						<?= $photo["JudulFoto"] ?>
					</span>
					<div class="flex gap-4">
						<div class="flex items-center gap-2">
							<?php
							$isLiked = $dbh->prepare("SELECT * FROM likefoto WHERE FotoID = :fotoid AND UserID = :userid");
							$isLiked->bindParam(":fotoid", $photo["FotoID"]);
							$isLiked->bindParam(":userid", $_SESSION["UserID"]);
							$isLiked->execute();
							$result = $isLiked->fetch(PDO::FETCH_ASSOC);
							?>
							<form action="<?php echo ($result != null) ? 'like_delete.php' : 'like_post.php'; ?>" method="post" class="flex items-center">
								<input type="hidden" name="fotoid" value="<?= $photo["FotoID"] ?>">
								<?php
								if ($result != null) {
								?>
									<input type="hidden" name="likeid" value="<?= $result["LikeID"] ?>">
								<?php
								}
								?>
								<button class="flex items-center">
									<?php
									if ($result != null) {
									?>
										<i class="fa-solid fa-heart text-red-500 text-2xl"></i>
									<?php
									} else {
									?>
										<i class="fa-regular fa-heart text-2xl"></i>
									<?php
									}
									?>
								</button>
							</form>
							<span><?= $photo["likee"] ?></span>
						</div>
						<div class="flex items-center gap-2">
							<i class="fa-solid fa-comment text-2xl"></i>
							<span><?= $photo["komentar"] ?></span>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		?>
	</div>
</body>

</html>