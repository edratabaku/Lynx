<?php
require_once "configuration.php";
require_once "Driver.php";
require_once "Comment.php";
require_once "Complaint.php";
$name = $_REQUEST["name"];

$drivers = array();
$query="";
if($name == null){
    $query = " SELECT d.Id as Id,
                    Username,
                    Email,
                    PhoneNumber,
                    FirstName,
                    LastName,
                    d.IsActive,
                    d.IsBanned,
                    DateOfBirth,
                    Gender,
                    UserId,
                    LicenseId,
                    LicenseDate,
                    LicenseExpireDate,
                    OperatingArea,
                    Car,
                    ParentId,
                    IsApproved,
                    IsBusy FROM lynx.Drivers as d
              inner join Users as u on u.Id = d.userId
              where d.IsBusy=0 and d.IsBanned=0 and d.IsApproved=1 and u.IsActive=1 and d.IsActive=1";
    if($stmt = mysqli_prepare($mysqli, $query)){
        if(mysqli_stmt_execute($stmt)){
             $result = mysqli_stmt_get_result($stmt);
             if(mysqli_num_rows($result)>0){
                 foreach ($result as $r){
                     $driver = new Driver();
                     $driver = new Driver();
                     $driver->set_firstName($r["FirstName"]);
                     $driver->set_lastName($r["LastName"]);
                     $driver->set_id($r["Id"]);
                     $driver->set_userId($r["UserId"]);
                     $driver->set_supervisorId($r["ParentId"]);
                     $driver->set_licenseId($r["LicenseId"]);
                     $driver->set_licenseDate($r["LicenseDate"]);
                     $driver->set_licenseExpireDate($r["LicenseExpireDate"]);
                     $driver->set_car($r["Car"]);
                     $driver->set_gender($r["Gender"]);
                     $driver->set_area($r["OperatingArea"]);
                     $driver->set_username($r["Username"]);
                     $driver->set_email($r["Email"]);
                     $driver->set_phoneNumber($r["PhoneNumber"]);
                     $driver->set_age($r["DateOfBirth"]);
                     $subQuery = "SELECT COUNT(Id) as Count from Rides where DriverId=?";
                     if($substmt=mysqli_prepare($mysqli,$subQuery)){
                         mysqli_stmt_bind_param($substmt,"s",$param_id);
                         $param_id=$r["Id"];
                         if(mysqli_stmt_execute($substmt)){
                             if(mysqli_num_rows($result) == 1){
                                 $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                 $driver->set_numberOfDrives($row["Count"]);
                             }
                         }
                     }
                     mysqli_stmt_close($substmt);
                     $subQuery = "SELECT COUNT(Id) as Count from Rides where DriverId=?";
                     if($substmt=mysqli_prepare($mysqli,$subQuery)){
                         mysqli_stmt_bind_param($substmt,"s",$param_id);
                         $param_id=$r["Id"];
                         if(mysqli_stmt_execute($substmt)){
                             if(mysqli_num_rows($result) == 1){
                                 $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                 $driver->set_numberOfDrives($row["Count"]);
                             }
                         }
                     }
                     mysqli_stmt_close($substmt);
                     $subQuery = "SELECT Id,DriverId,CustomerId,Rating,Text from lynx.Reviews where DriverId=?";
                     if($substmt=mysqli_prepare($mysqli,$subQuery)){
                         mysqli_stmt_bind_param($substmt,"s",$param_id);
                         $param_id=$r["Id"];
                         if(mysqli_stmt_execute($substmt)){
                             if(mysqli_num_rows($result) == 1){
                                 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                     $comment = new Comment();
                                     $comment->set_id($row["Id"]);
                                     $comment->set_driverId($row["driverId"]);
                                     $comment->set_customerId($row["customerId"]);
                                     $comment->set_rating($row["rating"]);
                                     $comment->set_text($row["text"]);
                                     $driver->add_comment($comment);
                                 }

                             }
                             $driver->set_numberOfComments(mysqli_num_rows($result));
                         }
                     }
                     mysqli_stmt_close($substmt);
                     $subQuery = "SELECT Id,DriverId,CustomerId,Text from lynx.Complaints where DriverId=?";
                     if($substmt=mysqli_prepare($mysqli,$subQuery)){
                         mysqli_stmt_bind_param($substmt,"s",$param_id);
                         $param_id=$r["Id"];
                         if(mysqli_stmt_execute($substmt)){
                             if(mysqli_num_rows($result) == 1){
                                 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                     $complaint = new Complaint();
                                     $complaint->set_id($row["Id"]);
                                     $complaint->set_driverId($row["driverId"]);
                                     $complaint->set_customerId($row["customerId"]);
                                     $complaint->set_text($row["text"]);
                                     $driver->add_complaint($complaint);
                                 }

                             }
                             $driver->set_numberOfComments(mysqli_num_rows($result));
                         }
                     }
                     mysqli_stmt_close($substmt);
                     array_push($drivers,$driver);
                 }
             }
        }}
    mysqli_stmt_close($stmt);
}
else {
    $query = "
              SELECT d.Id as Id,
                    Username,
                    Email,
                    PhoneNumber,
                    FirstName,
                    LastName,
                    d.IsActive,
                    d.IsBanned,
                    DateOfBirth,
                    Gender,
                    UserId,
                    LicenseId,
                    LicenseDate,
                    LicenseExpireDate,
                    OperatingArea,
                    Car,
                    u.ParentId,
                    IsApproved,
                    IsBusy FROM lynx.Drivers as d
              inner join Users as u on u.Id = d.userId
              where d.IsBusy=0 and d.IsBanned=0 and d.IsApproved=1 and d.IsActive=1 and u.IsActive=1
              and u.firstname like ? or u.lastname like ?";
    if($stmt = mysqli_prepare($mysqli, $query)){
        mysqli_stmt_bind_param($stmt,"ss", $param_name, $param_name);
        $param_name = "%".$name."%";
        if(mysqli_stmt_execute($stmt)){
             $result = mysqli_stmt_get_result($stmt);
             if(mysqli_num_rows($result)>0){
                 foreach ($result as $r){
                     $driver = new Driver();
                     $driver->set_firstName($r["FirstName"]);
                     $driver->set_lastName($r["LastName"]);
                     $driver->set_id($r["Id"]);
                     $driver->set_userId($r["UserId"]);
                     $driver->set_supervisorId($r["ParentId"]);
                     $driver->set_licenseId($r["LicenseId"]);
                     $driver->set_licenseDate($r["LicenseDate"]);
                     $driver->set_licenseExpireDate($r["LicenseExpireDate"]);
                     $driver->set_car($r["Car"]);
                     $driver->set_gender($r["Gender"]);
                     $driver->set_area($r["OperatingArea"]);
                     $driver->set_username($r["Username"]);
                     $driver->set_email($r["Email"]);
                     $driver->set_phoneNumber($r["PhoneNumber"]);
                     $driver->set_age($r["DateOfBirth"]);
                     $subQuery = "SELECT COUNT(Id) as Count from Rides where DriverId=?";
                     if($substmt=mysqli_prepare($mysqli,$subQuery)){
                         mysqli_stmt_bind_param($substmt,"s",$param_id);
                         $param_id=$r["Id"];
                         if(mysqli_stmt_execute($substmt)){
                             if(mysqli_num_rows($result) == 1){
                                 $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                 $driver->set_numberOfDrives($row["Count"]);
                             }
                         }
                     }
                     mysqli_stmt_close($substmt);
                     $subQuery = "SELECT COUNT(Id) as Count from Rides where DriverId=?";
                     if($substmt=mysqli_prepare($mysqli,$subQuery)){
                         mysqli_stmt_bind_param($substmt,"s",$param_id);
                         $param_id=$r["Id"];
                         if(mysqli_stmt_execute($substmt)){
                             if(mysqli_num_rows($result) == 1){
                                 $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                 $driver->set_numberOfDrives($row["Count"]);
                             }
                         }
                     }
                     mysqli_stmt_close($substmt);
                     $subQuery = "SELECT Id,DriverId,CustomerId,Rating,Text from lynx.Reviews where DriverId=?";
                     if($substmt=mysqli_prepare($mysqli,$subQuery)){
                         mysqli_stmt_bind_param($substmt,"s",$param_id);
                         $param_id=$r["Id"];
                         if(mysqli_stmt_execute($substmt)){
                             if(mysqli_num_rows($result) == 1){
                                 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                     $comment = new Comment();
                                     $comment->set_id($row["Id"]);
                                     $comment->set_driverId($row["driverId"]);
                                     $comment->set_customerId($row["customerId"]);
                                     $comment->set_rating($row["rating"]);
                                     $comment->set_text($row["text"]);
                                     $driver->add_comment($comment);
                                 }

                             }
                             $driver->set_numberOfComments(mysqli_num_rows($result));
                         }
                     }
                     mysqli_stmt_close($substmt);
                     $subQuery = "SELECT Id,DriverId,CustomerId,Text from lynx.Complaints where DriverId=?";
                     if($substmt=mysqli_prepare($mysqli,$subQuery)){
                         mysqli_stmt_bind_param($substmt,"s",$param_id);
                         $param_id=$r["Id"];
                         if(mysqli_stmt_execute($substmt)){
                             if(mysqli_num_rows($result) == 1){
                                 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                     $complaint = new Complaint();
                                     $complaint->set_id($row["Id"]);
                                     $complaint->set_driverId($row["driverId"]);
                                     $complaint->set_customerId($row["customerId"]);
                                     $complaint->set_text($row["text"]);
                                     $driver->add_complaint($complaint);
                                 }

                             }
                             $driver->set_numberOfComments(mysqli_num_rows($result));
                         }
                     }
                     mysqli_stmt_close($substmt);
                     array_push($drivers,$driver);
                 }
             }
        }

    }
    mysqli_stmt_close($stmt);
}
    mysqli_stmt_close($mysqli);
 echo "<div class='content'>";
 echo "<div class='container'>";
for($counter = 0; $counter<count($drivers);$counter++){
 //echo"<div class='row'>";
if($counter==0){
    echo "<div class='row'>";
}
else if($counter%3==0){
    echo "</div>";
    echo "<div class='row'>";
}

    echo"
<div class='col-lg-4 col-xl-4 col-md-4 col-sm-12'>
    <div class='text-center card-box'>
        <div class='member-card pt-2 pb-2'>
            <div class='thumb-lg member-thumb mx-auto'>
                <img src='https://img.favpng.com/22/16/5/computer-icons-person-clip-art-png-favpng-eBETpZsN4c6NHF7i9VTxF9gCq.jpg' class='rounded-circle img-thumbnail' alt='profile-image'>
            </div>
        <div>
        <h4>".$drivers[$counter]->get_firstName()." ".$drivers[$counter]->get_lastName()."</h4>
        <p class='text-muted'>".$drivers[$counter]->get_age()."<span>| </span><span>".$drivers[$counter]->get_gender()."</span><span>| </span><span>".$drivers[$counter]->get_area()."</span></p>
    </div>
    <button type='button' class='btn btn-dark mt-3 btn-rounded waves-effect w-md waves-light'>Show details</button>
    <br/><button type='button' class='btn btn-golden mt-3 btn-rounded waves-effect w-md waves-light'>Require service</button>
    <div class='mt-4'>
        <div class='row'>
            <div class='col-4'>
                <div class='mt-3'>
                    <h4>".$drivers[$counter]->get_numberOfDrives()."</h4>
                    <p class='mb-0 text-muted'>Number of orders</p>
                </div>
            </div>
            <div class='col-4'>
                <div class='mt-3'>
                    <h4>".$drivers[$counter]->get_numberOfComments()."</h4>
                    <p class='mb-0 text-muted'>Number of orders</p>
                </div>
            </div>
            <div class='col-4'>
                <div class='mt-3'>
                    <h4>".$drivers[$counter]->get_numberOfComplaints()."</h4>
                    <p class='mb-0 text-muted'>Number of orders</p>
                </div>
            </div>
        </div>
   </div> 
</div>

<div>







</div>



</div>
</div>";
if ($counter == count($drivers)-1 && $counter%3!=0){
        echo "</div>";
    }
}
echo "</div>
</div>
";
?>