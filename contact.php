<?php
    session_start();
    // Question maths:
    if (!isset($_SESSION['math_answer'])) {
        // Utiliser mt_rand() au lieu de rand() pour une meilleure performance
        $_SESSION['number1'] = mt_rand(1, 10);
        $_SESSION['number2'] = mt_rand(1, 10);
        $_SESSION['math_answer'] = $_SESSION['number1'] + $_SESSION['number2'];
        session_write_close(); // Forcer la sauvegarde de la session ici
    } 
    
    // Affectation des variables locales
    $number1 = isset($_SESSION['number1']) ? $_SESSION['number1'] : 'undefined';
    $number2 = isset($_SESSION['number2']) ? $_SESSION['number2'] : 'undefined';

    // Vérifier les valeurs des variables pour le débogage 
    // echo "Debug: number1 = $number1, number2 = $number2, math_answer = ".$_SESSION['math_answer'];
    // echo 'Session save path: ' . session_save_path();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (!empty($_POST['podmielle'])) {
            die("Spam submission detected. Please try again.");
        }

        // Check if the required keys "message" and "from" are set in $_POST
        if(isset($_POST["message"]) && isset($_POST["from"])) {

            // Check réponse à la question de maths:
            if (!isset($_POST['math']) || intval($_POST['math']) !== $_SESSION['math_answer']) {
                die("Bot detected or incorrect answer. \n SessionAnswer:" . $_SESSION['math_answer'] . "\nYourAnswer:" . $_POST['math']);
            }

            // Sanitize and validate email
            $from = filter_input(INPUT_POST, 'from', FILTER_SANITIZE_EMAIL);
            if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email format.";
                exit();
            }
            else {
                // Sanitize other inputs
                $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
                $phoneNumber = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);

                // Additional optional fields
                $name = $name ? $name : "Non renseigné (optionnel)";
                $lastName = $lastName ? $lastName : "Non renseigné (optionnel)";
                $phoneNumber = $phoneNumber ? $phoneNumber : "Non renseigné (optionnel)";

                // Construct the email content
                $emailContent = "Nom du contact (optionnel): $name $lastName\n";
                // $emailContent .= "Last Name: $lastName\n";
                $emailContent .= "Numéro de téléphone (optionnel): $phoneNumber\n\n";
                $emailContent .= "Email du contact: $from\n";
                $emailContent .= "Message: \"$message\"\n";

                // Email configuration
                $to = "christine.k2r2@free.fr";
                $subject = "www.les100ciels.art : Nouveau message depuis le formulaire de contact";
                // $headers = "From: $from\r\n";
                $headers = "From: les100ciels.art\r\n";

                // Send the emails (cc)
                mail($to, $subject, $emailContent, $headers);
                mail("basile08@hotmail.fr", $subject, $emailContent, $headers);
            
                // Après avoir envoyé le mail avec succès
                unset($_SESSION['math_answer']);
                unset($_SESSION['number1']);
                unset($_SESSION['number2']);
            }
            
        } else {
            echo "Vous devez remplir les champs obligatoires pour pouvoir vous recontacter.";
        }

        // Redirect the user to a thank you page or back to the form page
        header("Location: merci.html"); // Change "thank_you.php" to your thank you page URL
        exit();
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="/favicon1.ico">

        <link rel='stylesheet' href='style.css'>
        <link rel='stylesheet' href='phone.css'>

        <link rel='stylesheet' href='styleSass.css'>
        <!-- <link rel='stylesheet' href='styleSass2k.css'> -->

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;1,100;1,200;1,300&display=swap" rel="stylesheet">

        <script src="https://kit.fontawesome.com/698848973e.js" crossorigin="anonymous"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>

        <!-- reCAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>


        <!-- FIX STARS / BODY / HTML -->
        <style>
            body {
                overflow: scroll;
            }

            .burgerOngletActif {
                background: linear-gradient(90deg, rgba(231,213,164,1) 50%, rgb(123 235 255 / 54%) 100%);
            }
            .burgerOngletActif-desktop {
                background: linear-gradient(90deg, rgba(231,213,164,1) 50%, rgb(146 239 255 / 40%) 100%);
            }

            
            .bgStars {
            height: 100%;
            overflow: hidden;
            position: absolute;
            width: 100%;
            }

            /* Mobile (déjà absulte sur pc) */
            .titlesDiv {
                position: relative;
            }
            .titlesDiv h1 {
                margin-block-end: 20px;
            }

            @media (max-width: 480px) {
                html {
                    height: auto;
                }
                body {
                    position: relative;
                    overflow: auto;
                }

                .titlesDiv h1 {
                    margin-block-end: 0px;
                }
            }
        </style>

    </head>



    <body class="bodyContact" style="overflow:auto;">



        <!-- Desktop: rectangle detection sideNav-desktop -->
        <div id="shapeDetection-navBarre-desktop" class="isDesktop"></div>

        <!-- Nav Burger DESKTOP (onHover title) -->
        <div id="mySidenav-desktop" class="sidenav-desktop sideNavContact isDesktop">

            <ul>
                <!-- <li><a href="index.html">Home</a></li> -->
                <li><a href="peinture.html">Acrylique</a></li>
                <li><a href="aquarelle.html">Aquarelle</a></li>
                <li><a href="haushka.html">Haushka</a></li>
                <li><a href="haikus.html">Ha&iuml;kus</a></li>
                <!-- <li><a href="#" class="navBurgerDisabled">Reproductions</a></li>
                <li><a href="#" class="navBurgerDisabled">Expositions</a></li> -->
                <li><a href="contact.php" class="burgerOngletActif-desktop">Contact</a></li>
            </ul>

            <!-- Letters background: (Desktop burger) -->
            <img src="contactImage-min_v3.png" class="lettersContactBg isDesktop"> 

            <div class="mentionsLegalesDiv">
                <a href="mentionsLegales.html">Mentions l&eacute;gales</a>
            </div>
        </div>




        <!-- *** Nav MenuBurger mobile -->
        <!-- NavDiv -->
        <div id="mySidenav" class="sidenav sideNavContact isMobile">

            <!-- Letters background: (Mobile burger) -->
            <img src="contactImage-min_v3.png" class="lettersContactBg isMobile"> 

            <a id="closeBtn" href="#" class="close">&times;</a>

            <a href="index.html" class="linkBurgerHomeBtn">
                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="svgBurgerHomeBtn"
                width="341.000000pt" height="288.000000pt" viewBox="0 0 341.000000 288.000000"
                preserveAspectRatio="xMidYMid meet">
                    <metadata>Created by potrace 1.14, written by Peter Selinger 2001-2017</metadata>
                    <g transform="translate(0.000000,288.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                        <path d="M2590 2395 c-138 -29 -298 -86 -473 -169 -53 -26 -109 -46 -125 -46
                        -20 0 -38 -10 -60 -34 -17 -19 -53 -47 -79 -62 -26 -15 -76 -44 -110 -66 -34
                        -21 -71 -38 -81 -38 -10 0 -32 -16 -50 -36 -25 -28 -35 -33 -49 -26 -30 16
                        -57 -1 -63 -39 -5 -31 -8 -34 -40 -34 -30 0 -37 -5 -53 -36 -14 -30 -97 -105
                        -202 -183 -8 -6 -19 -2 -33 12 -28 28 -82 29 -105 4 -22 -24 -22 -66 0 -98
                        l17 -26 -85 -82 c-407 -390 -601 -764 -477 -922 72 -91 266 -98 515 -20 165
                        52 184 60 389 161 105 52 199 95 208 95 9 0 36 18 60 40 51 47 56 48 56 15 0
                        -33 42 -75 73 -75 31 0 77 36 77 60 0 31 -20 65 -46 81 l-24 15 77 54 c81 55
                        271 201 381 291 61 50 62 50 62 24 0 -35 41 -75 78 -75 35 0 72 37 72 72 0 31
                        -42 78 -70 78 -32 0 -24 14 26 45 26 16 54 41 63 55 8 14 53 66 98 115 394
                        431 494 765 254 850 -53 18 -190 19 -281 0z m128 -25 c-8 -18 -297 -290 -309
                        -290 -8 0 53 67 134 148 82 82 155 149 163 149 8 0 13 -3 12 -7z m-203 -146
                        c-78 -81 -147 -145 -153 -143 -15 6 257 289 278 289 11 0 -35 -54 -125 -146z
                        m258 127 c-11 -10 -83 -74 -158 -142 -189 -172 -202 -162 -20 14 111 108 156
                        146 175 146 l25 0 -22 -18z m-293 -108 c-62 -65 -126 -131 -141 -147 -16 -17
                        -31 -27 -35 -23 -11 10 249 286 270 287 13 0 -15 -36 -94 -117z m358 108 c-5
                        -16 -330 -282 -339 -279 -14 5 304 286 324 287 10 1 17 -3 15 -8z m-442 -139
                        c-130 -145 -156 -170 -156 -155 0 19 229 278 252 284 13 4 24 7 26 8 2 1 -53
                        -61 -122 -137z m482 120 c8 -6 -21 -34 -95 -89 -60 -44 -138 -104 -176 -133
                        -95 -74 -96 -55 -1 22 43 35 116 95 163 135 94 78 90 76 109 65z m-553 -137
                        c-107 -126 -134 -153 -122 -122 10 26 166 226 185 237 49 28 32 -2 -63 -115z
                        m585 101 c0 -8 -6 -16 -12 -19 -7 -2 -84 -57 -172 -121 -87 -64 -163 -115
                        -168 -114 -10 4 330 267 345 268 4 0 7 -6 7 -14z m-580 -11 c0 -8 -149 -203
                        -160 -210 -6 -4 -13 -5 -15 -3 -7 6 104 172 131 196 23 20 44 29 44 17z m606
                        -21 c4 -11 -344 -246 -357 -242 -5 2 63 55 151 117 88 63 166 121 172 128 12
                        14 30 13 34 -3z m-765 -121 c-122 -192 -162 -211 -56 -28 60 102 85 132 117
                        134 4 1 -24 -47 -61 -106z m779 85 c0 -14 -358 -244 -365 -236 -5 5 343 244
                        358 247 4 0 7 -5 7 -11z m-836 -71 c-28 -52 -39 -57 -47 -26 -8 30 0 45 36 62
                        41 20 42 18 11 -36z m840 20 c-5 -13 -367 -237 -373 -231 -10 9 31 39 185 135
                        91 57 167 107 170 111 8 13 24 -1 18 -15z m-934 -61 c0 -18 2 -19 11 -6 8 12
                        10 11 7 -5 -2 -15 -11 -20 -35 -21 -29 -1 -30 0 -22 26 11 32 39 36 39 6z
                        m930 16 c0 -5 -8 -16 -17 -24 -33 -28 -343 -201 -349 -195 -7 6 14 20 225 149
                        129 78 141 84 141 70z m-980 -27 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5
                        5 3 0 5 -2 5 -5z m-40 -24 c0 -26 -20 -83 -36 -102 -8 -8 -14 -29 -14 -46 0
                        -27 -20 -50 -33 -37 -2 2 2 41 9 86 10 65 18 86 36 100 29 23 38 22 38 -1z
                        m1005 -12 c-6 -15 -353 -204 -361 -196 -5 5 359 217 364 212 1 -1 0 -8 -3 -16z
                        m-875 -4 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5 5 3 0 5 -2 5 -5z m-80
                        -25 c0 -23 -30 -81 -39 -76 -14 9 9 86 25 86 8 0 14 -5 14 -10z m70 4 c0 -14
                        -41 -72 -55 -78 -18 -7 -18 -7 0 37 13 32 26 44 48 46 4 1 7 -2 7 -5z m147 -6
                        c13 -16 9 -47 -8 -62 -9 -8 -10 -8 -6 0 4 7 0 14 -8 18 -19 7 -20 56 -1 56 7
                        0 18 -6 23 -12z m23 7 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5 5 3 0 5 -2
                        5 -5z m231 -6 c91 -28 112 -124 53 -244 -28 -57 -31 -60 -67 -62 -65 -2 -97
                        -57 -67 -114 9 -16 4 -27 -26 -63 -33 -40 -40 -44 -69 -39 -39 6 -63 -23 -46
                        -55 10 -19 -23 -56 -146 -166 l-32 -29 -26 32 c-33 38 -67 40 -100 6 -31 -30
                        -32 -68 -4 -96 26 -26 27 -26 -106 -114 -92 -61 -100 -65 -103 -45 -4 27 -27
                        36 -58 22 -24 -12 -33 -45 -14 -57 20 -12 9 -24 -54 -59 -73 -41 -76 -42 -76
                        -22 0 22 -40 46 -77 46 -42 0 -66 -34 -61 -86 l3 -37 -90 -29 c-110 -35 -231
                        -42 -282 -15 -125 66 -53 275 180 529 63 68 84 85 92 75 17 -22 43 -27 60 -11
                        21 22 19 55 -4 61 -17 4 -10 14 42 59 70 61 76 64 69 28 -11 -57 70 -95 119
                        -56 44 36 18 122 -38 122 -28 0 -30 11 -3 28 17 10 26 10 50 -3 40 -21 53 -19
                        85 11 24 22 27 30 21 65 -3 21 -4 39 -2 39 2 0 11 3 19 6 11 4 16 -2 19 -17 2
                        -12 15 -31 30 -42 24 -18 30 -18 58 -7 54 22 66 75 26 115 l-23 23 51 28 c28
                        15 65 35 81 44 17 9 62 30 102 48 l72 31 30 -29 c53 -53 121 -30 121 40 0 36
                        2 39 33 43 67 8 121 7 158 -4z m-621 -25 c0 -22 -4 -25 -22 -21 -12 3 -30 2
                        -40 -4 -32 -17 -20 8 15 30 42 27 47 26 47 -5z m1079 -5 c-15 -21 -359 -202
                        -359 -188 0 12 46 41 145 92 50 25 119 62 155 81 77 42 76 42 59 15z m-759 6
                        c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5 5 3 0 5 -2 5 -5z m-426 -72 c19
                        -98 19 -121 -3 -133 -29 -15 -36 -8 -51 45 -12 43 -11 47 10 70 13 14 20 25
                        16 25 -4 0 -4 5 -1 10 11 18 23 11 29 -17z m1151 -6 c-9 -14 -37 -34 -63 -45
                        -26 -11 -100 -46 -165 -76 -65 -31 -117 -50 -115 -44 4 12 348 197 356 192 2
                        -1 -4 -13 -13 -27z m-1061 -34 c-11 -59 -44 -69 -44 -13 0 17 7 33 18 39 29
                        17 33 13 26 -26z m-181 -63 c10 -33 17 -64 17 -68 0 -22 -53 4 -80 37 l-29 38
                        27 26 c38 39 47 34 65 -33z m1196 20 c-12 -18 -73 -46 -281 -131 -72 -28 -59
                        -2 14 29 34 15 107 48 163 74 114 53 123 56 104 28z m-1300 -66 c20 -34 22
                        -104 2 -104 -16 0 -85 81 -75 88 6 4 16 17 23 30 16 30 26 28 50 -14z m261 21
                        c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5 5 3 0 5 -2 5 -5z m990 -29 c-14
                        -14 -79 -44 -152 -70 -137 -50 -138 -50 -138 -37 0 4 48 28 108 51 59 24 125
                        52 147 62 55 24 64 22 35 -6z m-983 -25 c0 -9 -9 -17 -18 -17 -10 0 -17 5 -16
                        11 1 5 -7 8 -18 7 -18 -4 -18 -2 4 17 26 23 51 14 48 -18z m-345 -59 c44 -45
                        46 -48 29 -65 -17 -16 -19 -16 -52 6 -20 13 -34 31 -34 43 0 12 -6 28 -13 36
                        -10 13 -10 17 0 21 21 8 21 8 70 -41z m148 28 c0 -5 6 -10 14 -10 17 0 29 -27
                        16 -35 -6 -4 -10 0 -11 7 0 9 -2 10 -5 1 -3 -7 -12 -9 -20 -6 -16 6 -20 53 -4
                        53 6 0 10 -4 10 -10z m1125 -19 c-22 -20 -213 -87 -222 -77 -10 10 2 17 109
                        56 120 45 142 49 113 21z m-255 -30 c0 -19 -29 -44 -43 -38 -17 6 -13 36 6 49
                        11 7 37 0 37 -11z m-1105 -47 c71 -48 60 -73 -17 -40 -18 8 -51 18 -73 21
                        l-40 7 28 24 c36 31 37 31 102 -12z m1290 -3 c-21 -20 -51 -33 -100 -45 -38
                        -9 -96 -22 -127 -30 -47 -11 -56 -12 -51 0 2 7 50 27 106 44 56 17 118 37 137
                        45 57 23 68 18 35 -14z m-1555 -26 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5
                        5 5 3 0 5 -2 5 -5z m59 -21 c2 -2 -7 -7 -20 -12 -28 -11 -52 6 -35 24 10 11
                        35 5 55 -12z m350 19 c-3 -20 2 -34 10 -29 5 3 12 -2 16 -11 3 -10 1 -19 -4
                        -21 -6 -2 -9 -8 -5 -13 11 -18 -6 -8 -31 18 -24 27 -34 50 -16 39 5 -3 11 1
                        15 9 6 15 17 21 15 8z m-227 -43 c33 -32 43 -36 53 -20 3 6 11 10 18 10 6 0 3
                        -9 -8 -19 -19 -19 -22 -19 -87 -3 -60 14 -68 19 -68 39 0 35 51 31 92 -7z
                        m1302 -11 c-43 -46 -61 -53 -88 -36 -12 8 -31 17 -41 19 -11 2 18 13 65 25 47
                        11 87 21 89 22 3 0 -8 -13 -25 -30z m-1254 6 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5
                        5 0 3 2 5 5 5 3 0 5 -2 5 -5z m1085 -37 c-11 -6 -23 -15 -26 -20 -3 -5 -21 -8
                        -40 -6 l-34 4 35 17 c19 9 46 16 60 16 24 0 24 0 5 -11z m-1203 -15 c80 -17
                        92 -25 60 -42 -18 -9 -187 0 -198 11 -5 6 43 48 55 48 3 0 41 -8 83 -17z
                        m1263 -33 c-3 -4 0 -10 6 -12 5 -2 -2 -15 -16 -28 -17 -16 -25 -20 -23 -10 2
                        8 -3 14 -9 13 -8 -2 -13 7 -13 22 0 19 4 23 17 18 12 -5 15 -2 10 10 -6 15 -3
                        16 14 7 11 -6 17 -15 14 -20z m25 5 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5
                        5 5 3 0 5 -2 5 -5z m-110 -35 c0 -18 -7 -20 -72 -20 -70 0 -71 0 -53 20 14 15
                        31 20 72 20 46 0 53 -2 53 -20z m-1332 -17 c-2 -11 -14 -19 -33 -21 -30 -4
                        -30 -4 -11 17 23 26 49 28 44 4z m161 -9 c-8 -10 -16 -11 -30 -3 -15 7 -19 6
                        -19 -5 0 -8 -10 -25 -22 -37 -13 -12 -17 -18 -9 -14 9 5 12 4 7 -3 -4 -6 -14
                        -8 -23 -5 -13 5 -15 2 -10 -11 4 -11 3 -15 -4 -11 -6 4 -8 13 -5 20 3 8 0 15
                        -6 17 -7 3 -9 14 -5 30 6 22 14 27 45 30 77 8 93 6 81 -8z m1211 1 c0 -3 -2
                        -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5 5 3 0 5 -2 5 -5z m-201 -51 c-12 -13 -32
                        -24 -43 -24 -19 1 -17 4 12 31 39 35 65 29 31 -7z m166 -9 c-54 -46 -105 -46
                        -105 0 0 13 -6 26 -12 28 -7 3 25 6 72 6 l85 1 -40 -35z m-1335 -6 c15 -27 18
                        -25 -75 -34 l-80 -7 29 31 c38 39 108 45 126 10z m1247 -58 c10 -5 5 -13 -18
                        -30 -30 -23 -33 -23 -66 -7 -18 9 -44 16 -56 16 -47 0 -68 11 -55 27 10 13 28
                        14 97 9 47 -4 91 -11 98 -15z m-1212 -11 c-15 -14 -53 -25 -139 -40 -66 -11
                        -121 -19 -122 -17 -2 2 9 15 24 30 25 24 39 28 127 35 55 5 107 10 115 11 11
                        1 9 -4 -5 -19z m1337 -4 c16 -28 -5 -41 -31 -20 -11 10 -21 23 -21 30 0 17 41
                        9 52 -10z m-421 -34 c2 2 -2 -3 -10 -9 -18 -16 -31 -17 -31 -2 0 5 -4 7 -10 4
                        -5 -3 -10 3 -10 14 0 26 32 32 46 9 5 -10 12 -17 15 -16z m112 -1 c14 -6 28
                        -19 32 -30 7 -23 34 -28 53 -9 7 7 12 9 12 6 0 -4 -16 -19 -35 -33 l-34 -27
                        -51 28 c-63 34 -79 52 -59 65 18 11 51 11 82 0z m-1084 -27 c-13 -12 -133 -46
                        -259 -71 l-25 -5 25 26 c21 21 45 29 130 45 140 25 149 25 129 5z m985 -42
                        c23 -14 29 -25 30 -60 3 -60 -7 -60 -54 -1 -43 55 -47 65 -27 72 19 8 20 8 51
                        -11z m-1024 -17 c-17 -16 -300 -106 -300 -95 0 25 57 53 161 79 137 35 160 37
                        139 16z m930 -22 c0 -5 16 -25 35 -47 19 -21 35 -41 35 -45 0 -19 -13 -17 -36
                        6 -19 19 -28 22 -39 13 -7 -6 -15 -7 -18 -3 -9 15 -11 58 -4 71 9 13 27 17 27
                        5z m-966 -33 c-15 -15 -304 -110 -304 -101 0 17 29 30 155 69 138 43 166 49
                        149 32z m911 -77 c17 -64 17 -66 0 -80 -30 -21 -33 -17 -40 56 -11 112 13 126
                        40 24z m-935 33 c-12 -10 -163 -78 -324 -145 -5 -2 -1 9 8 25 15 25 40 37 164
                        80 164 58 172 60 152 40z m841 -76 c10 -8 21 -29 25 -46 6 -29 2 -34 -41 -64
                        l-46 -32 7 89 c6 81 24 127 32 85 2 -9 12 -24 23 -32z m160 37 c-1 -12 -15 -9
                        -19 4 -3 6 1 10 8 8 6 -3 11 -8 11 -12z m-1016 -6 c-16 -15 -345 -170 -345
                        -163 1 22 32 40 171 102 143 64 197 83 174 61z m769 -74 c-7 -65 -7 -65 -31
                        -54 -21 9 -24 16 -19 45 4 28 43 83 54 76 2 -1 0 -31 -4 -67z m-784 34 c0 -6
                        -78 -49 -172 -98 -209 -106 -196 -101 -178 -75 12 18 318 179 343 181 4 1 7
                        -3 7 -8z m548 -49 c2 -7 8 -10 13 -6 5 3 9 1 9 -3 0 -10 -31 -30 -45 -29 -5 1
                        -10 13 -10 27 -1 27 25 35 33 11z m155 1 c-3 -10 -15 -26 -27 -35 -19 -16 -19
                        -18 -3 -18 9 0 34 -5 55 -12 l38 -12 -21 -21 c-36 -38 -52 -38 -60 2 -4 24 -9
                        32 -14 24 -16 -25 -21 20 -6 55 14 35 50 50 38 17z m-713 3 c0 -2 -66 -41
                        -147 -87 -82 -45 -160 -90 -175 -101 -36 -25 -55 -24 -37 2 21 32 359 206 359
                        186z m650 -20 c0 -1 -23 -51 -50 -109 -43 -91 -70 -127 -94 -127 -8 0 80 176
                        106 213 16 23 38 36 38 23z m-665 -25 c-28 -27 -347 -213 -353 -206 -10 10 16
                        27 185 126 169 98 204 115 168 80z m5 -34 c-16 -15 -326 -206 -340 -210 -4 -1
                        -8 3 -7 8 0 6 77 56 171 112 161 96 210 121 176 90z m483 -64 c-22 -32 -56
                        -85 -76 -118 -25 -43 -47 -66 -73 -78 -20 -10 -38 -17 -39 -15 -5 5 173 242
                        196 261 36 29 33 12 -8 -50z m327 62 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2
                        5 5 5 3 0 5 -2 5 -5z m140 -28 c0 -16 -20 -37 -34 -37 -6 0 -4 5 4 10 13 8 13
                        10 -2 10 -12 0 -15 5 -11 17 5 12 3 14 -8 8 -12 -8 -12 -6 0 8 15 18 51 7 51
                        -16z m-390 13 c0 -32 -90 -187 -115 -198 -15 -7 -29 -11 -31 -9 -2 2 26 51 63
                        110 60 98 83 124 83 97z m110 -13 c0 -13 5 -28 12 -35 14 -14 9 -19 -45 -52
                        l-38 -22 17 38 c9 22 21 51 27 67 14 33 27 35 27 4z m-650 9 c0 -9 -361 -238
                        -367 -232 -11 10 6 23 183 132 156 96 184 111 184 100z m9 -30 c-2 -2 -79 -56
                        -171 -120 -154 -107 -195 -131 -186 -107 4 12 351 241 357 236 2 -3 2 -7 0 -9z
                        m389 -18 c-14 -18 -57 -76 -96 -129 -39 -53 -79 -99 -89 -102 -10 -2 -27 -8
                        -38 -12 -14 -6 -10 3 16 31 20 21 71 82 115 134 43 52 83 98 89 102 24 18 26
                        6 3 -24z m552 17 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5 5 3 0 5 -2 5 -5z
                        m-921 -18 c-2 -2 -65 -50 -139 -107 -74 -57 -147 -114 -162 -128 -24 -22 -54
                        -23 -46 -2 5 13 341 251 346 245 3 -3 3 -6 1 -8z m319 4 c-4 -11 -158 -190
                        -204 -238 -18 -18 -39 -33 -48 -33 -10 0 34 55 111 140 114 125 152 159 141
                        131z m-87 -48 c-82 -97 -226 -243 -240 -243 -7 0 23 39 67 88 137 150 273 271
                        173 155z m-211 23 c-8 -8 -80 -68 -160 -135 -130 -108 -178 -141 -168 -111 4
                        13 319 260 332 260 6 0 4 -6 -4 -14z m39 -10 c-8 -7 -63 -57 -124 -111 -60
                        -54 -124 -112 -142 -128 -20 -19 -38 -27 -48 -23 -14 5 62 75 284 259 32 27
                        59 30 30 3z m149 15 c-8 -24 -270 -281 -285 -281 -11 0 38 56 127 145 130 131
                        168 163 158 136z m-129 -37 c-70 -74 -269 -254 -279 -254 -21 0 -7 15 145 153
                        141 129 211 182 134 101z m-49 -100 c-129 -130 -166 -161 -176 -151 -8 8 280
                        286 296 287 8 0 -46 -61 -120 -136z M1990 2115 c0 -3 2 -5 5 -5 3 0 5 2 5 5 0
                        3 -2 5 -5 5 -3 0 -5 -2 -5 -5z M1993 2093 c4 -3 10 -3 14 0 3 4 0 7 -7 7 -7 0
                        -10 -3 -7 -7z M2028 1909 c-22 -12 -24 -58 -2 -66 23 -8 64 14 64 35 0 36 -29
                        50 -62 31z M2271 1894 c-25 -32 -20 -69 16 -101 27 -26 44 -28 76 -12 30 16
                        37 28 37 65 0 67 -88 100 -129 48z m65 -24 c-7 11 -4 11 9 -2 12 -11 15 -23
                        10 -35 -5 -12 -9 -14 -12 -5 -3 6 -9 10 -13 7 -11 -7 -32 30 -23 39 3 3 13 0
                        21 -7 13 -10 14 -9 8 3z M2142 1804 c-47 -33 -14 -91 34 -59 29 19 30 36 6 58
                        -16 15 -20 15 -40 1z M1915 1788 c-63 -35 -37 -132 34 -130 67 2 96 75 48 120
                        -26 24 -52 27 -82 10z m49 -47 c3 -5 0 -14 -8 -20 -10 -9 -15 -8 -19 3 -9 22
                        15 37 27 17z M2207 1673 c-14 -13 -6 -53 12 -63 28 -14 66 18 57 48 -7 22 -53
                        32 -69 15z M2011 1634 c-26 -33 -27 -64 -1 -91 41 -45 99 -34 120 23 28 72
                        -72 128 -119 68z m67 -39 c-2 -23 -24 -42 -36 -30 -3 3 0 5 8 5 10 0 10 3 1
                        14 -7 9 -8 17 -2 20 5 3 12 0 14 -6 3 -8 6 -6 6 5 1 9 3 17 6 17 3 0 4 -11 3
                        -25z m22 0 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5 5 3 0 5 -2 5 -5z
                        M1713 1620 c-140 -72 -92 -294 64 -294 97 1 158 60 158 156 0 120 -115 192
                        -222 138z m130 -42 c15 -5 27 -16 27 -24 0 -8 4 -14 9 -14 5 0 9 -27 9 -60 0
                        -39 -4 -58 -12 -57 -6 1 -10 -7 -9 -18 1 -11 0 -14 -3 -7 -5 10 -12 8 -30 -9
                        -29 -27 -99 -22 -126 10 -9 12 -21 21 -26 21 -15 0 -9 113 6 118 6 2 12 9 12
                        16 0 13 31 32 65 39 20 5 27 3 78 -15z M2019 1462 c-28 -23 -30 -73 -4 -102
                        25 -28 76 -26 103 3 61 65 -29 155 -99 99z m66 -54 c-2 -25 -20 -35 -31 -17
                        -4 7 1 18 11 26 23 16 22 17 20 -9z M2070 1395 c0 -3 2 -5 5 -5 3 0 5 2 5 5 0
                        3 -2 5 -5 5 -3 0 -5 -2 -5 -5z M1460 1420 c-53 -53 -11 -128 68 -121 22 2 52
                        40 52 66 0 59 -79 96 -120 55z m66 -46 c-3 -9 -6 -20 -6 -25 0 -5 -7 -9 -16
                        -9 -13 0 -14 3 -4 15 6 8 9 19 5 25 -3 5 1 10 10 10 12 0 15 -5 11 -16z m-31
                        -4 c3 -5 1 -10 -4 -10 -6 0 -11 5 -11 10 0 6 2 10 4 10 3 0 8 -4 11 -10z
                        M1332 1318 c-32 -32 1 -79 44 -59 35 16 31 65 -6 69 -14 2 -31 -3 -38 -10z
                        M1585 1298 c-26 -15 -44 -39 -45 -60 0 -27 49 -78 76 -78 33 0 74 42 74 75 0
                        30 -41 75 -67 75 -10 0 -27 -6 -38 -12z m46 -63 c5 3 6 -1 3 -10 -6 -15 -8
                        -15 -22 -2 -9 9 -11 20 -6 29 7 10 9 9 12 -5 2 -9 8 -15 13 -12z M1163 1272
                        c-49 -8 -70 -85 -33 -122 41 -41 93 -35 119 14 27 50 -27 118 -86 108z m37
                        -72 c-5 -9 -7 -19 -5 -22 2 -2 -5 -4 -15 -4 -10 0 -17 2 -15 5 3 2 -1 11 -8
                        19 -10 13 -9 15 5 9 10 -3 17 -3 17 1 -3 16 2 21 16 16 11 -4 12 -11 5 -24z
                        M1755 1245 c-14 -13 -25 -36 -25 -50 0 -30 41 -75 68 -75 25 0 69 35 77 60 7
                        21 -10 58 -34 77 -25 20 -60 15 -86 -12z m75 -58 c0 -5 -4 -5 -10 -2 -5 3 -10
                        1 -10 -4 0 -19 -17 -12 -23 8 -3 13 1 21 12 23 15 4 31 -8 31 -25z M1416 1217
                        c-29 -21 -18 -62 18 -62 36 0 56 27 40 53 -15 22 -35 26 -58 9z M1518 1119
                        c-25 -14 -23 -56 2 -64 42 -13 78 33 48 63 -14 14 -27 15 -50 1z M1242 1088
                        c-46 -50 -12 -128 55 -128 41 0 77 42 68 80 -15 61 -85 88 -123 48z m44 -39
                        c5 -8 9 -8 14 1 5 8 9 3 12 -12 3 -18 0 -25 -11 -25 -15 0 -40 24 -41 40 0 11
                        18 8 26 -4z m44 -14 c0 -3 -2 -5 -5 -5 -3 0 -5 2 -5 5 0 3 2 5 5 5 3 0 5 -2 5
                        -5z M1780 1775 c0 -3 2 -5 5 -5 3 0 5 2 5 5 0 3 -2 5 -5 5 -3 0 -5 -2 -5 -5z
                        M2440 1685 c0 -3 2 -5 5 -5 3 0 5 2 5 5 0 3 -2 5 -5 5 -3 0 -5 -2 -5 -5z
                        M2450 1435 c0 -3 2 -5 5 -5 3 0 5 2 5 5 0 3 -2 5 -5 5 -3 0 -5 -2 -5 -5z
                        M2410 1285 c0 -3 2 -5 5 -5 3 0 5 2 5 5 0 3 -2 5 -5 5 -3 0 -5 -2 -5 -5z
                        M1930 1015 c0 -3 2 -5 5 -5 3 0 5 2 5 5 0 3 -2 5 -5 5 -3 0 -5 -2 -5 -5z
                        M1750 2208 c-25 -28 -26 -58 0 -91 48 -60 130 -30 130 47 0 57 -89 87 -130 44z
                        m64 -34 c19 -7 22 -34 4 -34 -9 0 -8 -4 2 -10 8 -5 11 -10 5 -10 -18 0 -30 15
                        -31 38 -1 25 -1 24 20 16z M1566 2199 c-51 -40 -18 -129 47 -129 38 0 50 7 66
                        37 15 30 4 69 -26 93 -33 25 -54 25 -87 -1z m74 -49 c0 -15 -48 -29 -53 -15
                        -3 7 -3 15 -1 18 8 7 54 5 54 -3z M1385 2115 c-32 -31 -33 -75 -2 -102 31 -28
                        55 -35 80 -21 54 28 61 76 18 119 -36 35 -64 37 -96 4z m41 -41 c-5 -14 -4
                        -15 8 -5 11 9 15 7 18 -7 6 -20 -21 -26 -34 -8 -6 9 0 36 9 36 3 0 2 -7 -1
                        -16z M1229 1992 c-48 -40 -11 -132 53 -132 65 0 94 88 42 129 -33 26 -65 27
                        -95 3z m72 -41 c0 -3 2 -14 4 -24 2 -11 1 -16 -2 -13 -4 3 -12 1 -19 -5 -13
                        -11 -30 22 -20 39 6 10 36 12 37 3z M1290 1935 c0 -3 2 -5 5 -5 3 0 5 2 5 5 0
                        3 -2 5 -5 5 -3 0 -5 -2 -5 -5z M1121 1839 c-30 -33 -32 -66 -4 -100 48 -57
                        123 -30 123 44 0 64 -77 101 -119 56z m66 -54 c8 -21 -1 -28 -27 -20 -17 5
                        -19 10 -9 21 15 18 28 18 36 -1z M2270 1133 c-26 -33 -20 -68 16 -100 48 -44
                        114 -14 114 51 0 70 -87 103 -130 49z m73 -64 c-23 -8 -41 20 -22 32 9 6 18 3
                        27 -8 10 -15 10 -19 -5 -24z M2136 988 c-22 -31 -20 -74 4 -98 67 -67 160 1
                        113 82 -25 44 -92 53 -117 16z m70 -39 c3 -6 1 -16 -5 -22 -11 -11 -30 5 -31
                        26 0 11 28 8 36 -4z M1965 890 c-26 -28 -23 -75 5 -105 45 -48 120 -20 120 45
                        0 65 -84 105 -125 60z m75 -55 c0 -4 -7 -13 -15 -21 -13 -13 -15 -12 -21 10
                        -8 34 3 50 22 32 8 -8 14 -17 14 -21z"/>
                    </g>
                </svg>
            </a>


            <ul>
              <!-- <li><a href="index.html">Home</a></li> -->
              <li><a href="peinture.html">Acrylique</a></li>
              <li><a href="aquarelle.html">Aquarelle</a></li>
              <li><a href="haushka.html">Haushka</a></li>
              <li><a href="haikus.html">Ha&iuml;kus</a></li>
              <!-- <li><a href="#" class="navBurgerDisabled">Reproductions</a></li>
              <li><a href="#" class="navBurgerDisabled">Expositions</a></li> -->
              <li><a href="contact.php" class="burgerOngletActif">Contact</a></li>
            </ul>


            <!-- (TODO: changer SVG burger: EZ) -->
            <!-- <object type="image/svg+xml" data="burgerDrawing.svg" width="260" height="auto" class="svgBurgerObj">
                Your browser does not support SVG
            </object> -->


            <div class="mentionsLegalesDiv">
                <a href="mentionsLegales.html">Mentions l&eacute;gales</a>
            </div>
        </div>
        <!-- Btn burger -->
        <a href="#" id="openBtn" class="isMobile">
            <span class="burger-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>




        <div class="bgStars">
            <div id="stars"></div>
            <div id="stars2"></div>
            <div id="stars3"></div>
        </div>

        <div id="contactContent" class="contactContent">


            <!-- titre absolute -->
            <div id="titlesDiv" class="titlesDiv titleDiv-Contact">
                <a href="./index.html" class="linkTitle">
                    <h1 id="h1titleBurgerDesktop" class="h1title h1titleBurgerDesktop">
                        <!-- Burger Icone PC (à titre indicatif): -->
                        <span id="burger-icon-desktop" class="burger-icon-desktop isDesktop">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                        Les 100 ciels
                    </h1>
                </a>
            </div>

            <h2 class="contactSubTitle">
                <i class="fa-solid fa-envelope contactFormIconTitle"></i>
                <span class="spanContactFormMobile">Formulaire de <br class="isMobile">contact</span>
            </h2>

            <div class="contactFormWrapper">

                <form class="contactForm" method="post" action="contact.php" enctype="multipart/form-data">
                    <div class="formLinePc">
                        <div class="formLineElemPc">
                            <label for="name">Votre nom <span class="fieldInfo">(facultatif)</span> :</label>
                            <input type="text" name="name" id="name" maxlength="75">
                        </div>

                        <div class="formLineElemPc">
                            <label for="last_name">Votre prénom <span class="fieldInfo">(facultatif)</span> :</label>
                            <input type="text" name="last_name" id="last_name" maxlength="75">
                        </div>
                    </div>

                    <br>

                    <label for="phone_number">Votre n° de téléphone <span class="fieldInfo">(facultatif)</span> :</label>
                    <input type="text" name="phone_number" id="phone_number" maxlength="15">


                    <label for="from">Votre adresse mail <span class="fieldInfo">(afin de vous recontacter)</span> :</label>
                    <input type="email" name="from" id="from" required maxlength="100">

                    <br>

                    <!-- Podmielle -->
                    <label for="podmielle" class="podmielle">podmielle</label>
                    <input type="text" name="podmielle" id="podmielle">

                    <label for="message">Votre message :</label>
                    <textarea name="message" id="message" rows="4" required maxlength="2000"></textarea>

                    <br>

                    <!-- reCAPTCHA désactivé  -->
                    <!-- <div class="g-recaptcha" data-sitekey="your_site_key"></div> -->

                    <!-- Disclaimer  -->
                    <p class="contactFormDisclaimer">Vos informations personnelles ne seront ni stockées ni partagées. Elles seront uniquement utilisées pour vous recontacter.</p>

                    <label for="math">Combien font <?php echo $_SESSION['number1']; ?> + <?php echo $_SESSION['number2']; ?> ?</label>
                    <input type="text" id="math" name="math" required>

                    <br>

                    <input class="contactFormSubmitBtn" type="submit">
                </form>


            </div>
        
        </div>

    </body>




    <script>

        // Menu burger mobile pop:
        var sidenav = document.getElementById("mySidenav");
        var openBtn = document.getElementById("openBtn");
        var closeBtn = document.getElementById("closeBtn");

        openBtn.onclick = openNav;
        closeBtn.onclick = closeNav;

        function openNav() {
            sidenav.classList.add("active");
            // pas opti :
            // document.getElementById("titlesDiv").style.filter = "blur(2px)";
            // document.getElementById("contactContent").style.filter = "blur(3px)";
        }

        function closeNav() {
            sidenav.classList.remove("active");

            // document.getElementById("titlesDiv").style.filter = "blur(0px)";
            // document.getElementById("contactContent").style.filter = "blur(0px)";

            sidenav.classList.add('closeBurgerColorAnimated');
            setTimeout(function() {
                sidenav.classList.remove('closeBurgerColorAnimated');
            }, 500);
        }


        // WIP click outer navBurger = close() (MOBILE):
        document.getElementById("contactContent").addEventListener('click', function(e){   

            if (document.getElementById('mySidenav').contains(e.target)){
                // click dans nav burger
            } else {
                // click en dehors nav burger
                if (sidenav.classList.contains("active")) {
                    closeNav();
                }
            }
        });




        // DESKTOP hover title = sideNav-desktop:
        
        // Détection mobile (isMobile JS)
        var mobileDetection;
        if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)) {
            mobileDetection = true ;
        } else {
            mobileDetection = false ;
        }

        if (!mobileDetection) {

            document.getElementById('titlesDiv').addEventListener("mouseenter", function() {
                document.getElementById('mySidenav-desktop').style.left = "0";
                document.getElementById('mySidenav-desktop').style.opacity = "1";

                document.getElementById("burgerActif-shadePage").classList.add("shadePage-active");

                document.getElementById('burger-icon-desktop').style.opacity = "0";
                document.getElementById('h1titleBurgerDesktop').style.transform = "translateX(-35px)";
            });
            document.getElementById('shapeDetection-navBarre-desktop').addEventListener("mouseenter", function() {
                console.log("NETERED");
                document.getElementById('mySidenav-desktop').style.left = "0";
                document.getElementById('mySidenav-desktop').style.opacity = "1";

                document.getElementById("burgerActif-shadePage").classList.add("shadePage-active");

                document.getElementById('burger-icon-desktop').style.opacity = "0";
                document.getElementById('h1titleBurgerDesktop').style.transform = "translateX(-35px)";
            });


            document.getElementById('mySidenav-desktop').addEventListener("mouseleave", function() {
                document.getElementById('mySidenav-desktop').style.left = "-350px";
                document.getElementById('mySidenav-desktop').style.opacity = "0";

                document.getElementById("burgerActif-shadePage").classList.remove("shadePage-active");

                document.getElementById('burger-icon-desktop').style.opacity = "1";
                document.getElementById('h1titleBurgerDesktop').style.transform = "translateX(0px)";
            });

        }




    </script>



</html>

