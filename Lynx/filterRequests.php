<?php
require_once "configuration.php";
require_once "Request.php";
$name = $_REQUEST["name"];
$id = $_SESSION["Id"];
$requests = array();
$query="SELECT u.FirstName, u.LastName, u.Id";
if($name == null){
    $query = "SELECT u.FirstName, u.LastName, r.RequestedById, r.DriverId, r.TimeOfRequest, r.IsAccepted FROM requests AS r
                INNER JOIN Users as u on u.Id = r.RequestedById
                INNER JOIN Drivers as d on d.Id = r.DriverId
                WHERE u.IsActive = 1 AND d.UserId = ? AND r.IsSeen = 0";
    if($stmt = mysqli_prepare($mysqli, $query)){
        mysqli_stmt_bind_param($stmt,"s",$id);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result)>0){
                foreach ($result as $r){
                    $request = new Request();
                    $request->set_driverId($id);
                    $request->set_firstName($r["FirstName"]);
                    $request->set_lastName($r["LastName"]);
                    $request->set_id($r["Id"]);
                    $request->set_isAccepted($r["IsAccepted"]);
                    $request->set_isSeen($r["IsSeen"]);
                    $request->set_timeOfRequest($r["TimeOfRequest"]);
                    array_push($requests,$request);
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
}
else {
    $query = "SELECT u.FirstName, u.LastName, r.RequestedById, r.DriverId, r.TimeOfRequest, r.IsAccepted FROM requests AS r
                INNER JOIN Users as u on u.Id == r.RequestedById
                INNER JOIN Drivers as d on d.Id = r.DriverId
                WHERE u.IsActive = 1 AND d.UserId = ? AND r.IsSeen = 0 AND (u.FirstName like ? or u.LastName like ?)";
    if($stmt = mysqli_prepare($mysqli, $query)){
        mysqli_stmt_bind_param($stmt,"sss",$id,$name,$name);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result)>0){
                foreach ($result as $r){
                    $request = new Request();
                    $request->set_driverId($id);
                    $request->set_firstName($r["FirstName"]);
                    $request->set_lastName($r["LastName"]);
                    $request->set_id($r["Id"]);
                    $request->set_isAccepted($r["IsAccepted"]);
                    $request->set_isSeen($r["IsSeen"]);
                    $request->set_timeOfRequest($r["TimeOfRequest"]);
                    array_push($requests,$request);
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_stmt_close($mysqli);
echo"<div class='container'>
<ul class='responsive-table'>
<li class='table-header'>
<div class='col col-1'>No.</div>
<div class='col col-2'>Customer Name</div>
<div class='col col-3'>Time of Request</div>
<div class='col col-4'>Actions</div>
</li>";
$counter=1;
for($i=0;$i<count($requests);$i++){
 echo"<li class='table-row'><div class='col col-1' data-label='No.'>".$counter."</div>
<div class='col col-2' data-label='Customer Name'>".$requests[$i]->get_firstName()." ".$requests[$i]->get_lastName()."</div>
<div class='col col-3' data-label='Time of Request'>".$requests[$i]->get_timeOfRequest()."</div>
<div class='col col-4' data-label='Actions'></div>
</li>
</ul>"
;
}
