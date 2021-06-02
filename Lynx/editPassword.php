<?php
session_start();
require_once "configuration.php";
$oldPassword_error=$newPassword_error=$confirmNewPassword_error="";
if(isset($_GET["id"])&& !empty(trim($_GET["id"]))){
    $param_id = trim($_GET["id"]);
    $result = mysqli_query($mysqli,"SELECT Password,r.Name as RoleName FROM USERS as u INNER JOIN Roles AS R ON RoleId = R.Id WHERE u.Id='$param_id'");
    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $param_oldPassword = $row["Password"];
        $param_roleName = $row["RoleName"];
    }
}
else{
    $param_id = $_SESSION["Id"];
    $result = mysqli_query($mysqli,"SELECT Password,r.Name as RoleName FROM USERS as u INNER JOIN Roles AS R ON RoleId = R.Id WHERE u.Id='$param_id'");
    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $param_oldPassword = $row["Password"];
        $param_roleName = $row["RoleName"];
    }
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["oldPassword"]))){
        $oldPassword_error = "You must enter your old password.";
    } else{
        $oldPassword = trim($_POST["oldPassword"]);
    }
    if(empty(trim($_POST["newPassword"]))){
        $newPassword_error = "You must enter your new password.";
    }
    else if(strlen(trim($_POST["newPassword"]))<8){
        $newPassword_error = "Your password must contain at least 8 characters.";
    }
    else{
        $newPassword = trim($_POST["newPassword"]);
    }
    if(empty(trim($_POST["confirmNewPassword"]))){
        $confirmNewPassword_error = "You must confirm your new password.";
    }
    else if(strcmp($_POST["confirmNewPassword"],$_POST["newPassword"]) != 0){
        $confirmPassword_error = "Your new password and confirm password do not match.";
    }
    else{
        $confirmNewPassword = trim($_POST["confirmNewPassword"]);
    }
    if(empty($newPassword_error) && empty($confirmNewPassword_error) && empty($oldPassword_error)){
        $result = mysqli_query($mysqli,"SELECT Password FROM Users Where Id ='$param_id'");
        if(mysqli_num_rows($result)==1){
            $hash_password = $row["Password"];
            $oldPassword = hash("sha512",$oldPassword);
            if(password_verify($oldPassword,$hash_password)){
                $param_password = hash('sha512', $_POST["newPassword"]);
                $param_password = password_hash($param_password, PASSWORD_DEFAULT);
                $updatePassword = "UPDATE users SET Password = '$param_password' WHERE Id = '$param_id'";
                if (mysqli_query($mysqli, $updatePassword)) {
                    $updateConfirmPassword = "UPDATE users SET ConfirmPassword = '$param_password' WHERE Id = '$param_id'";
                    if(mysqli_query($mysqli,$updateConfirmPassword)){
                        //modal
                    }
                    else{
                        header("location: error.php");
                    }
                }
                else{
                    header("location: error.php");
                }
            }
            else{
                $oldPassword_error="This password does not match your current password.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    header("location: userProfile.php?id="+$param_id);
}
mysqli_close($mysqli); ?>



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

        .btn-info {
            color: #fff;
            background-color: transparent;
            border-color: #ff9933;
            margin-right: 12px;
        }

            .btn-info:hover {
                background-color: #996900;
                border-color: #f8f9fa;
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
            padding: 4em 1rem 5rem 4rem;
            background-size: 200%;
            color: #ffffffb3;
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

        #profilePic {
            width: 200px;
            height: auto;
            border-radius: 50%;
            box-shadow: -9px 7px 16px #2f2006;
        }

        #watercolor {
            z-index: -2;
            position: absolute;
            left: -33%;
            width: 25vw;
            top: -24%;
        }

        #returnHome {
            color: rgb(146 199 255);
        }

        #title:hover {
            color: #caa245;
        }

        #blackCircle {
            z-index: -10;
            position: absolute;
            top: 0%;
            width:39%;
            animation: mymove 10s infinite;
        }

        @keyframes mymove {
            0% {
                left: 3%;
            }

            50% {
                left: 5%;
            }

            100% {
                left: 3%;
            }
        }
<?php include 'CSS/authentication.css'; ?>
    </style>
</head>
<body>
    <ul class="navigation">
        <li class="item">
            <img src="Images/logo2.png" height="150" />
        </li>
        <li class="nav-item">
            <a href="userLayout.php?page=index">Home</a>
        </li>
        <li class="nav-item">
            <?php echo '<a href="userProfile.php?id='.$param_id.'">Profile</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="pastServices.php?id='.$param_id.'&role='.$param_roleName.'">Past Services</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="awaitingServices.php?id='.$param_id.'&role='.$param_roleName.'">Awaiting Services</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="yourReviews.php?id='.$param_id.'">Your reviews</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="yourComplaints.php?id='.$param_id.'">Your complaints</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="logout.php">Sign Out</a>';?>
        </li>
        <img src="Images/circle_PNG62.png" id="blackCircle" />>

    </ul>
    <input type="checkbox" id="nav-trigger" class="nav-trigger" />
    <!--<label for="nav-trigger"></label>-->

    <div class="site-wrap">
        <header class="user__header">
            <img src="Images/logo2.png" height="150" />
            <h1 class="user__title">These changes are permanent and cannot be reverted.</h1>
        </header>
        <input type="hidden" id="userId" value="<?php $param_id?>" />
        <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form__group" <?php echo(!empty($oldPassword_error))? 'has-error' : '' ?>>
                <input type="password" placeholder="Old Password" class="form__input" name="oldPassword" id="oldPassword" />
                <span class="help-block text-danger">
                    <?php echo $oldPassword_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($newPassword_error))? 'has-error': ''?>>
                <input type="password" placeholder="New Password" class="form__input" name="newPassword" id="newPassword" />
                <span class="help-block text-danger">
                    <?php echo $newPassword_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($confirmNewPassword_error))? 'has-error': ''?>>
                <input type="password" placeholder="Confirm New Password" class="form__input" name="confirmNewPassword" id="confirmNewPassword" />
                <span class="help-block text-danger">
                    <?php echo $confirmNewPassword_error ?>
                </span>
            </div>
            <button class="btn" type="submit">Confirm</button>
        </form>
        <button class="btn btn-info" id="myBtn" onclick="goBack()" style="width: 48%; margin-left: 25%; margin-top: 1%;">
            Cancel
        </button>
        </div>
    </body>
</html>
<script>
    debugger
    function goBack() {
        window.history.back();
    }
</script>
