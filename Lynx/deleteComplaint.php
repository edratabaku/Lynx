<?php
require_once "configuration.php";
session_start();
$currentUserId = $_SESSION["Id"];

    $id =  trim($_REQUEST["id"]);
    // Set parameters
    $param_id = $id;

    // Prepare a select statement
    $result = mysqli_query($mysqli,
               "UPDATE Complaints SET IsActive=0
                    where Id = '$param_id'");
    if($result){
        /* Fetch result row as an associative array. Since the result set
        contains only one row, we don't need to use while loop */
        header("location: yourComplaints.php?id=".$currentUserId);
    }
mysqli_close($mysqli);
?>