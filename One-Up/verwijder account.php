<?php
require("php/functies.php");

if ($_SESSION['ingelogd'] != true) {
    header("Location: login.php");
}

require("php/dbconnect.php");

if(!isset($_SESSION['verwijderFase2'])){
    $_SESSION['verwijderFase2'] = false;
}
if(!isset($_POST["1"]) && !isset($_POST["2"])){
    $_SESSION['aantalJaVoorVerwijderen'] = 0;
}

if(!isset($_POST["1"]) && !isset($_POST["2"]) && (!isset($_SESSION['aantalJaVoorVerwijderen']) && !$_SESSION['verwijderFase2'] && !isset($_POST["submitVerwijderen"])) || isset($_POST["annuleerVerwijderen"])){
    $_SESSION['aantalJaVoorVerwijderen'] = 0;
    $_SESSION['verwijderFase2'] = false;
}

$jaKnopVerwijderen = rand(1,2);
$aantalJaNodig = 3;
$knopAntwoord = "";
$error = "";

if($_SESSION['aantalJaVoorVerwijderen'] >= $aantalJaNodig-1){
    $_SESSION['verwijderFase2'] = true;
}

if (isset($_POST["1"])) {
    if($_POST["1"] == "Ja"){$knopAntwoord = "Ja";}else{$knopAntwoord = "Nee";}
}

if (isset($_POST["2"])) {
    if($_POST["2"] == "Ja"){$knopAntwoord = "Ja";}else{$knopAntwoord = "Nee";}
}

if($knopAntwoord == "Ja"){
    $_SESSION['aantalJaVoorVerwijderen'] += 1;
}
if($knopAntwoord == "Nee"){
    $_SESSION['aantalJaVoorVerwijderen'] = 0;
    $_SESSION['verwijderFase2'] = false;
    header("Location: beheer account.php");
}

if (isset($_POST['submitVerwijder']) && $_SESSION['verwijderFase2']) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = ($_POST['password']);
        $sql = "SELECT * FROM gebruikers WHERE gebruiker_id =".$_SESSION['gebruiker_id'];
        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_object()) {
                $dbuser = $row->username;
                $dbpass = $row->password;
                if($username == $dbuser && $password == $dbpass){
                    $sql = "DELETE FROM gebruikers WHERE gebruiker_id =" . $_SESSION['gebruiker_id'];
                    if ($conn->query($sql) === TRUE) {
                        header("Location: php/logout.php");
                    }
                    else {
                        $error = "Error: " . $sql . "<br>" . $conn->error;
                    }
                }else{
                    $error = "Onjuist gebruikersnaam of wachtwoord";
                }
            }
            $error = "Onjuist gebruikersnaam of wachtwoord";
            $result->close();
        }
        $conn->close();
    }
    else {
        $error = "Gebruikersnaam en wachtwoord zijn verplicht";
    }

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
            <div class="loginform-container">
                <?php
                if($_SESSION['verwijderFase2']){
                    echo '
                    <h1 class="loginform__h1">Vul uw login gegevens in om uw account te verwijderen</h1>
                    <form method="POST">
                        '.  "<strong>" . $error . "</strong>" .'<br>
                        <input type="text" name="username" placeholder="Gebruikersnaam"/><br>
                        <input type="password" name="password" placeholder="Wachtwoord"/><br><br>
                        <input type="submit" name="submitVerwijder" value="Verwijder" class="extra-margin verwijder-button"/>
                        <input type="submit" name="annuleerVerwijderen" value="Annuleren" class="extra-margin"/>
                    </form>
                ';
                }else{
                    echo '
                    <h1 class="loginform__h1">Weet u het zeker?</h1>
                    <p class="loginform__p">Al uw gegevens gaan voorgoed weg.</p>
                    <form method="POST">
                        <input type="submit" name="1" value="'; if($jaKnopVerwijderen == 1){echo "Ja";}else{echo "Nee";} echo '"  class="extra-margin extra-margin-top"/>
                        <input type="submit" name="2" value="'; if($jaKnopVerwijderen == 2){echo "Ja";}else{echo "Nee";} echo '"  class="extra-margin extra-margin-top"/>
                        <p class="loginform__p">' .$_SESSION['aantalJaVoorVerwijderen']."/".$aantalJaNodig.'</p>
                    </form>
                    ';
                }            
                ?>
            </div>
        </main>
        <?php get_footer(); ?>
    </body>
</html>