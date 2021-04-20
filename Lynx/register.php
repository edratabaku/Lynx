<?php
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
$email_error=$password_error=$username_error=$confirmPassword_error=$firstName_error=$lastName_error=$phoneNumber_error=$dateOfBirth_error=$gender_error="";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //if there is no email address specified
    if((empty(trim($_POST["email"])))&&(empty(trim($_POST["phoneNumber"])))){
        $email_error="You must enter an email or phone number.";
    }
    else{
        //prepare statement
        $sql = "SELECT Id FROM users WHERE email = ? or phonenumber = ?";

        if($stmt = mysqli_prepare($mysqli,$sql)){
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
            mysqli_stmt_close($stmt);
        }
    }
    if(empty(trim($_POST["password"]))){
        $password_error = "You must provide a password";
    }
    else if(strlen(trim($_POST["password"]))<8){
        $password_error = "Password must have a minimum length of 8.";
    }
    else{
        $password = trim($_POST["password"]);
    }

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
          ? ((date("Y") - $birthDate[0]) - 1)
          : (date("Y") - $birthDate[0]));
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
    if(empty($email_error) && empty($password_error) && empty($confirmPassword_error) && empty($username_error)&&empty($phoneNumber_error)&&empty($dateOfBirth_error)&&empty($gender_error)){
        $sql = "INSERT INTO users(Id,Email,PhoneNumber,Password,ConfirmPassword,Username,FirstName,LastName,IsActive,RoleId,DateOfBirth,Gender) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            $param_email = $email;
            $param_number = $phoneNumber;
            $param_password = hash('sha512', $password);
            $param_password = password_hash($param_password, PASSWORD_DEFAULT);
            $param_confirmPassword = hash('sha512', $password);
            $param_confirmPassword = password_hash($param_confirmPassword, PASSWORD_DEFAULT);
            $param_username = $username;
            $param_firstName = $firstName;
            $param_lastName= $lastName;
            $param_isActive = true;
            $otherSql = "SELECT * FROM Roles WHERE Name=?";
            if($stmt2 = mysqli_prepare($mysqli,$otherSql)){
                $param_name = "User";
                mysqli_stmt_bind_param($stmt2,"s",$param_name);
                if(mysqli_stmt_execute($stmt2)){
                    mysqli_stmt_store_result($stmt2);
                    if(mysqli_stmt_num_rows($stmt2)==1){
                        mysqli_stmt_bind_result($stmt2,$id,$name);
                        if(mysqli_stmt_fetch($stmt2)){
                            $param_roleId =  $id;
                        }
                    }
                }
            }
            mysqli_stmt_close($stmt2);
            $param_id = uniqid();
            $param_dateOfBirth = $dateOfBirth;
            $param_gender = $gender;
            mysqli_stmt_bind_param($stmt,"ssssssssssss", $param_id,$param_email,$param_number,$param_password,$param_confirmPassword,$param_username,$param_firstName,$param_lastName,$param_isActive,$param_roleId,$param_dateOfBirth,$param_gender);
            if(mysqli_stmt_execute($stmt)){
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["Id"] = $personId;
                $_SESSION["Email"] = $email;
                $_SESSION["Username"] = $username;
                $_SESSION["PhoneNumber"] = $phoneNumber;
                $_SESSION["Role"] = "User";
                $_SESSION["FirstName"] = $firstName;
                $_SESSION["LastName"] = $lastName;
                if(isset($_POST["isDriver"]) && $_POST["isDriver"]==true){
                    $otherSql = "SELECT * FROM Roles WHERE Name=?";
                    if($stmt2 = mysqli_prepare($mysqli,$otherSql)){
                        $param_name = "Driver";
                        mysqli_stmt_bind_param($stmt2,"s",$param_name);
                        if(mysqli_stmt_execute($stmt2)){
                            mysqli_stmt_store_result($stmt2);
                            if(mysqli_stmt_num_rows($stmt2)==1){
                                mysqli_stmt_bind_result($stmt2,$id,$name);
                                if(mysqli_stmt_fetch($stmt2)){
                                    $param_roleId =  $id;
                                }
                            }
                        }
                    }
                    mysqli_stmt_close($stmt2);
                    $otherSql = "UPDATE Users SET RoleId = ? WHERE Id = ?";
                    if($stmt2 = mysqli_prepare($mysqli,$otherSql)){
                        $param_name = "Driver";
                        mysqli_stmt_bind_param($stmt2,"ss",$param_roleId,$param_id);
                         if(mysqli_stmt_execute($stmt2)){
                             $_SESSION["Role"] = "Driver";
                             header("location:driverSpecifications.php");
                         }
                         else{
                             echo "Ndodhi nje gabim. Provoni perseri.";
                         }
                    }
                    mysqli_stmt_close($stmt2);
                }
                else header("location: userLayout.php?page=index");
            }
            else{
                echo "Ndodhi nje gabim. Provoni perseri.";
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
<?php include 'CSS/authentication.css'; ?>
    </style>
</head>
<body>
    <div class="user">
        <header class="user__header">
            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3219/logo.svg" alt="" />
            <h1 class="user__title">Sign up to access the app.</h1>
        </header>
        <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="form__group">
                        <input type="text" placeholder="First Name" class="form__input" name="firstName" id="firstName" value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName'], ENT_QUOTES) : ''; ?>" />
                        <span class="help-block text-danger pl-2"><?php echo $firstName_error ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form__group">
                        <input type="text" placeholder="Last Name" class="form__input" name="lastName" id="lastName" value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['lastName'], ENT_QUOTES) : ''; ?>" />
                        <span class="help-block text-danger pl-2"><?php echo $lastName_error ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form__group" <?php echo(!empty($username_error))? 'has-error' : ''?>>
                <input type="text" placeholder="Username" class="form__input" name="username" id="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : ''; ?>" />
                <span class="help-block text-danger pl-2"><?php echo $username_error?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($email_error))? 'has-error' : '' ?>>
                <input type="email" placeholder="Email" class="form__input" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ''; ?>" />
                <span class="help-block text-danger pl-2"><?php echo $email_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($phoneNumber_error))? 'has-error' : '' ?>>
                <input type="text" placeholder="Phone number" class="form__input" name="phoneNumber" id="phoneNumber" value="<?php echo isset($_POST['phoneNumber']) ? htmlspecialchars($_POST['phoneNumber'], ENT_QUOTES) : ''; ?>" />
                <span class="help-block text-danger pl-2"><?php echo $phoneNumber_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($password_error))? 'has-error': ''?>>
                <input type="password" placeholder="Password" class="form__input" name="password" id="password"/>
                <span class="help-block text-danger pl-2"><?php echo $password_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($confirmPassword_error))? 'has-error': ''?>>
                <input type="password" placeholder="Confirm Password" class="form__input" name="confirmPassword" id="confirmPassword" />
                <span class="help-block text-danger pl-2"><?php echo $confirmPassword_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($dateOfBirth_error))? 'has-error' : '' ?>>
                <input type="date" placeholder="Date of birth" class="form__input" name="dateOfBirth" id="dateOfBirth" value="<?php echo isset($_POST['dateOfBirth']) ? htmlspecialchars($_POST['dateOfBirth'], ENT_QUOTES) : ''; ?>" />
                <span class="help-block text-danger pl-2">
                    <?php echo $dateOfBirth_error ?>
                </span>
            </div>

            <div class="form__group help-block">
                <label style="font-size:14px;color:#808080;" class="mr-4 ml-3">Gender:</label>
                <input type="radio" name="gender" value="m" id="genderM" class="ml-3 mr-2" style="font-size:larger;" checked/>
                <label for="genderM" style="font-size:14px;color:#808080;">Male</label>
                <input type="radio" name="gender" value="f" id="genderF" class="ml-3 mr-2" style="font-size:larger;" />
                <label for="genderF" style="font-size:14px;color:#808080;">Female</label>
                <input type="radio" name="gender" value="o" id="genderO" class="ml-3 mr-2" style="font-size:larger;" />
                <label for="genderO" style="font-size:14px;color:#808080;">Other</label>
            </div>

            <div class="form__group help-block">
                <input type="checkbox" name="isDriver" value="true" id="isDriver" class="ml-3 mr-2" style="font-size:larger;"/>
                <label for="isDriver" style="font-size:14px;color:#808080;">Register as Driver</label>
            </div>
            <button class="btn" type="submit">Register</button>
            <div class="row text__login justify-content-center mt-3">
                <a href="login.php" class="text__login">Already have an account? Log In</a>
            </div>
        </form>
    </div>
</body>
</html>


