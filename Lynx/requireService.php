<?php
session_start();
require_once "configuration.php";
$date_error=$time_error=$address_error=$destination_error="";
if($_SERVER["REQUEST_METHOD"]=="GET"){
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $param_userid = trim($_GET["id"]);
    }
    else{
        $param_userid = $_SESSION["Id"];
    }
    $_SESSION["serviceUserId"] = $param_userid;
    if(isset($_GET["driverId"]) && !empty(trim($_GET["driverId"]))){
        $param_driverId = trim($_GET["driverId"]);
    }
    $_SESSION["serviceDriverId"] = $param_driverId;
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $param_driver = $_SESSION["serviceDriverId"];
    $param_user = $_SESSION["serviceUserId"];
    if(empty(trim($_POST["date"]))){
        $date_error = "You must enter the service date";
    }
    else{
        $date = trim($_POST["date"]);
    }
    if(empty(trim($_POST["time"]))){
        $time_error = "You must enter the service time";
    }
    else{
        $time = trim($_POST["time"]);
    }
    if(empty(trim($_POST["address"]))){
        $address_error = "You must enter your address";
    }
    else{
        $address = trim($_POST["address"]);
    }
    if(empty(trim($_POST["destination"]))){
        $destination_error = "You must enter a destination";
    }
    else{
        $destination = trim($_POST["destination"]);
    }
    if(empty($date_error) && empty($time_error) && empty($address_error) && empty($destination_error)){
        $param_id = uniqid();
        $service_time = date('Y-m-d h:i:s',strtotime($_POST['date'].' '.$_POST['time']));
        $result = mysqli_query($mysqli,"INSERT INTO Requests(Id, RequestedById, DriverId, TimeOfRequest, IsAccepted, IsSeen) values ('$param_id','$param_user','$param_driver','$service_time',0,0);");
        if($result){

        }
        else{
            header("location: error.php");
        }
    }
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
        <img src="Images/circle_PNG62.png" id="blackCircle" />
    </ul>

    <input type="checkbox" id="nav-trigger" class="nav-trigger" />
    <!--<label for="nav-trigger"></label>-->

    <div class="site-wrap">
        <!--<header class="user__header">
            <img src="Images/logo2.png" height="150" />
            <h1 class="user__title">These changes are permanent and cannot be reverted.</h1>
        </header>-->
        <!--<input type="hidden" id="id" name="id" value="<?php $param_userid?>" />
        <input type="hidden" id="driverId" name="driverId" value="<?php $param_driverId?>" />-->
        <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form__group" <?php echo(!empty($date_error))? 'has-error' : '' ?>>
                <input type="date" placeholder="Date" class="form__input" name="date" id="date" />
                <span class="help-block text-danger">
                    <?php echo $date_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($time_error))? 'has-error': ''?>>
                <input type="time" placeholder="Time" class="form__input" name="time" id="time" />
                <span class="help-block text-danger">
                    <?php echo $time_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($confirmNewPassword_error))? 'has-error': ''?>>
                <input type="text" placeholder="Address" class="form__input" name="address" id="address" />
                <span class="help-block text-danger">
                    <?php echo $address_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($destination_error))? 'has-error': ''?>>
                <input type="text" placeholder="Destination" class="form__input" name="destination" id="destination" />
                <span class="help-block text-danger">
                    <?php echo $destination_error ?>
                </span>
            </div>
            <button class="btn" type="submit">Confirm</button>
        </form>
        <button class="btn btn-info" id="myBtn" onclick="goBack()">Cancel</button>
    </div>
    <div class="modal fade" id="thankyouModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Thank you for pre-registering!</h4>
                </div>
                <div class="modal-body">
                    <p>Thanks for getting in touch!</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    debugger
    function goBack() {
        window.history.back();
    }
</script>
