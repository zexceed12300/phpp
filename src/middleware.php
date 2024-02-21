<?php

if (!isset($_SESSION["UserID"])) {
	redirect("login.php");
}
