<?php

include 'koneksi.php';

include 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$validatedData = validate($_POST, [
		"id" => "required|number"
	]);

	$query = $dbh->prepare("SELECT * FROM foto WHERE FotoID = :id");
	$query->bindParam(":id", $validatedData["id"]);
	$query->execute();
	$photo = $query->fetch();
	if (file_exists($photo["LokasiFile"])) {
		unlink($photo["LokasiFile"]);
	}

	$query = $dbh->prepare("DELETE FROM foto WHERE FotoID = :id");
	$query->bindParam(":id", $validatedData["id"]);
	$query->execute();
}
header("location: {$_SERVER['HTTP_REFERER']}");
