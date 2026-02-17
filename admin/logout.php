<?php
session_start();
$_SESSION = []; // Kosongkan semua variabel session
session_unset();
session_destroy();
header("Location: login.php");
exit;
