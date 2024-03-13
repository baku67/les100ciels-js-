<?php



    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check if the honeypot field is filled
        if (!empty($_POST['honeypot'])) {
            // If honeypot is filled, it's likely a bot, so reject the submission
            die("Spam submission detected. Please try again.");
        }

        // Check if the required keys "message" and "from" are set in $_POST
        if(isset($_POST["message"]) && isset($_POST["from"])) {
            // Extract the message and sender's email
            $message = $_POST["message"];
            $from = $_POST["from"];
            
            // Additional optional fields
            $name = isset($_POST["name"]) ? $_POST["name"] : "";
            $lastName = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
            $phoneNumber = isset($_POST["phone_number"]) ? $_POST["phone_number"] : "";

            // Construct the email content
            $emailContent = "Name: $name\n";
            $emailContent .= "Last Name: $lastName\n";
            $emailContent .= "Phone Number: $phoneNumber\n";
            $emailContent .= "Message: $message\n";
            
            // Email configuration
            // $to = "basile08@hotmail.fr";
            $to = "christine.k2r2@free.fr";
            $subject = "Nouveau message depuis le formulaire de contact de www.les100ciels.art";
            $headers = "From: $from\r\n";

            // Send the email
            mail($to, $subject, $emailContent, $headers);
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

            @media (max-width: 480px) {

                html {
                    height: auto;
                }
                body {
                    position: relative;
                    overflow: auto;
                }



            }

        </style>

    </head>



    <body style="overflow:auto;">



        <div class="bgStars">
            <div id="stars"></div>
            <div id="stars2"></div>
            <div id="stars3"></div>
        </div>

        <div class="contactContent">


            <!-- titre absolute -->
            <div class="titlesDiv">
                <a href="./index.html" class="linkTitle">
                    <h1 class="h1title">Les 100 ciels</h1>
                </a>
            </div>

            <h2 class="contactSubTitle">
                <i class="fa-solid fa-envelope contactFormIconTitle"></i>
                Contactez-moi
            </h2>

            <div class="contactFormWrapper">

                <form class="contactForm" method="post" action="contact.php" enctype="multipart/form-data">
                    <div class="formLinePc">
                        <div class="formLineElemPc">
                            <label for="name">Votre nom <span class="fieldInfo">(facultatif)</span> :</label>
                            <input type="text" name="name" id="name">
                        </div>

                        <div class="formLineElemPc">
                            <label for="last_name">Votre prénom <span class="fieldInfo">(facultatif)</span> :</label>
                            <input type="text" name="last_name" id="last_name">
                        </div>
                    </div>

                    <br>

                    <label for="phone_number">Votre n° de téléphone <span class="fieldInfo">(facultatif)</span> :</label>
                    <input type="text" name="phone_number" id="phone_number">


                    <label for="from">Votre adresse mail <span class="fieldInfo">(afin de vous recontacter)</span> :</label>
                    <input type="email" name="from" id="from" required>

                    <br>

                    <label for="message">Votre message :</label>
                    <textarea name="message" id="message" rows="4" required></textarea>

                    <br>

                    <!-- Honeypot Field -->
                    <div style="display: none;">
                        <input type="text" name="honeypot" id="honeypot">
                    </div>

                    <!-- reCAPTCHA désactivé  -->
                    <!-- <div class="g-recaptcha" data-sitekey="your_site_key"></div> -->

                    <!-- Disclaimer  -->
                    <p class="contactFormDisclaimer">Vos informations personnelles ne seront ni stockées ni partagées. Elles seront uniquement utilisées pour vous recontacter concernant votre demande.</p>

                    <input class="contactFormSubmitBtn" type="submit">
                </form>


            </div>
        
        </div>

    </body>

</html>

