<?php
session_start();
require_once "configuration.php";
$oldPassword_error=$newPassword_error=$confirmNewPassword_error="";
if(isset($_GET["id"])&& !empty(trim($_GET["id"]))){
    $param_id = trim($_GET["id"]);
    $result = mysqli_query($mysqli,"SELECT * FROM USERS WHERE Id='$param_id'");
    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $param_oldPassword = $row["Password"];
    }
}
else{
    $param_id = $_SESSION["Id"];
    $result = mysqli_query($mysqli,"SELECT * FROM USERS WHERE Id='$param_id'");
    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $param_oldPassword = $row["Password"];
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
        //$readOldData = "SELECT Password FROM Users Where Id = ?";
        //if($stmt = mysqli_prepare($mysqli,$readOldData)){
        //    mysqli_stmt_bind_param($stmt,"s",$param_id);
        //    if(mysqli_stmt_execute($stmt)){
        //        if(mysqli_stmt_num_rows($stmt)==1){
        //             mysqli_stmt_bind_result($stmt,$hash_password);
        //             if(mysqli_stmt_fetch($stmt)){
        //                 $oldPassword = hash("sha512",$oldPassword);
        //                 if(password_verify($oldPassword,$hash_password)){
        //                     $param_password = hash('sha512', $_POST["newPassword"]);
        //                     $param_password = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);
        //                     $updatePassword = "UPDATE users SET Password = '$param_password' WHERE Id = '$param_id'";
        //                     if (mysqli_query($mysqli, $updatePassword)) {
        //                         $updateConfirmPassword = "UPDATE users SET ConfirmPassword = '$param_password' WHERE Id = '$param_id'";
        //                         if(mysqli_query($mysqli,$updateConfirmPassword)){
        //                             //modal
        //                         }
        //                         else{
        //                             header("location: error.php");
        //                         }
        //                     }
        //                     else{
        //                         header("location: error.php");
        //                     }
        //                 }
        //                 else{
        //                     $oldPassword_error="This password does not match your current password.";
        //                 }
        //             }
        //        }
        //    }
        //}
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($mysqli); ?>


<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
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
            <h1 class="user__title">Change password</h1>
        </header>
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

    </div>
</body>
</html>