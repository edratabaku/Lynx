<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $param_writer = $_SESSION["complaintWriterId"];
    $param_subject = $_SESSION["complaintSubjectId"];
    if(empty($param_writer) || empty($param_subject)){
        $text_error = "Some error occurred. Try again later";
    }
    if(empty(trim($_POST["text"]))){
        $text_error = "You must write text for your complaint.";
    }
    else{
        $text = trim($_POST["text"]);
    }
    if(empty($text_error)){
        $param_id = uniqid();
        $result = mysqli_query($mysqli,"INSERT INTO Complaints(Id, WriterId, SubjectId, Text, IsActive) VALUES ('$param_id','$param_writer','$param_subject','$param_text',1);");
        if($result){
            header("location: yourComplaints.php?id=".$param_writer);
        }
        else{
            header("location: error.php");
        }
    }
}

mysqli_close($mysqli);
?>