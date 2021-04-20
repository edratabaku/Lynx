<?php

?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="#">
                    Home
                    <span class="sr-only">(current)</span>
                </a>
                <a class="nav-item nav-link" href="#">Features</a>
                <a class="nav-item nav-link" href="#">Pricing</a>
                <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </div>
        </div>
    </nav>
    <div class="site-wrap">
        <?php
        $page = $_GET['page']; // To get the page

        if($page == null) {
            $page = 'index'; // Set page to index, if not set
        }
        switch ($page) {

            case "roles":
                include('roles.php');
                break;

            case "about":
                include('about.php');
                break;

            case "contact":
                include('contact.php');
                break;
        }

        ?>
    </div>
</body>
</html>