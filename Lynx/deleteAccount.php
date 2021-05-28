<?php
require_once "configuration.php";
$id = $_REQUEST["id"];
$update = mysqli_query($mysqli,"UPDATE Users SET IsActive=0 WHERE Id='$id'");
header("logout.php");
?>

