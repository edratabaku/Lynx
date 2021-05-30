<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $param_driver = $_SESSION["reviewDriverId"];
    $param_user = $_SESSION["reviewUserId"];
    if(empty(trim($_POST["rating"]))){
        $rating_error = "You must give a rating.";
    }
    else{
        $rating = trim($_POST["rating"]);
    }
    $text = trim($_POST["text"]);
    if(empty($rating_error)){
        $param_id = uniqid();
        $result = mysqli_query($mysqli,"INSERT INTO Reviews(Id, CustomerId, DriverId, Rating, Text, IsActive) values ('$param_id','$param_user','$param_driver',$rating,'$text',1);");
        if($result){
            header("location: yourReviews.php?id=".$param_user);
        }
        else{
            header("location: error.php");
        }
    }
}

mysqli_close($mysqli);
?>