<?php
session_start();
if(isset($_SESSION["Role"]) && $_SESSION["Role"] == "Driver"){
    require_once "configuration.php";
    $Id = $_SESSION["Id"];
}
?>

<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet' />
    <script type="text/javascript">
        function searchText(str, id) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("results").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "filterRequests.php?name=" + str + "&driverId=" + id, true);
            xmlhttp.send();
        }
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("results").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "filterRequests.php?name=" + "", true);
        xmlhttp.send();
    </script>
    <style>
        body {
            font-family: 'lato', sans-serif;
        }

        .container {
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 10px;
            padding-right: 10px;
        }

        h2 {
            font-size: 26px;
            margin: 20px 0;
            text-align: center;
        }

        .responsive-table li {
            border-radius: 3px;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .responsive-table .table-header {
            background-color: #95A5A6;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .responsive-table .table-row {
            background-color: #ffffff;
            box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.1);
        }

        .responsive-table .col-1 {
            flex-basis: 10%;
        }

        .responsive-table .col-2 {
            flex-basis: 40%;
        }

        .responsive-table .col-3 {
            flex-basis: 25%;
        }

        .responsive-table .col-4 {
            flex-basis: 25%;
        }

        @media all and (max-width: 767px) {
            .table-header {
                display: none;
            }

            .table-row {
            }

            li {
                display: block;
            }

            .col {
                flex-basis: 100%;
                display: flex;
                padding: 10px 0;
            }

                .col::before {
                    color: #6C7A89;
                    padding-right: 10px;
                    content: attr(data-label);
                    flex-basis: 50%;
                    text-align: right;
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
                padding: 4em;
                background-size: 200%;
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
    </style>

</head>
<body>
    <ul class="navigation">
        <li class="item">Administration</li>
        <li class="nav-item">
            <a href="#">Home</a>
        </li>
        <li class="nav-item">
            <?php echo '<a href="userProfile.php?id='.$Id.'">Profile</a>';?>
        </li>
        <li class="nav-item">
            <a href="#">Drivers</a>
        </li>
        <li class="nav-item">
            <a href="#">Supervisors</a>
        </li>
        <li class="nav-item">
            <a href="#">Managers</a>
        </li>
        <li class="nav-item">
            <a href="#">New requests</a>
        </li>
        <li class="nav-item">
            <?php echo '<a href="logout.php">Sign Out</a>';?>
        </li>
    </ul>

    <input type="checkbox" id="nav-trigger" class="nav-trigger" />
    <!--<label for="nav-trigger"></label>-->

    <div class="site-wrap">
        <?php
        $page = $_GET['page']; // To get the page

        if($page == null) {
            $page = 'driverIndex'; // Set page to index, if not set
        }
        switch ($page) {

            case "driverIndex":
                include('driverIndex.php');
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