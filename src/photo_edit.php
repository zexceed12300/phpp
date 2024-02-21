<?php

include 'koneksi.php';

include 'middleware.php';

$query = $dbh->prepare("SELECT * FROM album INNER JOIN user ON album.UserID = user.UserID WHERE user.UserID = :userid");
$query->bindParam(":userid", $_SESSION["UserID"]);
$query->execute();
$albums = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $dbh->prepare("SELECT * FROM foto WHERE FotoID = :fotoid");
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

<body>
	<?php
	include 'navbar.php';
	?>
	<form method="post" action="photo_put.php" enctype="multipart/form-data">
		<input type="hidden" name="fotoid" value="<?= $photo["FotoID"] ?>">
		<div class="flex flex-col items-center my-36 gap-4 text-lg">
			<?php
			if (!empty($_SESSION["errors"])) {
			?>
				<div class="border-2 text-red-600 font-bold border-red-600 px-4 py-3 rounded-2xl mb-4">
					<?= $_SESSION["errors"] ?>
				</div>
			<?php } ?>
			<label for="input-file" class="w-1/3 flex flex-col cursor-pointer bg-zinc-100 hover:bg-zinc-50 justify-center items-center rounded-2xl border-dashed border-8 p-4">
				<div class="flex flex-col justify-center items-center h-80 hidden" id="uploadLogo" class="">
					<i class="fa-solid fa-upload text-3xl mb-2"></i>
					<span class="text-xl font-bold">Select Image</span>
					<input id="input-file" name="image" class="hidden" onchange="loadPreview(event)" id="input-file" type="file">
				</div>
				<img id="preview" class="object-contain h-[500px]" alt="preview" src="<?= $photo["LokasiFile"] ?>">
			</label>
			<div class="flex flex-col w-1/3">
				<span class="ml-2">Judul</span>
				<input name="judul" class="outline-none border rounded-lg px-3 py-2" type="text" placeholder="Tulis judul disini." value="<?= $photo["JudulFoto"] ?>">
			</div>
			<div class="flex flex-col w-1/3">
				<span class="ml-2">Pilih album</span>
				<select name="albumid" class="outline-none border rounded-lg px-3 py-2" type="text" placeholder="Tulis judul disini.">
					<?php
					foreach ($albums as $album) { ?>
						<option <?php if ($album["AlbumID"] == $photo["AlbumID"]) {
									echo "selected";
								} ?> value="<?= $album["AlbumID"] ?>"><?= $album["NamaAlbum"] ?></option>
					<?php
					}
					?>
				</select>
			</div>
			<div class="flex flex-col w-1/3">
				<span class="ml-2">Deskripsi</span>
				<textarea name="deskripsi" class="outline-none border rounded-lg px-3 py-2" type="text" placeholder="Tulis deskripsi disini."><?= $photo["DeskripsiFoto"] ?></textarea>
			</div>
			<button class="w-1/3 bg-black text-white rounded-xl p-3">Upload</button>
		</div>
	</form>
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

<?php

include 'end.php';

?>

</html>