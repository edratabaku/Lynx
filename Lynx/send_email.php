<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();
require 'C:\Users\user\vendor\autoload.php';
if(isset($_SESSION['Email']))
{
    require_once "configuration.php";

/* Exception class. */
    require 'C:\Users\user\Downloads\PHPMailer-master\PHPMailer-master\src\Exception.php';

/* The main PHPMailer class. */
    require 'C:\Users\user\Downloads\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';

/* SMTP class, needed if you want to use SMTP. */
    require 'C:\Users\user\Downloads\PHPMailer-master\PHPMailer-master\src\SMTP.php';
        $user_id = $_SESSION["Id"];
        $token = md5($_SESSION['Email']).rand(10,9999);
        mysqli_query($mysqli, "UPDATE TABLE users SET email_verification_link = '".$token."' WHERE Email =  '".$user_id."';");
        $link = "<a href='localhost/verify_email.php?key=".$_SESSION['email']."&token=".$token."'>Click and Verify Email</a>";
        //require_once('phpmail/PHPMailerAutoload.php');
        $mail = new PHPMailer();
        //$mail->CharSet =  "utf-8";
        $mail->IsSMTP();
        // enable SMTP authentication
        $mail->SMTPDebug = 1;
        $mail->SMTPAuth = true;

        $mail->SMTPSecure = "ssl";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = "465";
        $mail->IsHTML(true);

        // GMAIL username
        $mail->Username = "melmelissa242@gmail.com";
        $mail->Password = "coolandfancy1";
        $mail->From='melmelissa242@gmail.com';
        $mail->FromName='Lynx Team';
        $mail->AddAddress($_SESSION['Email']);
        $mail->Subject  =  'Verify Account';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Body    = 'Thank you for joining Lynx. You can now use our services for free and help us grow and improve. '.$link.'';

        if($mail->Send())
        {
            echo "Check Your Email box and Click on the email verification link.";
        }
        else
        {
            echo "Mail Error - >".$mail->ErrorInfo;
        }
        echo !extension_loaded('openssl')?"Not Available":"Available";
    header("location: login.php");
}
else{
    header("location:error.php");
}
?>