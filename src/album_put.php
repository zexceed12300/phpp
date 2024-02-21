<?php

include 'koneksi.php';

include 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$validatedData = validate($_POST, [
		'albumid' => 'required|number',
		'nama' => 'required|min:4|max:64',
		'deskripsi' => 'required|min:4|max:128'
	]);

	$query = $dbh->prepare("SELECT * FROM album WHERE AlbumID = :albumid AND UserID = :userid");
	$query->bindParam(":albumid", $validatedData["albumid"]);
	$query->bindParam(":userid", sanitizeInput($_SESSION["UserID"]));
	$query->execute();
	$album = $query->fetch();
	if (empty($album)) {
		redirect("profile_album.php");
		exit();
	}

	$query->$query = $dbh->prepare("UPDATE album SET NamaAlbum = :nama, Deskripsi = :deskripsi WHERE AlbumID = :albumid");
	$query->bindParam(':nama', $validatedData['nama']);
	$query->bindParam(':deskripsi', $validatedData['deskripsi']);
	$query->bindParam(':albumid', $validatedData['albumid']);
	$query->execute();
}
redirect("profile_album.php");
