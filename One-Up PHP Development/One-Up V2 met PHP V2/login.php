<?php
require("php/functies.php"); 
require("php/dbconnect.php");

if(isset($_SESSION['ingelogd']) && $_SESSION['ingelogd']){
    header("location:javascript://history.go(-1)");
}

$error = "";

if (isset($_POST['submit'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $sql = "SELECT * FROM gebruikers WHERE username = '".$username."'";
        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_object()) {
                $dbpass = $row->password;
                if ($password == $dbpass) {
                    $_SESSION['ingelogd'] = true;
                    $_SESSION['gebruiker_id'] = $row->gebruiker_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['permission'] = $row->permission;
                    header("Location: index.php");
                }
            }
            $result->close();
        }
        $conn->close();

        $error = "Onjuist gebruikersnaam of wachtwoord";

    }
    else {
        $error = "Gebruikersnaam en wachtwoord zijn verplicht";
    }

}
?>

<!DOCTYPE html>
<html lang="nl">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="One-Up">
        <meta name="author" content="Jesca Wiegers, Danny van Kampen">
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
        <?php get_header(null,false,"Inloggen"); ?>

        <main class="container">
            <div class="loginform-container">
                <h1 class="loginform__h1">Log in op uw account</h1>
                <p class="loginform__p">Nog geen account? Klik <a href="signup.php">hier</a> om te registreren!</p>
                <form method="POST">
                    <?php echo "<strong>" . $error . "</strong><br>"; ?><br>
                    <input type="text" name="username" placeholder="Gebruikersnaam"><br>
                    <input type="password" name="password" placeholder="Wachtwoord"><br><br>
                    <input type="submit" name="submit" value="Inloggen" class="login-button">
                </form>
            </div>
        </main>

        <?php get_footer(); ?>
    </body>

</html>