<?php

include 'koneksi.php';

$query = $dbh->prepare("SELECT foto.LokasiFile, foto.FotoID, user.Username, foto.JudulFoto, foto.DeskripsiFoto, album.NamaAlbum, foto.TanggalUnggah FROM foto INNER JOIN user ON user.UserID = foto.UserID INNER JOIN album ON album.AlbumID = foto.AlbumID WHERE foto.FotoID = :fotoid");
$query->bindParam(":fotoid", $_GET["id"]);
$query->execute();
$photo = $query->fetch();

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

<body class="relative">
	<?php
	include 'navbar.php';
	?>
	<div class="flex flex-col lg:flex-row gap-8 lg:my-20 lg:mx-96">
		<div class="sticky top-28 w-1/2 max-h-[700px] rounded-xl overflow-hidden">
			<img class="object-contain w-full h-full" src="<?= $photo["LokasiFile"] ?>" alt="">
		</div>
		<div class="w-1/2">
			<div class="">
				<div>
					<div class="flex justify-between items-center mb-6">
						<div class="text-lg font-bold">@<?= $photo["Username"] ?></div>
						<div>
							<span class="flex flex-col items-center">
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
								<span class="font-bold">Like</span>
							</span>
						</div>
					</div>
					<span><?= time_elapsed_string($photo["TanggalUnggah"]) ?></span>
					<div class="text-3xl font-bold"><?= $photo["JudulFoto"] ?></div>
					<div class="mb-6">
						<span>Album:</span>
						<span class="font-bold text-violet-600"><?= $photo["NamaAlbum"] ?></span>
					</div>
					<p class="text-lg mb-12">
						<?= $photo["DeskripsiFoto"] ?>
					</p>
				</div>
				<div class="text-xl font-bold mb-2">Comments</div>
				<div class="flex flex-col gap-2">
					<?php

					$query = $dbh->prepare("SELECT user.Username, komentarfoto.TanggalKomentar, komentarfoto.IsiKomentar, foto.FotoID FROM komentarfoto INNER JOIN user ON user.UserID = komentarfoto.UserID INNER JOIN foto ON foto.FotoID = komentarfoto.FotoID WHERE foto.FotoID = :fotoid");
					$query->bindParam(":fotoid", $photo["FotoID"]);
					$query->execute();
					$comments = $query->fetchAll(PDO::FETCH_ASSOC);

					foreach ($comments as $comment) {
					?>
						<div>
							<div class="flex items-center gap-3 mb-1">
								<span class="font-bold text-lg">@<?= $comment["Username"] ?></span>
								<span><?= time_elapsed_string($comment["TanggalKomentar"]) ?></span>
							</div>
							<p class="mb-2"><?= $comment["IsiKomentar"] ?></p>
						</div>
					<?php
					}
					?>
				</div>
				<form action="comment_post.php" method="post" class="sticky bottom-0 flex items-center gap-3 py-8">
					<input type="hidden" name="fotoid" value="<?= $photo["FotoID"] ?>">
					<input name="comment" class="outline-none border rounded-2xl bg-zinc-200 p-3 w-full" type="text" placeholder="Tulis comment disini">
					<button type="submit" class="bg-violet-600 rounded-full p-3 text-white flex justify-center items-center">
						<i class="fa-solid fa-paper-plane text-xl"></i>
					</button>
				</form>
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