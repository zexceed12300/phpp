<?php

include 'koneksi.php';

session_destroy();

redirect("login.php");
