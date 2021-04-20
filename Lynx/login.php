<?php
include("layout.php");
session_start();
// Nese perdoruesi ka dhene kredencialet drejtoje te home
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
    if(isset($_SESSION["Role"]) && $_SESSION["Role"]=="User"){
        header("location: index.php");
        exit;
    }
    else if(isset($_SESSION["Role"]) && $_SESSION["Role"]=="Driver"){
        header("location: driverIndex.php");
        exit;
    }
    else if(isset($_SESSION["Role"]) && $_SESSION["Role"]=="Supervisor"){
        header("location: supervisorIndex.php");
        exit;
    }
    else if(isset($_SESSION["Role"]) && $_SESSION["Role"]=="Manager"){
        header("location: managerIndex.php");
        exit;
    }
    else if(isset($_SESSION["Role"]) && $_SESSION["Role"]=="Administrator"){
        header("location: adminIndex.php");
        exit;
    }
}
require_once "configuration.php";
$username = $password = "";
$username_error = $password_error = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_error = "You must enter a username.";
    } else{
        $username = trim($_POST["username"]);
    }
    if(empty(trim($_POST["password"]))){
        $password_error = "You must enter a password.";
    } else{
        $password = trim($_POST["password"]);
    }
    if(empty($username_error) && empty($password_error)){
        $sql = "SELECT Id, Email, Username, Password, IsActive, RoleId, FirstName, LastName FROM Users WHERE Email = ? OR Username=? OR PhoneNumber=?";
        if($stmt = mysqli_prepare($mysqli,$sql)){
            $param_username = $username;
            mysqli_stmt_bind_param($stmt,"sss", $param_username, $param_username, $param_username);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                // If there exists and account with the specified email address, validate password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt,$id, $email, $username, $hash_password, $isActive, $roleId, $firstName, $lastName);
                    if(mysqli_stmt_fetch($stmt)){
                        $password = hash("sha512",$password);
                        if(password_verify($password, $hash_password)){
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["Id"] = $id;
                            $_SESSION["Email"] = $email;
                            $_SESSION["Username"] = $username;
                            $_SESSION["RoleId"] = $roleId;
                            $_SESSION["FirstName"] = $firstName;
                            $_SESSION["LastName"] = $lastName;
                            $otherSql = "SELECT * FROM Roles WHERE Id=?";
                            if($stmt2 = mysqli_prepare($mysqli,$otherSql)){
                                $param_id = $roleId;
                                mysqli_stmt_bind_param($stmt2,"s",$param_id);
                                if(mysqli_stmt_execute($stmt2)){
                                    mysqli_stmt_store_result($stmt2);
                                    if(mysqli_stmt_num_rows($stmt2)==1){
                                        mysqli_stmt_bind_result($stmt2,$id,$name);
                                        if(mysqli_stmt_fetch($stmt2)){
                                            if($name == 'Administrator'){
                                                $_SESSION["Role"] = "Administrator";
                                                header("location: adminIndex.php");
                                            }
                                            else if($name == "Manager"){
                                                $_SESSION["Role"] = "Manager";
                                                header("location: managerIndex.php");
                                            }
                                            else if($name == "Supervisor"){
                                                $_SESSION["Role"] = "Supervisor";
                                                header("location: supervisorIndex.php");
                                            }
                                            else if($name == "Driver"){
                                                $_SESSION["Role"] = "Driver";
                                                header("location: driverIndex.php");
                                            }
                                            else{
                                                $_SESSION["Role"] = "User";
                                                header("location: userLayout.php?page=index");
                                            }
                                        }
                                    }
                                }
                            }

                        }
                        else{ $password_error = "Password is incorrect.";}
                    }
                } else{ $email_error = "No existing account.";}
            } else{ echo "Error. Please try again.";}
            // Close statement
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($stmt2);
        }
    }
    // Close connection
    mysqli_close($mysqli);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <style type="text/css">
<?php include 'CSS/authentication.css'; ?>
    </style>
</head>
<body>
    <section class="back"></section>
    <div class="user">
        <header class="user__header">
            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3219/logo.svg" alt="" />
            <h1 class="user__title">Sign up to access the app.</h1>
        </header>

        <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
            <div class="form__group" <?php echo(!empty($email_error))? 'has-error' : '' ?>>
                <input type="text" placeholder="Username" class="form__input" name="username" id="username" />
                <span class="help-block text-danger">
                    <?php echo $username_error ?>
                </span>
            </div>

            <div class="form__group" <?php echo(!empty($password_error))? 'has-error': ''?>>
                <input type="password" placeholder="Password" class="form__input" name="password" id="password" />
                <span class="help-block text-danger">
                    <?php echo $password_error ?>
                </span>
            </div>

            <button class="btn" type="submit">Login</button>
            <div class="row text__login justify-content-center mt-3">
                <a href="register.php" class="text__login">Register new account.</a>
            </div>
        </form>
    </div>
</body>
</html>