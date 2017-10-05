<?php
session_start();
session_destroy();
setcookie("nomutili", "", time()-3600, "/");
header("Location: ../index.php");
?>