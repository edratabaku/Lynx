<?php
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

mysqli_close($mysqli);
?>