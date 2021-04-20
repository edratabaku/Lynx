<?php
include("layout.php");
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "configuration.php";

    // Prepare a select statement
    $sql = "SELECT * FROM Users WHERE Id = ?";

    if($stmt = mysqli_prepare($mysqli, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        //$param_id = trim($_GET["id"]);
         $param_id = trim($_GET["id"]);
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $Id = $row["Id"];
                $username = $row["Username"];
                $email = $row["Email"];
                $personType = $row["PhoneNumber"];
                $firstName = $row["FirstName"];
                $lastName = $row["LastName"];
                $isActive= $row["IsActive"];
                $isBanned = $row["IsBanned"];
                $dateOfBirth = $row["DateOfBirth"];
                $gender = $row["Gender"];

            } else{
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    echo "<p>".$row["Id"]."</p>";
                }
                // URL doesn't contain valid id parameter. Redirect to error page
               // header("location: error.php");
                //exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center mb-5">
                    <h1 class="mt-5 mb-3">
                        Detajet: <?php echo $row["Username"]?>
                    </h1>
                </div>
            </div>
            <div class="form-group">
                <label>Username:</label>
                <?php echo $row["Username"]; ?>
            </div>
            <div class="form-group">
                <label>Emri:</label>
                <?php echo $row["FirstName"]; ?>
            </div>
            <div class="form-group">
                <label>Mbiemri:</label>
                <?php echo $row["LastName"]; ?>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <?php echo $row["Email"]; ?>
            </div>
            <?php echo '<a href="editProfile.php?id'.$Id.'" class="btn btn-info" style="font-size:16px;">Modifiko</a>'; ?>
            <a href="home.php" class="btn btn-default" style="font-size:16px;">Kthehu ne faqen kryesore</a>

        </div>
    </div>
</body>
</html>