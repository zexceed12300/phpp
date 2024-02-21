<?php

include 'koneksi.php';

include 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$validatedData = validate($_POST, [
		'nama' => 'required|string|min:4|max:64',
		'deskripsi' => 'required|string|min:4|max:128'
	]);

	$query = $dbh->prepare("INSERT INTO album VALUES (NULL, :nama, :deskripsi, now(), :userid)");
	$query->bindParam(':nama', $validatedData["nama"]);
	$query->bindParam(':deskripsi', $validatedData["deskripsi"]);
	$query->bindParam(':userid', $_SESSION['UserID']);
	$query->execute();
}
redirect("profile_album.php");
