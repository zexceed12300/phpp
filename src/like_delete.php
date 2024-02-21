<?php

include 'koneksi.php';

include 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$validatedData = validate($_POST, [
		"likeid" => "required|number|exists:likefoto,LikeID"
	]);

	$query = $dbh->prepare("DELETE FROM likefoto WHERE LikeID = :likeid");
	$query->bindParam(':likeid', $_POST['likeid']);
	$query->execute();
}
redirect("{$_SERVER['HTTP_REFERER']}");
