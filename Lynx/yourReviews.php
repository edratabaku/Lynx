<?php
session_start();
$id = $_SESSION["Id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Record</title>
    <link rel="stylesheet" href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <style>
        body {
            background: #333333;
            margin-top: 20px;
            font-family: "system-ui" !important;
            font-weight: 600;
        }

        .card-box {
            padding: 20px;
            border-radius: 21px;
            margin-bottom: 30px;
            background-image: linear-gradient( 45deg, #383838, #030303);
            color: white;
            font-weight: 600;
            box-shadow: 3px 0px 11px 2px #272727;
        }

            .card-box:hover {
                box-shadow: 3px 0px 11px 2px #8a7129;
            }

        .social-links li a {
            border-radius: 50%;
            color: rgba(121, 121, 121, .8);
            display: inline-block;
            height: 30px;
            line-height: 27px;
            border: 2px solid rgba(121, 121, 121, .5);
            text-align: center;
            width: 30px;
        }

            .social-links li a:hover {
                color: #797979;
                border: 2px solid #797979;
            }

        .thumb-lg {
            height: 88px;
            width: 88px;
        }

        .img-thumbnail {
            padding: .25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: .25rem;
            max-width: 100%;
            height: auto;
        }

        .text-pink {
            color: #ff679b !important;
        }

        .btn-rounded {
            border-radius: 2em;
        }

        .text-muted {
            color: #c0c8cc !important;
        }

        h4 {
            line-height: 22px;
            font-size: 18px;
        }

        .btn-golden {
            font-family: "system-ui";
            color: black;
            background: #FFD700;
            font-weight: 600;
        }

        .btn:hover {
            opacity: 0.7;
        }

        .btn-dark {
            background: #343a40;
            font-weight: 600;
        }

        .btn-info {
            color: #fff;
            background-color: transparent;
            border-color: #ff9933;
            margin-right: 12px;
        }

            .btn-info:hover {
                background-color: #996900;
                border-color: #f8f9fa;
            }
        .navbar {
            background-color: rgba(0,0,0,0.1);
        }

        .navigation {
            /* critical sizing and position styles */
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 0;
            /* non-critical appearance styles */
            list-style: none;
            background: #111;
        }

        .item {
            /* non-critical appearance styles */
            width: 200px;
            border-top: 1px solid #111;
            border-bottom: 1px solid #000;
            display: block;
            padding: 1em;
            background: linear-gradient(135deg, rgba(0,0,0,0) 0%,rgba(0,0,0,0.65) 100%);
            color: white;
            font-size: 1.2em;
            text-decoration: none;
            transition: color 0.2s, background 0.5s;
        }
        /* Navigation Menu - List items */
        .nav-item {
            /* non-critical appearance styles */
            width: 200px;
            border-top: 1px solid #111;
            border-bottom: 1px solid #000;
        }

            .nav-item a {
                /* non-critical appearance styles */
                display: block;
                padding: 1em;
                background: linear-gradient(135deg, rgba(0,0,0,0) 0%,rgba(0,0,0,0.65) 100%);
                color: white;
                font-size: 1.2em;
                text-decoration: none;
                transition: color 0.2s, background 0.5s;
            }

                .nav-item a:hover {
                    color: #FFD700;
                    background: linear-gradient(135deg, rgba(0,0,0,0) 0%,rgba(209, 176, 0,0.65) 100%);
                }

        /* Site Wrapper - Everything that isn't navigation */
        .site-wrap {
            /* Critical position and size styles */
            min-height: 100%;
            min-width: 100%;
            background-color: #111; /* Needs a background or else the nav will show through */
            position: relative;
            top: 0;
            bottom: 100%;
            left: 0;
            z-index: 1;
            /* non-critical apperance styles */
            padding: 4em 1rem 5rem 4rem;
            background-size: 200%;
            color: #ffffffb3;
            overflow:auto;
        }

        /* Nav Trigger */
        .nav-trigger {
            position: absolute;
            clip: rect(0, 0, 0, 0);
        }

        label[for="nav-trigger"] {
            /* critical positioning styles */
            position: fixed;
            left: 15px;
            top: 15px;
            z-index: 2;
            /* non-critical apperance styles */
            height: 30px;
            width: 30px;
            cursor: pointer;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' x='0px' y='0px' width='30px' height='30px' viewBox='0 0 30 30' enable-background='new 0 0 30 30' xml:space='preserve'><rect width='30' height='6'/><rect y='24' width='30' height='6'/><rect y='12' width='30' height='6'/></svg>");
            background-size: contain;
        }

        /* Make the Magic Happen */
        /*.nav-trigger + label, .site-wrap {
        transition: left 0.2s;
    }*/

        .nav-trigger:not(:checked) + label {
            left: 215px;
        }

        .nav-trigger:not(:checked) ~ .site-wrap {
            left: 200px;
            box-shadow: 0 0 5px 5px rgba(0,0,0,0.5);
        }

        /* Additional non-critical styles */
        h1, h3, p {
            max-width: 600px;
            margin: 0 auto 1em;
        }
        /* Micro reset */
        *, *:before, *:after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        #profilePic {
            width: 200px;
            height: auto;
            border-radius: 50%;
            box-shadow: -9px 7px 16px #2f2006;
        }

        #watercolor {
            z-index: -2;
            position: absolute;
            left: -33%;
            width: 25vw;
            top: -24%;
        }


        #returnHome {
            color: rgb(146 199 255);
        }

        #title:hover {
            color: #caa245;
        }

        #blackCircle {
            z-index: -10;
            position: absolute;
            top: 0%;
            width:39%;
            animation: mymove 10s infinite;
        }

        @keyframes mymove {
            0% {
                left: 3%;
            }

            50% {
                left: 5%;
            }

            100% {
                left: 3%;
            }
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        * {
            outline: none;
        }

        .table th,
        .table thead th {
            font-weight: 500;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table th {
            padding: 1rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        th {
            text-align: inherit;
        }

        .m-b-20 {
            margin-bottom: 20px;
        }
        .table-light tbody + tbody, .table-light td, .table-light th, .table-light thead th {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        .rating-css {
            width: 300px;
        }

            .rating-css div {
                color: #ffe400;
                font-size: 30px;
                font-family: sans-serif;
                font-weight: 800;
                text-align: center;
                text-transform: uppercase;
            }

            .rating-css input {
                display: none;
            }

                .rating-css input + label {
                   
                  
                }

                .rating-css input:checked + label ~ label {
                    color: #838383;
                }

            .rating-css label:active {
                transform: scale(0.8);
                transition: 0.3s ease;
            }
<?php include 'CSS/authentication.css'; ?>
    </style>
</head>
<body>
    <ul class="navigation">
        <li class="item">
            <img src="Images/logo2.png" height="150" />
        </li>
        <li class="nav-item">
            <a href="userLayout.php?page=index">Home</a>
        </li>
        <li class="nav-item">
            <?php echo '<a href="userProfile.php?id='.$id.'">Profile</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="pastServices.php?id='.$id.'&role=User">Past Services</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="awaitingServices.php?id='.$id.'&role=User">Awaiting Services</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="yourReviews.php?id='.$id.'">Your reviews</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="yourComplaints.php?id='.$id.'">Your complaints</a>';?>
        </li>
        <li class="nav-item">
            <?php echo '<a href="logout.php">Sign Out</a>';?>
        </li>
        <img src="Images/circle_PNG62.png" id="blackCircle" />>

    </ul>

    <input type="checkbox" id="nav-trigger" class="nav-trigger" />
    <!--<label for="nav-trigger"></label>-->

    <div class="site-wrap">
        <?php
 require_once "configuration.php";
require_once "Review.php";
$name = $_REQUEST["name"];
$id = $_GET["id"];
$reviews = array();
$query="";
if($name == null){
    $query = "select r.Id,
                    r.DriverId as DriverId,
                    du.FirstNAme as DriverFirstName,
                    du.LastNAme as DriverLastName,
                    r.CustomerId as CustomerId,
                    r.Rating as Rating,
                    r.Text as Text
                    from lynx.Reviews as r
                    inner join lynx.Drivers as d on r.DriverId = d.Id
                    inner join lynx.Users as du on d.UserId = du.Id
                    where CustomerID = '$id' and r.IsActive = 1 ";
    if($stmt = mysqli_prepare($mysqli, $query)){
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result)>0){
                foreach ($result as $r){
                    $review = new Review();
                    $review->set_id($r["Id"]);
                    $review->set_driverId($r["DriverId"]);
                    $review->set_customerId($r["CustomerId"]);
                    $driverName = $r["DriverFirstName"]." ".$r["DriverLastName"];
                    $review->set_driverName($driverName);
                    $review->set_rating($r["Rating"]);
                    $review->set_text($r["Text"]);
                    array_push($reviews,$review);
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
}
else {
    $query = "select r.Id,
                    r.DriverId as DriverId,
                    du.FirstNAme as DriverFirstName,
                    du.LastNAme as DriverLastName,
                    r.CustomerId as CustomerId,
                    r.Rating as Rating,
                    r.Text as Text
                    from lynx.Reviews as r
                    inner join lynx.Drivers as d on r.DriverId = d.Id
                    inner join lynx.Users as du on d.UserId = du.Id
                    where CustomerID = '$id' and r.IsActive = 1 and (DriverFirstName='$name' OR DriverLastName='$name'";
    if($stmt = mysqli_prepare($mysqli, $query)){
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result)>0){
                foreach ($result as $r){
                    $review = new Review();
                    $review->set_id($r["Id"]);
                    $review->set_customerId($r["customerId"]);
                    $review->set_driverName($r["DriverFirstName"]." ".$r["DriverLastNAme"]);
                    $review->set_rating($r["Rating"]);
                    $review->set_text($r["Text"]);
                    array_push($review,$reviews);
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
}
echo "<div class='content'>";
echo "<div class='container'>";
for($counter = 0; $counter<count($reviews);$counter++){
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
     <div class='rating-css'>
    <div class='star-icon'>";
    for($count = 0; $count<$reviews[$counter]->get_rating();$count++){
        echo"<input type='radio' name='rating1' id='rating1'>
      <label for='rating1' class='fa fa-star'></label>";
    }
     echo"
    </div>
  </div>";
    echo"
            </div>
        <div>
        <h4>".$reviews[$counter]->get_driverName()."</h4>
    </div>

    <div class='mt-4'>
        <div class='row'>
            <div class='col-md-12'>".$reviews[$counter]->get_text()."
</div>
        </div>
   </div>
</div>

<div>







</div>



</div>
</div>";
    if ($counter == count($reviews)-1 && $counter%3!=0){
        echo "</div>";
    }
}
echo "</div>
</div>
";
mysqli_stmt_close($mysqli);
        ?>
        </div>
</body> 
</html>