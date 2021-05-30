<?php
include("layout.php");
// Include config file
require_once "configuration.php";

// Define variables and initialize with empty values
$username=$email=$phoneNumber=$firstName=$lastName=$dateOfBirth=$gender="";
$username_error=$email_error=$phoneNumber_error=$firstName_error=$lastName_error=$dateOfBirth_error=$genderError="";
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    // Validate title
    if(empty(trim($_POST["username"]))){
        if(empty(trim($_POST["phoneNumber"])))
            $username = $email;
        else $username = $phoneNumber;
    }
    else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["firstName"]))){
        $firstName = "";
    }
    else{
        $firstName = trim($_POST["firstName"]);
    }
    //TO DO: Check if email and password already exist
    if(empty(trim($_POST["email"]))){
        $email_error = "You must specify an email.";
    }
    else{
        $email = trim($_POST["email"]);
    }
    if(empty(trim($_POST["phoneNumber"]))){
        $phoneNumber_error = "You must specify a phone number.";
    }
    else{
        $phoneNumber = trim($_POST["phoneNumber"]);
    }

    if(empty(trim($_POST["lastName"]))){
        $lastName = "";
    }
    else{
        $lastName = trim($_POST["lastName"]);
    }
    if(empty(trim($_POST["firstName"]))){
        $firstName = "";
    }
    else{
        $firstName = trim($_POST["firstName"]);
    }

    if(empty(trim($_POST["dateOfBirth"]))){
        $dateOfBirth_error = "Please enter your birth date.";
    }
    else{
        $dateOfBirth = trim($_POST["dateOfBirth"]);
        $birthDate = explode("-", $dateOfBirth);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
          ? ((date("Y") - $birthDate[0]) - 1)
          : (date("Y") - $birthDate[0]));
        if($age<13){
            $dateOfBirth_error = "For safety reasons, you must be 13 or older to use our services.";
        }
        else if($age<20 && $_SESSION["Role"]=="Driver"){
            $dateOfBirth_error = "For safety reasons, you must be 20 or older to work as a driver.";
        }
        else if($age>60 && $_SESSION["Role"]=="Driver"){
            $dateOfBirth_error = "For safety reasons, you must be 60 or younger to work as a driver.";
        }
        else if($age>110){
            $dateOfBirth_error = "Please enter a valid date of birth.";
        }
    }
    if(isset($_POST["gender"])){
        $gender = $_POST["gender"];
    }
    else{
        $gender_error = "Please specify your gender";
    }
    // Check input errors before inserting in database
    if(empty($email_error)&&empty($dateOfBirth_error)&& empty($firstName_error)&&empty($gender_error)&&
        empty($lastName_error)&&empty($phoneNumber_error)&&empty($username_error)){
        //    // Prepare an update statement
        $result = mysqli_query($mysqli,"UPDATE users SET Username='$username', Email='$email', PhoneNumber='$phoneNumber',Gender='$gender',DateOfBirth='$dateOfBirth',FirstName='$firstName',LastName='$lastName' WHERE Id='$id'");
        if($result == true){
            //TO DO: Fix script
            echo "<script>";
            echo "debugger;";
            echo "alert('This is an alert from JavaScript!');";
            echo "setTimeout(function{ window.location.href='userProfile.php?id='"+$id+";}, 5000);";
            echo "</script>";
        }
        if (isset($_FILES['profileImage'])) {
            // for the database
            $profileImageName = time() . '-' . $_FILES["profileImage"]["name"];
            // For image upload
            $target_dir = "images/";
            $target_file = $target_dir . basename($profileImageName);
            // VALIDATION
            // validate image size. Size is calculated in Bytes
            if($_FILES['profileImage']['size'] > 200000) {
                $profileImageError = "Image size should not be greated than 200Kb";
            }
            // check if file exists
            if(file_exists($target_file)) {
                $profileImageError = "File already exists";
            }
            // Upload image only if no errors
            if (empty($profileImageError)) {
                if(move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE users SET profileImage='$profileImageName' WHERE Id='$param_id'";
                    if(mysqli_query($mysqli, $sql)){
                        //file uploaded successfully
                    } else {
                        $profileImageError = "There was an error.";
                    }
                } else {
                    $profileImageError = "There was an error uploading image.";
                }
            }
        }

        //// Close connection
        mysqli_close($mysqli);
    }
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        // Set parameters
        $param_id = $id;

        // Prepare a select statement
        $result = mysqli_query($mysqli,"SELECT * FROM Users AS U LEFT JOIN Roles AS R ON RoleId = R.Name WHERE U.Id='$param_id'");
        if(mysqli_num_rows($result) == 1){
            /* Fetch result row as an associative array. Since the result set
            contains only one row, we don't need to use while loop */
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            // Retrieve individual field value
            $username = $row["Username"];
            $email = $row["Email"];
            $phoneNumber = $row["PhoneNumber"];
            $firstName = $row["FirstName"];
            $lastName = $row["LastName"];
            $isActive= $row["IsActive"];
            $isBanned = $row["IsBanned"];
            $dateOfBirth = $row["DateOfBirth"];
            $gender = $row["Gender"];
            $roleName = $row["Name"];
        } else{
            // URL doesn't contain valid id. Redirect to error page
            header("location: error.php");
            exit();
        }

        //    } else{
        //        echo "Something went wrong. Please try again later.";
        //    }
        //}

        //// Close statement
        //mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($mysqli);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <style>
        #goldPaint{
                position: absolute;
    left: -96%;
    width: 166%;
    top: -72%;
    z-index: -3;
    opacity: 0.6;
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
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1 !important;
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
        width: 200px;
        border-top: 1px solid #111;
        border-bottom: 1px solid #000;
    }

        .nav-item a {
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

    .site-wrap {
        min-height: 100%;
        min-width: 100%;
        background-color: #111;
        position: relative;
        top: 0;
        bottom: 100%;
        left: 0;
        z-index: 1;
        padding: 4em;
        background-size: 200%;
    }

    .nav-trigger {
        position: absolute;
        clip: rect(0, 0, 0, 0);
    }

    label[for="nav-trigger"] {
        position: fixed;
        left: 15px;
        top: 15px;
        z-index: 2;
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

    #profileDisplay {
    display: block;
    height: auto;
    width: 40%;
    border-radius: 50%;
    margin-left: 30%;

    }

    .img-placeholder {
        width: 33%;
        color: white;
        background: black;
        opacity: .7;
        height: 245px;
        border-radius: 50%;
        z-index: 2;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        display: none;
    }

        .img-placeholder h4 {
            margin-top: 40%;
            color: white;
        }

    .img-div:hover .img-placeholder {
        display: block;
        cursor: pointer;
    }

        body {
            background: #333333;
            margin-top: 20px;
            font-family: "system-ui" !important;
            font-weight: 600;
            overflow-x:hidden;
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
            z-index: -1;
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
    </style>
    <script>
        function triggerClick(e) {
            document.querySelector('#profileImage').click();
        }
        function displayImage(e) {
            if (e.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('#profileDisplay').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(e.files[0]);
            }
        }
    </script>



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
            <?php echo '<a href="userProfile.php?id='. $param_id.'">Profile</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="pastServices.php?id='. $param_id.'&role='.$roleName.'">Past Services</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="awaitingServices.php?id='. $param_id.'&role='.$roleName.'">Awaiting Services</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="yourReviews.php?id='. $param_id.'">Your reviews</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="yourComplaints.php?id='. $param_id.'">Your complaints</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="logout.php">Sign Out</a>';?>
        </li>
        <img src="Images/circle_PNG62.png" id="blackCircle" />>

    </ul>
        </ul>
  <input type="checkbox" id="nav-trigger" class="nav-trigger">
   
    <div class="site-wrap">
    <div class="row">
        
        <div class="col-md-6">
            
            <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" style="font-size:16px;" name="firstName" class="form-control <?php echo (!empty($firstName_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>" />
                                <span class="help-block text-danger pl-2"><?php echo $firstName_error;?>
                                </span>
                            </div>
                </div>
                        <div class="col-md-6">
                            <div class="form__group">
                                <label>Last Name</label>
                                <input type="text" style="font-size:16px;" name="lastName" class="form-control <?php echo (!empty($lastName_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>" />
                                <span class="help-block text-danger pl-2"><?php echo $lastName_error;?>
                                </span>
                            </div>
                        </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form__group">
                        <label>Username</label>
                        <input type="text" style="font-size:16px;" name="username"class="form-control <?php echo (!empty($username_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" />
                        <span class="help-block text-danger pl-2"><?php echo $username_error;?>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form__group">
                        <label>Email</label>
                        <input type="email" style="font-size:16px;" name="email" class="form-control <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" />
                        <span class="help-block text-danger pl-2"><?php echo $email_error;?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                 <div class="col-md-6">
                    <div class="form__group">
                        <label>Phone number</label>
                        <input type="text" style="font-size:16px;" name="phoneNumber" class="form-control <?php echo (!empty($phoneNumber_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $phoneNumber; ?>" />
                        <span class="help-block text-danger pl-2"><?php echo $phoneNumber_error;?>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form__group">
                        <label>Date of Birth</label>
                        <input type="date" style="font-size:16px;" name="dateOfBirth" class="form-control <?php echo (!empty($dateOfBirth_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $dateOfBirth; ?>" />
                        <span class="help-block text-danger pl-2"><?php echo $dateOfBirth_error;?>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form__group help-block">
                            <label class="check" for="genderO" style="font-size:14px;color:#808080;">Gender:</label>
                            <?php if ($gender == "m"){
                                echo "<input type='radio' name='gender' value='m' id='genderM' class='ml-3 mr-2' style='font-size:larger;' checked/>";
                                      } else{
                                echo "<input type='radio' name='gender' value='m' id='genderM' class='ml-3 mr-2' style='font-size:larger;' />";
                                }?>
                                  <label class="check" for="genderM" style="font-size:14px;color:#808080;">Male</label> 
                                      <?php if ($gender == "f"){
                                    echo "<input type='radio' name='gender' value='f' id='genderF' class='ml-3 mr-2' style='font-size:larger;' checked/>";
                                } else{
                                    echo "<input type='radio' name='gender' value='f' id='genderF' class='ml-3 mr-2' style='font-size:larger;' />";
                                                                                                                     }?>
                  <!--<input type="radio" name="gender" value="f" id="genderF" class="ml-3 mr-2" style="font-size:larger;" />
              -->

                  <label class="check" for="genderF" style="font-size:14px;color:#808080;">Female</label><?php if ($gender == "o"){
                                    echo "<input type='radio' name='gender' value='o' id='genderO' class='ml-3 mr-2' style='font-size:larger;' checked/>";
                                } else{
                                    echo "<input type='radio' name='gender' value='o' id='genderO' class='ml-3 mr-2' style='font-size:larger;' />";
                                                                                                               }?>
                  <!--<input type="radio" name="gender" value="o" id="genderO" class="ml-3 mr-2" style="font-size:larger;" />
              -->
                  <label class="check" for="genderO" style="font-size:14px;color:#808080;">Other</label>
                                                                                                        
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="row">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </form>
        </div>
        
        <div class="col-md-6">
             <div class="form-group text-center" style="position: relative;">
                <span class="img-div">
                    <div class="text-center img-placeholder" onclick="triggerClick()">
                        <h4>Upload image</h4>
                    </div>
                    <img src="Images/logo2.png" id="profileDisplay" onclick="triggerClick()">
                </span>
                <input type="file" name="profileImage" id="profileImage" class="form-control" style="display: none;" onchange="displayImage(this)">
                <label>Profile Image</label>
            </div>           
  <img src="Images/gold3.png" id="goldPaint">          
        </div>
        

    </div>
  </div>


    
</body>
</html>