<?php

include 'koneksi.php';

include 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (
		isset($_POST["fotoid"])
	) {
		$query = $dbh->prepare("INSERT INTO likefoto VALUES (NULL, :fotoid, :userid, now())");
		$query->bindParam(':fotoid', sanitizeInput($_POST['fotoid']));
		$query->bindParam(':userid', sanitizeInput($_SESSION['UserID']));
		$query->execute();
		header("location: {$_SERVER['HTTP_REFERER']}");
	}
}
header("location: {$_SERVER['HTTP_REFERER']}");
