<?php
require_once "adminConfig.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "You must enter role name.";
    }
    else{
        $name = $input_name;
    }

    $sql = "SELECT * FROM roles where name like ?";

    if($stmt = mysqli_prepare($mysqli, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_name);

        // Set parameters
        $param_name = trim($input_name);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) >= 1){
                $name_err = "This role already exists.";
            }

        } else{
            echo "Something went wrong.Try again.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Check input errors before inserting in database
    if(empty($name_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Roles (Id, Name) VALUES (?, ?)";

        if($stmt = mysqli_prepare($mysqli, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_id, $param_name);

            // Set parameters
            $param_id = uniqid();;
            $param_name = $name;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: home.php");
                exit();
            } else{
                echo "Something went wrong. Please try again.";
            }
        }
        else{
            $name_err="Something went wrong.Try again.";
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($mysqli);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Shto liber</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 text-center">Add role</h1>
                    <form class="form" action="<?php echo htmlspecialchars($_SERVER["roles.php"]); ?>" method="post">

                        <div class="form__group" <?php echo(!empty($name_err))? 'has-error' : '' ?>>
                            <input type="text" placeholder="Name" class="form__input" name="name" id="name" />
                            <span class="help-block text-danger">
                                <?php echo $name_err ?>
                            </span>
                        </div>

                        <button class="btn" type="submit">Add</button>
                        <div class="row text__login justify-content-center mt-3">
                            <a href="register.php" class="text__login">Nvm</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>