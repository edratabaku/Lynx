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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <style>
        body {
            background: #333333;
            margin-top: 20px;
            font-family: "system-ui" !important;
            font-weight: 600;
        }

        .card-box {
            padding: 20px;
            border-radius: 21px;
            margin-bottom: 30px;
            background-image: linear-gradient( 45deg, #383838, #030303);
            color: white;
            font-weight: 600;
            box-shadow: 3px 0px 11px 2px #272727;
        }

            .card-box:hover {
                box-shadow: 3px 0px 11px 2px #8a7129;
            }

        .social-links li a {
            border-radius: 50%;
            color: rgba(121, 121, 121, .8);
            display: inline-block;
            height: 30px;
            line-height: 27px;
            border: 2px solid rgba(121, 121, 121, .5);
            text-align: center;
            width: 30px;
        }

            .social-links li a:hover {
                color: #797979;
                border: 2px solid #797979;
            }

        .thumb-lg {
            height: 88px;
            width: 88px;
        }

        .img-thumbnail {
            padding: .25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: .25rem;
            max-width: 100%;
            height: auto;
        }

        .text-pink {
            color: #ff679b !important;
        }

        .btn-rounded {
            border-radius: 2em;
        }

        .text-muted {
            color: #c0c8cc !important;
        }

        h4 {
            line-height: 22px;
            font-size: 18px;
        }

        .btn-golden {
            font-family: "system-ui";
            color: black;
            background: #FFD700;
            font-weight: 600;
        }

        .btn:hover {
            opacity: 0.7;
        }

        .btn-dark {
            background: #343a40;
            font-weight: 600;
        }

        .navbar {
            background-color: rgba(0,0,0,0.1);
        }

        .navigation {
            /* critical sizing and position styles */
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 0;
            /* non-critical appearance styles */
            list-style: none;
            background: #111;
        }

        .item {
            /* non-critical appearance styles */
            width: 200px;
            border-top: 1px solid #111;
            border-bottom: 1px solid #000;
            display: block;
            padding: 1em;
            background: linear-gradient(135deg, rgba(0,0,0,0) 0%,rgba(0,0,0,0.65) 100%);
            color: white;
            font-size: 1.2em;
            text-decoration: none;
            transition: color 0.2s, background 0.5s;
        }
        /* Navigation Menu - List items */
        .nav-item {
            /* non-critical appearance styles */
            width: 200px;
            border-top: 1px solid #111;
            border-bottom: 1px solid #000;
        }

            .nav-item a {
                /* non-critical appearance styles */
                display: block;
                padding: 1em;
                background: linear-gradient(135deg, rgba(0,0,0,0) 0%,rgba(0,0,0,0.65) 100%);
                color: white;
                font-size: 1.2em;
                text-decoration: none;
                transition: color 0.2s, background 0.5s;
            }

                .nav-item a:hover {
                    color: #FFD700;
                    background: linear-gradient(135deg, rgba(0,0,0,0) 0%,rgba(209, 176, 0,0.65) 100%);
                }

        /* Site Wrapper - Everything that isn't navigation */
        .site-wrap {
            /* Critical position and size styles */
            min-height: 100%;
            min-width: 100%;
            background-color: #111; /* Needs a background or else the nav will show through */
            position: relative;
            top: 0;
            bottom: 100%;
            left: 0;
            z-index: 1;
            /* non-critical apperance styles */
            padding: 4em;
            background-size: 200%;
        }

        /* Nav Trigger */
        .nav-trigger {
            position: absolute;
            clip: rect(0, 0, 0, 0);
        }

        label[for="nav-trigger"] {
            /* critical positioning styles */
            position: fixed;
            left: 15px;
            top: 15px;
            z-index: 2;
            /* non-critical apperance styles */
            height: 30px;
            width: 30px;
            cursor: pointer;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' x='0px' y='0px' width='30px' height='30px' viewBox='0 0 30 30' enable-background='new 0 0 30 30' xml:space='preserve'><rect width='30' height='6'/><rect y='24' width='30' height='6'/><rect y='12' width='30' height='6'/></svg>");
            background-size: contain;
        }

        /* Make the Magic Happen */
        /*.nav-trigger + label, .site-wrap {
        transition: left 0.2s;
    }*/

        .nav-trigger:not(:checked) + label {
            left: 215px;
        }

        .nav-trigger:not(:checked) ~ .site-wrap {
            left: 200px;
            box-shadow: 0 0 5px 5px rgba(0,0,0,0.5);
        }

        /* Additional non-critical styles */
        h1, h3, p {
            max-width: 600px;
            margin: 0 auto 1em;
        }
        /* Micro reset */
        *, *:before, *:after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
    </style>
    <script>
        function DeactivateAccount() {
            $("#myModal").modal('show');
            //TO DO: AJAX that deactivates user
        }
    </script>
</head>
<body>
    <ul class="navigation">
        <li class="item">Administration</li>
        <li class="nav-item">
            <a href="#">Home</a>
        </li>
        <li class="nav-item">
            <?php echo '<a href="userProfile.php?id='.$Id.'">Profile</a>';?>
        </li>
        <li class="nav-item">
            <a href="#">Drivers</a>
        </li>
        <li class="nav-item">
            <a href="#">Supervisors</a>
        </li>
        <li class="nav-item">
            <a href="#">Managers</a>
        </li>
        <li class="nav-item">
            <a href="#">New requests</a>
        </li>
    </ul>

    <input type="checkbox" id="nav-trigger" class="nav-trigger" />
    <!--<label for="nav-trigger"></label>-->

    <div class="site-wrap">
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
                <label>First Name:</label>
                <?php echo $row["FirstName"]; ?>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <?php echo $row["LastName"]; ?>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <?php echo $row["Email"]; ?>
            </div>
            <?php echo '<a href="editProfile.php?id='.$Id.'" class="btn btn-info" style="font-size:16px;">Edit Profile</a>'; ?>
            <?php echo '<a href="editPassword.php?id='.$Id.'" class="btn btn-info" style="font-size:16px;">Change Password</a>'; ?>
            <?php echo '<a href="deleteAccount.php?id='.$Id.'" class="btn btn-info" style="font-size:16px;">Delete Account</a>'; ?>
            <a href="home.php" class="btn btn-default" style="font-size:16px;">Return to main page</a>

       </div>
    <!--Modal-->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" class="close" data-dismiss="modal">&times;</a>
                    <h3 class="modal-title">Confirm deactivation</h3>
                </div>
                <div class="modal-body">
                    <h4>Are you sure you want to delete your account? This action is permanent and cannot be reverted.</h4>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-success" onclick="DeactivateAccount()">Confirm</a>
                    <a href="#" class="btn btn-danger" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
