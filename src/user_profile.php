<?php

if (!isset($_GET["username"]) && isset($_SESSION["Username"])) {
	$_GET["username"] = $_SESSION["Username"];
}

$query = $dbh->prepare("SELECT * FROM user WHERE Username = :username");
$query->bindParam(":username", $_GET["username"]);
$query->execute();
$user = $query->fetch();

?>
<div class="flex justify-between items-center mb-8">
	<div class="flex flex-col">
		<div class="flex justify-center items-center border rounded-full h-28 w-28 mb-3 overflow-hidden">
			<i class="fa fa-user text-8xl mb-3 mt-8"></i>
		</div>
		<span class="text-3xl font-bold"><?= $user["NamaLengkap"] ?></span>
		<span class="font-bold">@<?= $user["Username"] ?></span>
		<span class="font-bold"><?= $user["Email"] ?></span>
	</div>
	<a href="logout.php" class="flex flex-col items-center">
		<div class="flex flex-col text-xl bg-zinc-200 p-2 rounded-xl hover:bg-zinc-300">
			<i class="fa-solid fa-right-from-bracket"></i>
		</div>
		<span class="font-bold text-lg">Logout</span>
	</a>
</div>