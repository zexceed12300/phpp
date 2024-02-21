<?php

include 'koneksi.php';

if (empty($_GET["username"])) {
	$_GET["username"] = $_SESSION["Username"];
}
$query = $dbh->prepare("SELECT * FROM user WHERE Username = :username");
$query->bindParam(":username", $_GET["username"]);
$query->execute();
$user = $query->fetch();

$query = $dbh->prepare("SELECT foto.FotoID, foto.LokasiFile, foto.JudulFoto, user.Username, album.NamaAlbum, album.AlbumID, COUNT(likefoto.FotoID) AS likee, COUNT(komentarfoto.FotoID) AS komentar FROM foto INNER JOIN user ON foto.UserID = user.UserID INNER JOIN album ON foto.AlbumID = album.AlbumID LEFT JOIN likefoto ON foto.FotoID = likefoto.FotoID LEFT JOIN komentarfoto ON foto.FotoID = komentarfoto.FotoID WHERE likefoto.UserID = :userid GROUP BY foto.FotoID");
$query->bindParam(":userid", $user["UserID"]);
$query->execute();
$photos = $query->fetchAll(PDO::FETCH_ASSOC);

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
			<div class="grid grid-cols-2 divide-x w-fit border rounded-xl text-violet-600 font-bold overflow-hidden mb-6">
				<a href="profile_album.php?username=<?= $_GET["username"] ?>" class="px-3 py-2">Album</a>
				<a href="favorite.php?username=<?= $_GET["username"] ?>" class="px-3 py-2 bg-gray-100">My Favorite</a>
			</div>
			<div class="columns-5 gap-16">
				<?php
				foreach ($photos as $photo) {
				?>
					<div class="w-fit overflow-hidden">
						<div class="relative group w-48 max-h-96 rounded-xl overflow-hidden">
							<div class="opacity-0 group-hover:opacity-100">
								<div class="absolute bg-black w-full h-full opacity-40"></div>
								<div class="absolute flex flex-col gap-3 justify-center items-center w-full h-full text-white">
									<a href="photo_detail.php?id=<?= $photo["FotoID"] ?>" class="bg-violet-500 rounded-lg px-2">Show</a>
									<?php if ($_GET["username"] == $_SESSION["Username"]) { ?>
										<div class="flex gap-2">
											<a href="photo_edit.php?id=<?= $photo["FotoID"] ?>" class="bg-yellow-500 rounded-lg px-2">Edit</a>
											<form action="photo_delete.php" method="post">
												<input type="hidden" name="id" value="<?= $photo["FotoID"] ?>">
												<button type="submit" class="bg-red-600 rounded-lg px-2">Hapus</button>
											</form>
										</div>
									<?php } ?>
								</div>
							</div>
							<img class="w-72 object-cover max-h-96" src="<?= $photo["LokasiFile"] ?>" alt="">
						</div>
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