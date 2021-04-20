<?php
include("layout.php");
//add the configuration file
require_once "configuration.php";
//initializing variables
$licenseId="";
$licenseDate="";
$licenseExpireDate="";
$car="";
$operatingArea="";

$licenseId_error=$licenseDate_error=$licenseExpireDate_error=$car_error=$operatingArea_error="";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["licenseId"]))){
        $licenseId_error = "You must enter your license id";
    }
    else{
        $licenseId = trim($_POST["licenseId"]);
    }
    if(empty(trim($_POST["licenseDate"]))){
        $licenseDate_error = "You must enter your license release date.";
    }
    else{
        $licenseDate = trim($_POST["licenseDate"]);
        $date = explode("-", $licenseDate);
        if($date[0]>2010){
            $licenseDate_error = "Your license has expired.";
        }
    }
    if(empty(trim($_POST["licenseExpireDate"]))){
        $licenseExpireDate_error = "You must enter your license expiration date.";
    }
    else{
        $licenseExpireDate = trim($_POST["licenseExpireDate"]);
        if(!empty(trim($_POST["licenseDate"]))){
            $expDate = explode("-",$licenseExpireDate);
            if($expDate[0]>=($date[0]+9)){
                $licenseExpireDate_error = "Your license release and expiration date must be at least 10 years apart.";
            }
        }
    }
    if(empty(trim($_POST["operatingArea"]))){
        $operatingArea_error = $_POST["operatingArea"];
    }
    else{
        $operatingArea = $_POST["operatingArea"];
    }
    if(empty(trim($_POST["car"]))){
        $car_error = $_POST["car"];
    }
    else{
        $car = $_POST["car"];
    }
    if(empty($licenseId_error) && empty($licenseDate_error) && empty($licenseExpireDate_error) && empty($operatingArea_error)&&empty($car_error)){
        $sql = "INSERT INTO Drivers(Id,UserId,LicenseId,LicenseDate,LicenseExpireDate,OperatingArea,Car,IsApproved,IsBusy,IsBanned) VALUES (?,?,?,?,?,?,?,?,?,?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            $param_licenseId = $licenseId;
            $param_licenseDate = $licenseDate;
            $param_licenseExpireDate = $licenseExpireDate;
            $param_operatingArea = $operatingArea;
            $param_car = $car;
            $param_isApproved = false;
            $param_isBusy = false;
            $param_isBanned = false;
            $param_id = uniqid();
            $param_userId = $_SESSION["id"];
            mysqli_stmt_bind_param($stmt,"ssssssssss", $param_id,$param_userId,$param_licenseId,$param_licenseDate,$param_licenseExpireDate,$param_operatingArea,$param_car,$param_isApproved,$param_isBusy,$param_isBanned);
            if(mysqli_stmt_execute($stmt)){
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["Id"] = $param_id;
                header("location: driverIndex.php");
            }
            else{
                echo "Ndodhi nje gabim. Provoni perseri.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($mysqli);

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Regjistrohu</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <style type="text/css">
<?php include 'CSS/authentication.css'; ?>
    </style>
</head>
<body>
    <div class="user">
        <header class="user__header">
            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3219/logo.svg" alt="" />
            <h1 class="user__title">Sign up to access the app.</h1>
        </header>
        <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form__group">
                        <input type="text" placeholder="License Id" class="form__input" name="licenseId" id="licenseId" value="<?php echo isset($_POST['licenseId']) ? htmlspecialchars($_POST['licenseId'], ENT_QUOTES) : ''; ?>" />
                        <span class="help-block text-danger pl-2">
                            <?php echo $licenseId_error ?>
                        </span>
                    </div>
                <div class="form__group" <?php echo(!empty($licenseDate_error))? 'has-error' : '' ?>>
                    <input type="date" placeholder="License Release Date" class="form__input" name="licenseDate" id="licenseDate" value="<?php echo isset($_POST['licenseDate']) ? htmlspecialchars($_POST['licenseDate'], ENT_QUOTES) : ''; ?>" />
                    <span class="help-block text-danger pl-2">
                        <?php echo $licenseDate_error ?>
                    </span>
                </div>
                <div class="form__group" <?php echo(!empty($licenseExpireDate_error))? 'has-error' : '' ?>>
                    <input type="date" placeholder="License Expiration Date" class="form__input" name="licenseExpireDate" id="licenseExpireDate" value="<?php echo isset($_POST['licenseExpireDate']) ? htmlspecialchars($_POST['licenseExpireDate'], ENT_QUOTES) : ''; ?>" />
                    <span class="help-block text-danger pl-2">
                        <?php echo $licenseExpireDate_error ?>
                    </span>
                </div>
                    <div class="form__group" <?php echo(!empty($operatingArea_error))? 'has-error' : '' ?>>
                        <input type="text" placeholder="City" class="form__input" name="operatingArea" id="operatingArea" value="<?php echo isset($_POST['operatingArea']) ? htmlspecialchars($_POST['operatingArea'], ENT_QUOTES) : ''; ?>" />
                        <span class="help-block text-danger pl-2">
                            <?php echo $operatingArea_error ?>
                        </span>
                    </div>
                    <div class="form__group" <?php echo(!empty($car_error))? 'has-error' : '' ?>>
                        <textarea rows="4" cols="20" placeholder="Car Short Description" class="form__input" name="car" id="car" value="<?php echo isset($_POST['car']) ? htmlspecialchars($_POST['car'], ENT_QUOTES) : ''; ?>" ></textarea>
                        <span class="help-block text-danger pl-2">
                            <?php echo $car_error ?>
                        </span>
                    </div>
            <button class="btn" type="submit">Register specifications</button>
            <div class="row text__login justify-content-center mt-3">
                <a href="register.php" class="text__login">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>