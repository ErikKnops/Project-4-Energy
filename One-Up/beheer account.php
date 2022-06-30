<?php
require("php/functies.php");

if ($_SESSION['ingelogd'] != true) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="One-Up">
        <meta name="author" content="Jesca Wiegers, Danny van Kampen, Erik Knöps, Sami Alouat">
        <meta name="keywords" content="">
        <link
            href="https://fonts.googleapis.com/css2?family=Tourney:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
        <link
            href="https://fonts.googleapis.com/css2?family=Lexend:wght@100;200;300;400;500;600;700;800;900&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/master.css">
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <script type="text/javascript" src="js/burger-menu.js"></script>
        <title>One-Up</title>
    </head>
    
    <body>
        <?php get_header(); ?>
        <main class="container">
            <ul class="instelling-ul">
                <li><h1 class="loginform__h1">Instellingen</h1></li>
                <li><a class="bewerken-button" href="verander gebruikersnaam.php">Verander gebruikersnaam</a></li>
                <li><a class="bewerken-button" href="verander wachtwoord.php">Verander wachtwoord</a></li>
                <li><a class="bewerken-button verwijder-button" href="verwijder account.php">Verwijder account</a></li>
                <?php
                require('php/dbconnect.php');
                if ($_SESSION['ingelogd'] && $_SESSION['permission'] >= 2) {
                ?> <li><a class="bewerken-button" href="bewerken2.php">Database bewerken</a></li> <?php
                }
                ?>
            </ul>
            
        </main>
        <?php get_footer(); ?>
    </body>
</html>