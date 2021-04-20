<!DOCTYPE html>
<html>
<head>
    <title>Administration Panel</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500" />
</head>
<body>
    <ul class="navigation" >
        <li class="item">Administration</li>
        <li class="nav-item">
            <a href="#">Home</a>
        </li>
        <li class="nav-item">
            <a href="adminlayout.php/?page=roles">Roles</a>
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
    </ul>

    <input type="checkbox" id="nav-trigger" class="nav-trigger" />
    <!--<label for="nav-trigger"></label>-->

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

<style>
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
        background-color: white; /* Needs a background or else the nav will show through */
        position: relative;
        top: 0;
        bottom: 100%;
        left: 0;
        z-index: 1;
        /* non-critical apperance styles */
        padding: 4em;
        background-image: linear-gradient(135deg, rgb(254,255,255) 0%,rgb(221,241,249) 35%,rgb(160,216,239) 100%);
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
    } .nav-trigger:not(:checked) ~ .site-wrap {
        left: 200px;
        box-shadow: 0 0 5px 5px rgba(0,0,0,0.5);
    } body {
        /* Without this, the body has excess horizontal scroll when the menu is open */
        overflow-x: hidden;
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
    } html, body {
        height: 100%;
        width: 100%;
        font-family: Helvetica, Arial, sans-serif;
    }
</style>

<script></script>