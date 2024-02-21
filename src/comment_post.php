<?php

include 'koneksi.php';

include 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$validatedData = validate($_POST, [
		"fotoid" => "required|number",
		"comment" => "required|string|min:1|max:64",
	]);

	$query = $dbh->prepare("INSERT INTO komentarfoto VALUES (NULL, :fotoid, :userid, :comment, now())");
	$query->bindParam(':fotoid', $validatedData["fotoid"]);
	$query->bindParam(':userid', sanitizeInput($_SESSION["UserID"]));
	$query->bindParam(':comment', $validatedData["comment"]);
	$query->execute();
}
header("location: {$_SERVER['HTTP_REFERER']}");
