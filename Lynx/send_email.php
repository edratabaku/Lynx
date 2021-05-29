<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
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
        $mail->CharSet =  "utf-8";
        $mail->IsSMTP();
        // enable SMTP authentication
        $mail->SMTPAuth = true;
        // GMAIL username
        $mail->Username = "melmelissa242@gmail.com";
        // GMAIL password
        $mail->Password = "coolandfancy1";
        $mail->SMTPSecure = "ssl";
        // sets GMAIL as the SMTP server
        $mail->Host = "smtp.gmail.com";
        // set the SMTP port for the GMAIL server
        $mail->Port = "465";
        $mail->From='melmelissa242@gmail.com';
        $mail->FromName='Lynx Team';
        $mail->AddAddress($_SESSION['Email']);
        $mail->Subject  =  'Verify Account';
        $mail->IsHTML(true);
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Body    = 'Click On This Link to Verify Email '.$link.'';
        if($mail->Send())
        {
            echo "Check Your Email box and Click on the email verification link.";
        }
        else
        {
            echo "Mail Error - >".$mail->ErrorInfo;
        }
    header("location: login.php");
}
else{
    header("location:error.php");
}
?>