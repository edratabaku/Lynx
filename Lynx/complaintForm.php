<?php
session_start();
require_once "configuration.php";
if($_SERVER["REQUEST_METHOD"]=="GET"){
    if(isset($_GET["writerId"]) && !empty(trim($_GET["writerId"]))){
        $param_userid = trim($_GET["writerId"]);
        $param_userRole = trim($_SESSION["Role"]);
    }
    else{
        $param_userid = $_SESSION["Id"];
       
    }
    $_SESSION["complaintWriterId"] = $param_userid;
    if(isset($_GET["subjectId"]) && !empty(trim($_GET["subjectId"]))){
        $param_driverId = trim($_GET["subjectId"]);
    }
    $_SESSION["complaintSubjectId"] = $param_driverId;
    $result = mysqli_query($mysqli,"SELECT u.FirstName, u.LastName, u.Id as driverId from Drivers as d inner join Users as u on u.Id = d.UserId where d.Id='$param_driverId'");
    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $driverName = $row["FirstName"]." ".$row["LastName"];
        $param_driverId = $row["driverId"];
    }
    $_SESSION["complaintSubjectId"] = $param_driverId;
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $param_subject = $_SESSION["complaintSubjectId"];
    $param_writer = $_SESSION["complaintWriterId"];
    if(empty(trim($_POST["text"]))){
        $text_error = "You must write text for your complaint.";
    }
    else{
        $text = trim($_POST["text"]);
    }
    if(empty($text_error)){
        $param_id = uniqid();
        $result = mysqli_query($mysqli,"INSERT INTO Complaints(Id, WriterId, SubjectId, Text, IsActive) values ('$param_id','$param_writer','$param_subject','$text',1);");
        if($result){
            //
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
    <link rel="stylesheet" href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
            <?php echo '<a href="userProfile.php?id='.$param_userid.'">Profile</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="pastServices.php?id='.$param_userid.'&role='. $param_userRole.'">Past Services</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="awaitingServices.php?id='.$param_userid.'&role='. $param_userRole.'">Awaiting Services</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="yourReviews.php?id='.$param_userid.'">Your reviews</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="yourComplaints.php?id='.$param_userid.'">Your complaints</a>';?>
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
        </header>
        <form class="form" id="complaintForm" method="POST">
            <input type="hidden" id="testId" name="testId" value="<?php echo $param_userid?>" />
            <div class="form__group">
                <label for="text" class="text-white" style="font-size:larger;">
                    Your Complaint for <?php echo $driverName ?>
                </label>
                <textarea class="form-control" rows="5" id="text" name="text"></textarea>
            </div>
            <div class="form__group">
                <button type="submit" class="btn btn-golden mb-2 mt-2" id="saveComplaint">Send Complaint</button>
                <button type="button" class="btn btn-golden" id="cancelReview" onclick="goBack()">Cancel</button>
            </div>
        </form>
    </div>
    <div class="modal fade" id="thankyouModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Success!</h4>
                </div>
                <div class="modal-body">
                    <p>Your complaint was submitted successfully!</p>
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
