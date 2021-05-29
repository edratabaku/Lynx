<?php
//include layout file
include("layout.php");
//add the configuration file
require_once "configuration.php";
//initializing variables
$email="";
$password="";
$username="";
$confirmPassword="";
$firstName="";
$lastName="";
$phoneNumber="";
$dateOfBirth = "";
 $gender = "";
//initializing error validation messages
$email_error=$password_error=$username_error=$confirmPassword_error=$firstName_error=$lastName_error=$phoneNumber_error=$dateOfBirth_error=$gender_error="";
$profileImageError="";
//check if the request is a post request
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //if there is no email address or phone number specified
    if((empty(trim($_POST["email"])))&&(empty(trim($_POST["phoneNumber"])))){
        $email_error="You must enter an email or phone number.";
    }
    else{
        //prepare statement to find if user with the same email/phone number exists
        $sql = "SELECT Id FROM users WHERE email = ? or phonenumber = ?";
        
        if($stmt = mysqli_prepare($mysqli,$sql)){
            //bind parameters to statement
            mysqli_stmt_bind_param($stmt,"ss",$param_email,$param_number);
            $param_email = trim($_POST["email"]);
            $param_number = trim($_POST["phoneNumber"]);
            //execute statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                //email address already exists
                if(mysqli_stmt_num_rows($stmt)==1){
                    $email_error="This account already exists.";
                    $phoneNumber_error="This account already exists.";
                }
                else{
                    $phoneNumber = trim($_POST["phoneNumber"]);
                    $email = trim($_POST["email"]);
                }
            }
            else{
                echo "An error occurred. Please try again later.";
            }

        }
        //close statement
        mysqli_stmt_close($stmt);
    }
    //password must not be empty
    if(empty(trim($_POST["password"]))){
        $password_error = "You must provide a password";
    }
    //password length must be 8 characters or more
    else if(strlen(trim($_POST["password"]))<8){
        $password_error = "Password must have a minimum length of 8.";
    }
    else{
        $password = trim($_POST["password"]);
    }
    //check for username is empty
    if(empty(trim($_POST["username"]))){
        //if phone number is empty, use email as username
        if(empty(trim($_POST["phoneNumber"])))
            $username = $email;
        //else use phone number as username
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

    if(empty(trim($_POST["lastName"]))){
        $lastName = "";
    }
    else{
        $lastName = trim($_POST["lastName"]);
    }
    $test_pass = $password;
    $test_confPass = trim($_POST["confirmPassword"]);
    if(empty(trim($_POST["confirmPassword"]))){
        $confirmPassword_error = "Please confirm your password";
    }
    //if password and its confirmation do not match
    else if(strcmp($test_pass,$test_confPass) != 0){
        $confirmPassword_error = "Your password and confirm password do not match.";
    }
    else{
        $confirmPassword = trim($_POST["confirmPassword"]);
    }
    if(empty(trim($_POST["dateOfBirth"]))){
        $dateOfBirth_error = "Please enter your birth date.";
    }
    else{
        $dateOfBirth = trim($_POST["dateOfBirth"]);
        $birthDate = explode("-", $dateOfBirth);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
          ? ((date("Y") - $birthDate[0]) - 1) : (date("Y") - $birthDate[0]));
        if($age<13 && $_POST["isDriver"]==false){
            $dateOfBirth_error = "For safety reasons, you must be 13 or older to use our services.";
        }
        else if($age<20 && $_POST["isDriver"]==true){
            $dateOfBirth_error = "For safety reasons, you must be 20 or older to register as a driver.";
        }
        else if($age>60 && $_POST["isDriver"]==true){
            $dateOfBirth_error = "For safety reasons, you must be 60 or younger to register as a driver.";
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
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $email_error = "Invalid email format";
    }
    //check if there are any errors
    if(empty($email_error) && empty($password_error) && empty($confirmPassword_error) && empty($username_error)&&empty($phoneNumber_error)&&empty($dateOfBirth_error)&&empty($gender_error)){
        //prepare statement
        $sql = "INSERT INTO users(Id,Email,PhoneNumber,Password,ConfirmPassword,Username,FirstName,LastName,IsActive,RoleId,DateOfBirth,Gender) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            $param_email = $email;
            $param_number = $phoneNumber;
            //password as hash
            $param_password = hash('sha512', $password);
            $param_password = password_hash($param_password, PASSWORD_DEFAULT);
            $param_confirmPassword = hash('sha512', $password);
            $param_confirmPassword = password_hash($param_confirmPassword, PASSWORD_DEFAULT);
            $param_username = $username;
            $param_firstName = $firstName;
            $param_lastName= $lastName;
            $param_isActive = true;
            //if user wants to register as driver
             if(isset($_POST["isDriver"]) && $_POST["isDriver"]==true){
                $param_roleName = "Driver";
                //get the ide of the driver role
                $result = mysqli_query($mysqli,"SELECT Id, Name FROM Roles WHERE Name='$param_roleName'");
                if(mysqli_num_rows($result)==1){
                    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                    $param_roleId = $row["Id"];
                }
            }
            else{
                $param_roleName = "User";
                $result = mysqli_query($mysqli,"SELECT Id, Name FROM Roles WHERE Name='$param_roleName'");
                if(mysqli_num_rows($result)==1){
                    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                    $param_roleId = $row["Id"];
                }
            }
            //close statement
            //mysqli_stmt_close($stmt2);
            //generate unique id
            $param_id = uniqid();
            $param_dateOfBirth = $dateOfBirth;
            $param_gender = $gender;
            //bind parameters to statement
            mysqli_stmt_bind_param($stmt,"ssssssssssss", $param_id,$param_email,$param_number,$param_password,$param_confirmPassword,$param_username,$param_firstName,$param_lastName,$param_isActive,$param_roleId,$param_dateOfBirth,$param_gender);
            if(mysqli_stmt_execute($stmt)){
                //start a new session
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["Id"] = $param_id;
                $_SESSION["Email"] = $email;
                $_SESSION["Username"] = $username;
                $_SESSION["PhoneNumber"] = $phoneNumber;
                $_SESSION["FirstName"] = $firstName;
                $_SESSION["LastName"] = $lastName;
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
                if(isset($_POST["isDriver"]) && $_POST["isDriver"]==true){
                    $_SESSION["Role"] = "Driver";
                    header("location:send_email.php");
                    //header("location:driverSpecifications.php");
                }
                else{
                    $_SESSION["Role"] = "User";
                    header("location:send_email.php");
                    //header("location: userLayout.php?page=index");
                }
            }
            else{
                echo "An error occured. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($mysqli);

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Regjistrohu</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <style type="text/css">
<?php include 'CSS/authentication.css'; ?><?php include 'CSS/register.css'; ?>        
        #profileDisplay {
            display: block;
            height: auto;
            width: 16%;
            margin: 0px auto;
            border-radius: 50%;
        }

        .img-placeholder {
            width: 18%;
            color: white;
            height: 81%;
            background: black;
            opacity: .7;
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
    <section class="back"></section>
    <div class="user">
        <!--<header class="user__header">
            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3219/logo.svg" alt="" />
            <h1 class="user__title">Sign up to access the app.</h1>
        </header>-->
        <div class="row">
            <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group text-center" style="position: relative;" >
            <span class="img-div">
              <div class="text-center img-placeholder"  onClick="triggerClick()">
                <h4>Upload image</h4>
              </div>
              <img src="images/avatar.png" onClick="triggerClick()" id="profileDisplay">
            </span>
            <input type="file" name="profileImage" onChange="displayImage(this)" id="profileImage" class="form-control" style="display: none;">
            <label>Profile Image</label>
          </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row no-gutters">
                            <div class="col-md-6">
                                <div class="form__group">
                                    <input type="text" placeholder="First Name" class="form__input" name="firstName" id="firstName" value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName'], ENT_QUOTES) : ''; ?>" />
                                    <span class="help-block text-danger pl-2">
                                        <?php echo $firstName_error ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form__group">
                                    <input type="text" placeholder="Last Name" class="form__input" name="lastName" id="lastName" value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName'], ENT_QUOTES) : ''; ?>" />
                                    <span class="help-block text-danger pl-2">
                                        <?php echo $lastName_error ?>
                                    </span>
                                </div>
                            </div>
                        </div></div>
                        <div class="col-md-6">
                        <div class="form__group" <?php echo(!empty($username_error))? 'has-error' : ''?>>
                            <input type="text" placeholder="Username" class="form__input" name="username" id="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : ''; ?>" />
                            <span class="help-block text-danger pl-2">
                                <?php echo $username_error?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form__group" <?php echo(!empty($email_error))? 'has-error' : '' ?>>
                            <input type="email" placeholder="Email" class="form__input" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ''; ?>" />
                            <span class="help-block text-danger pl-2">
                                <?php echo $email_error ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form__group" <?php echo(!empty($phoneNumber_error))? 'has-error' : '' ?>>
                            <input type="text" placeholder="Phone number" class="form__input" name="phoneNumber" id="phoneNumber" value="<?php echo isset($_POST['phoneNumber']) ? htmlspecialchars($_POST['phoneNumber'], ENT_QUOTES) : ''; ?>" />
                            <span class="help-block text-danger pl-2">
                                <?php echo $phoneNumber_error ?>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form__group" <?php echo(!empty($password_error))? 'has-error': ''?>>
                            <input type="password" placeholder="Password" class="form__input" name="password" id="password" />
                            <span class="help-block text-danger pl-2">
                                <?php echo $password_error ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form__group" <?php echo(!empty($confirmPassword_error))? 'has-error': ''?>>
                            <input type="password" placeholder="Confirm Password" class="form__input" name="confirmPassword" id="confirmPassword" />
                            <span class="help-block text-danger pl-2">
                                <?php echo $confirmPassword_error ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="form__group" <?php echo(!empty($dateOfBirth_error))? 'has-error' : '' ?>>
                            <input type="date" placeholder="Date of birth" class="form__input" name="dateOfBirth" id="dateOfBirth" value="<?php echo isset($_POST['dateOfBirth']) ? htmlspecialchars($_POST['dateOfBirth'], ENT_QUOTES) : ''; ?>" />
                            <span class="help-block text-danger pl-2">
                                <?php echo $dateOfBirth_error ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form__group help-block">
                                <label style="color:#808080;" class="check mr-4 ml-3">Gender:</label>
                                <input type="radio" name="gender" value="m" id="genderM" class="ml-3 mr-2" style="font-size:larger;" checked />
                                <label class="check" for="genderM" style="color:#808080;">Male</label>
                                <input type="radio" name="gender" value="f" id="genderF" class="ml-3 mr-2" style="font-size:larger;" />
                                <label class="check" for="genderF" style="color:#808080;">Female</label>
                                <input type="radio" name="gender" value="o" id="genderO" class="ml-3 mr-2" style="font-size:larger;" />
                                <label class="check" for="genderO" style="color:#808080;">Other</label>
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="form__group help-block">
                        <input type="checkbox" name="isDriver" value="true" id="isDriver" class="ml-3 mr-2" style="font-size:larger;" />
                        <label for="isDriver" style="color:#c8c8c8;">Register as Driver</label>
                    </div>
                    <button class="btn" type="submit">Register</button>
                </div>
                <div class="row">
                     <div class="col-md-12 text__login justify-content-center mt-3">
                        <a href="login.php" class="text__login">Already have an account? Log In</a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</body>
</html>


