<?php
include("layout.php");
session_start();
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "configuration.php";
    $param_id = trim($_GET["id"]);
    $result = mysqli_query($mysqli,"SELECT * FROM Users WHERE Id='$param_id'");
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $Id = $row["Id"];
        $username = $row["Username"];
        $email = $row["Email"];
        $phoneNumber = $row["PhoneNumber"];
        $firstName = $row["FirstName"];
        $lastName = $row["LastName"];
        $isActive= $row["IsActive"];
        $isBanned = $row["IsBanned"];
        $dateOfBirth = $row["DateOfBirth"];
        $gender = $row["Gender"];
    }
    else{exit();}

    if ($_SESSION["Role"]=="Driver"){
        // Prepare a select statement
        $sql = "SELECT Id, UserId, LicenseId, LicenseDate, LicenseExpireDate, OperatingArea, Car, IsApproved, IsBusy from Drivers WHERE UserId=?";

        if($stmt = mysqli_prepare($mysqli, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = trim($_GET["id"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $driverId = $row["Id"];
                    $userId = $row["UserId"];
                    $licenseId = $row["LicenseId"];
                    $licenseDate = $row["LicenseDate"];
                    $licenseExpireDate = $row["LicenseExpireDate"];
                    $operatingArea = $row["OperatingArea"];
                    $car = $row["Car"];
                    $isApproved = $row["IsApproved"];
                    $isBusy = $row["IsBusy"];

                } else{
                    // URL doesn't contain valid id parameter. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

    }
    mysqli_stmt_close($stmt);
    // Close connection
    mysqli_close($mysqli);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
