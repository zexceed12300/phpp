<?php

include 'koneksi.php';

include 'middleware.php';

$query = $dbh->prepare("SELECT * FROM album WHERE AlbumID = :albumid");
$query->bindParam(":albumid", $_GET["albumid"]);
$query->execute();
$album = $query->fetch();

?>
<!DOCTYPE html>
<html data-theme="light" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>phpDaisyTemplate</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="assets/fontawesome/css/all.css" />
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <form method="post" action="album_put.php">
        <input type="hidden" name="albumid" value="<?= $_GET["albumid"] ?>">
        <div class="flex flex-col items-center my-20 gap-4 text-lg">
            <h1 class="w-1/3 font-bold text-3xl text-center text-violet-600 ">
                Edit Album
            </h1>
            <?php
            if (!empty($_SESSION["errors"])) {
            ?>
                <div class="border-2 text-red-600 font-bold border-red-600 px-4 py-3 rounded-2xl mb-4">
                    <?= $_SESSION["errors"] ?>
                </div>
            <?php } ?>
            <div class="flex flex-col w-1/3">
                <span class="ml-2">Nama Album</span>
                <input name="nama" value="<?= $album["NamaAlbum"] ?>" class="outline-none border rounded-lg px-3 py-2" type="text" placeholder="Tulis nama disini." />
            </div>
            <div class="flex flex-col w-1/3 mb-8">
                <span class="ml-2">Deskripsi</span>
                <textarea name="deskripsi" class="outline-none border rounded-lg px-3 py-2" type="text" placeholder="Tulis deskripsi disini."><?= $album["Deskripsi"] ?></textarea>
            </div>
            <button class="w-1/3 bg-violet-600 text-white rounded-xl p-3">
                Submit
            </button>
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
        let output = document.getElementById("preview");
        document.getElementById("uploadLogo").style.display = "none";
        document.getElementById("preview").style.display = "block";
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src); // free memory
        };
    };
</script>

</html>

<?php

include 'end.php';

?>