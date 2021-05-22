<?php
include("layout.php");
session_start();
//add the configuration file
require_once "configuration.php";
//initializing variables
$licenseId="";
$licenseDate="";
$licenseExpireDate="";
$car="";
$operatingArea="";
//$formType="form";
$fileError="";
$licenseId_error=$licenseDate_error=$licenseExpireDate_error=$car_error=$operatingArea_error="";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($_SESSION["formType"] == "form"){
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
        if($date[0]<2010){
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
            if($expDate[0]!=($date[0]+10)){
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
        $sql = "INSERT INTO Drivers(Id,UserId,LicenseId,LicenseDate,LicenseExpireDate,OperatingArea,Car,IsApproved,IsBusy,IsBanned, IsActive) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            $param_licenseId = $licenseId;
            $param_licenseDate = $licenseDate;
            $param_licenseExpireDate = $licenseExpireDate;
            $param_operatingArea = $operatingArea;
            $param_car = $car;
            $param_isApproved = 0;
            $param_isBusy = 0;
            $param_isBanned = 0;
            $param_isActive=1;
            $param_id = uniqid();
            $param_userId = $_SESSION["Id"];
            mysqli_stmt_bind_param($stmt,"sssssssssss", $param_id,$param_userId,$param_licenseId,$param_licenseDate,$param_licenseExpireDate,$param_operatingArea,$param_car,$param_isApproved,$param_isBusy,$param_isBanned,$param_isActive);
            if(mysqli_stmt_execute($stmt)){
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["Id"] = $param_id;
                header("location: driverIndex.php");
            }
            else{
                echo "An error occured. Please try again later.";
            }

        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($mysqli);
    }
    else{
        if (isset($_FILES['licenseImage'])) {
            // for the database
            $licenseImageName = time() . '-' . $_FILES["licenseImage"]["name"];
            // For image upload
            $target_dir = "images/";
            $target_file = $target_dir . basename($licenseImageName);
            // VALIDATION
            // validate image size. Size is calculated in Bytes
            if($_FILES['licenseImage']['size'] > 200000) {
                $licenseImageError = "Image size should not be greated than 200Kb";
            }
            // check if file exists
            if(file_exists($target_file)) {
                $licenseImageError = "File already exists";
            }
            // Upload image only if no errors
            if (empty($licenseImageError)) {
                if(move_uploaded_file($_FILES["licenseImage"]["tmp_name"], $target_file)) {
                    $sql = "INSERT INTO Drivers(Id,UserId,licensePhoto,Car,IsApproved,IsBusy,IsBanned, IsActive) VALUES (?,?,?,?,?,?,?,?)";
                    if($stmt = mysqli_prepare($mysqli, $sql)){
                        $param_isApproved = 0;
                        $param_isBusy = 0;
                        $param_isBanned = 0;
                        $param_isActive=1;
                        $param_id = uniqid();
                        $param_userId = $_SESSION["Id"];
                        $param_car = trim($_POST["car"]);
                        $param_image = $licenseImageName;
                        mysqli_stmt_bind_param($stmt,"ssssssss", $param_id,$param_userId,$param_image,$param_car,$param_isApproved,$param_isBusy,$param_isBanned,$param_isActive);
                        if(mysqli_stmt_execute($stmt)){
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["Id"] = $param_id;
                            header("location: userProfile.php?id=$param_id");
                        }
                        //$sql = "UPDATE drivert SET profileImage='$profileImageName' WHERE Id='$param_id'";
                        //if(mysqli_query($mysqli, $sql)){
                        //    //file uploaded successfully
                        //}
                        else {
                            $licenseImageError = "There was an error.";
                        }
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $licenseImageError = "There was an error uploading image.";
                }
            }
        }
        mysqli_close($mysqli);
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <style type="text/css">
<?php include 'CSS/authentication.css'; ?>        .img-placeholder {
            width: 18%;
            color: white;
            height: 81%;
            background: black;
            opacity: .7;
            border-radius: 10%;
            z-index: 2;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: none;
        }

            .img-placeholder h4 {
                margin-top: 40%;
                color: white;
            }

        .img-div:hover .img-placeholder {
            display: block;
            cursor: pointer;
        }

        input[type="checkbox"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            -webkit-tap-highlight-color: transparent;
            cursor: pointer;
        }

        .toggle {
            height: 32px;
            width: 52px;
            border-radius: 16px;
            display: inline-block;
            position: relative;
            margin: 0;
            border: 2px solid #474755;
            background: linear-gradient(180deg, #2D2F39 0%, #1F2027 100%);
            transition: all .2s ease;
        }

            .toggle::after {
                content: "";
                position: absolute;
                top: 2px;
                left: 2px;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: white;
                box-shadow: 0 1px 2px rgba(44,44,44,.2);
                transition: all .2s cubic-bezier(.5,.1,.75,1.35);
            }

            .toggle:checked {
                border-color: #FFD700;
            }

                .toggle:checked::after {
                    transform: translate(20px);
                }
    </style>
</head>
<body>
    <section class="back"></section>
    <div class="user">
        <header class="user__header">
            <img src="Images/logo2.png" height="150" />
            <h1 class="user__title">Sign in to access the app.</h1>
        </header>
        <br />
        <input type="checkbox" class="toggle" id="formType" onchange="switching()" />
        <div id="firstForm">
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
                    <textarea rows="4" cols="20" placeholder="Car Short Description" class="form__input" name="car" id="car" value="<?php echo isset($_POST['car']) ? htmlspecialchars($_POST['car'], ENT_QUOTES) : ''; ?>"></textarea>
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
        <div id="fileForm" style="display:none;">
            <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group text-center" style="position: relative;">
                    <label>License Image</label>
                    <span class="img-div">
                        <div class="text-center img-placeholder" onclick="triggerClick()">
                            <h4>Upload image</h4>
                        </div>
                        <img src="images/uploadImage.jpg" onclick="triggerClick()" id="licenseDisplay" />
                    </span>
                    <input type="file" name="licenseImage" onchange="displayImage(this)" id="licenseImage" class="form-control" style="display: none;" />

                </div>
                <div class="form__group" <?php echo(!empty($car_error))? 'has-error' : '' ?>>
                    <textarea rows="4" cols="20" placeholder="Car Short Description" class="form__input" name="car" id="car" value="<?php echo isset($_POST['car']) ? htmlspecialchars($_POST['car'], ENT_QUOTES) : ''; ?>"></textarea>
                    <span class="help-block text-danger pl-2">
                        <?php echo $car_error ?>
                    </span>
                </div>
                <button class="btn" type="submit">Register</button>
            </form>
        </div>
        </div>
</body>
</html>

<script>

    function triggerClick(e) {
        document.querySelector('#licenseImage').click();
    }
    function displayImage(e) {
        if (e.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.querySelector('#licenseDisplay').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }
    }
    function switching() {
        debugger;
        var selectedFormType = $("#formType").is(":checked");
        if (selectedFormType == true) {
            $("#fileForm").show();
            $("#firstForm").hide();
            var formType = "file";
            $.ajax({
                type: 'POST',
                url: 'changeFormType.php',
                data: 'formType=' + formType,
                success: function (data) {
                    //alert(data);
                }
            });
        }
        else {
            $("#fileForm").hide();
            $("#firstForm").show();
            var formType = "form";
            $.ajax({
                type: 'POST',
                url: 'changeFormType.php',
                data: 'formType=' + formType,
                success: function (data) {
                    //alert(data);
                }
            });
        }
    }
</script>