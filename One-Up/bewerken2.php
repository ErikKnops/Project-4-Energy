<?php
// Database bewerk pagina 2.0 gemaakt door Danny en Erik (bevat wat code van bewerk pagina 1.0)

require("php/functies.php");

if ($_SESSION['ingelogd'] != true) {
    header("Location: login.php");
} else if ($_SESSION['permission'] < 2) {
    header("Location: beheer account.php");
}

require("php/dbconnect.php");

//alerts
$alertAanbieding = "";
$alertArtiest = "";
$alertEvenement = "";
$alertGebruiker = "";
$alertLocatie = "";
$alertReactie = "";

//add
if (isset($_POST['submit-aanbieding-add'])) {
    if (!empty($_POST['aanbieding-titel-add'])) {
        $aanbiedingTitel = trim($_POST['aanbieding-titel-add']);
        $aanbiedingBegindatum = $_POST['aanbieding-begindatum-add'];
        $aanbiedingEinddatum = $_POST['aanbieding-einddatum-add'];
        $aanbiedingOmschrijving = trim($_POST['aanbieding-omschrijving-add']);
        $aanbiedingAfbeelding = trim($_POST['aanbieding-afbeelding-add']);
        $aanbiedingArtiestId = $_POST['aanbieding-artiest-id-add'];

        if($aanbiedingBegindatum == NULL){$aanbiedingBegindatum = "0001-01-01 00:00";}
        if($aanbiedingEinddatum == NULL){$aanbiedingEinddatum = "0001-01-01 00:00";}

        $sql = "SELECT artiest_id FROM artiesten WHERE artiest_id = '$aanbiedingArtiestId'";
        if($tabelConn = $conn->query($sql)){
            $rows = mysqli_num_rows($tabelConn);
            if($rows >= 1){
                $sql = "INSERT INTO aanbiedingen (titel, begindatum, einddatum, omschrijving, afbeelding, artiest_id) 
                VALUES ('$aanbiedingTitel', '$aanbiedingBegindatum', '$aanbiedingEinddatum', '$aanbiedingOmschrijving', '$aanbiedingAfbeelding', '$aanbiedingArtiestId')";

                if ($tabelConn = $conn->query($sql)) {
                    $alertAanbieding = "Aanbieding Toegevoegd";
                } else {
                    $alertAanbieding = "Toevoegen Gefaald";
                }
            }else{
                $alertAanbieding = "Ongeldig Artiest Id";
            }
        }
    } else {
        $alertAanbieding = "Vul een Titel in";
    }
}

if (isset($_POST['submit-artiest-add'])) {
    if (!empty($_POST['artiest-naam-add'])) {
        $artiestNaam = trim($_POST['artiest-naam-add']);
        $artiestAchternaam = trim($_POST['artiest-achternaam-add']);
        $artiestVoornaam = trim($_POST['artiest-voornaam-add']);
        $artiestTussenvoegsel = trim($_POST['artiest-tussenvoegsel-add']);
        $artiestStatement = trim($_POST['artiest-statement-add']);
        $artiestTelefoon = $_POST['artiest-telefoon-add'];
        $artiestActief = $_POST['artiest-actief-add'];

        $sql = "INSERT INTO artiesten (naam, achternaam, voornaam, tussenvoegsel, statement, telefoon, actief) 
        VALUES ('$artiestNaam', '$artiestAchternaam', '$artiestVoornaam', '$artiestTussenvoegsel', '$artiestStatement', '$artiestTelefoon', '$artiestActief')";

        if ($tabelConn = $conn->query($sql)) {
            $alertArtiest = "Artiest Toegevoegd";
        } else {
            $alertArtiest = "Toevoegen Gefaald";
        }
    } else {
        $alertArtiest = "Vul een Naam in";
    }
}

if (isset($_POST['submit-evenement-add'])) {
    if (!empty($_POST['evenement-artiest-id-add']) && !empty($_POST['evenement-locatie-id-add'])) {
        $evenementDatum = $_POST['evenement-datum-add'];
        $evenementArtiestId = $_POST['evenement-artiest-id-add'];
        $evenementLocatieId = $_POST['evenement-locatie-id-add'];
        $evenementMaxBezoekers = $_POST['evenement-max-bezoekers-add'];

        if($evenementDatum == NULL){$evenementDatum = "0001-01-01 00:00";}

        $sql = "SELECT artiest_id FROM artiesten WHERE artiest_id = '$evenementArtiestId'";
        if($tabelConn = $conn->query($sql)){
            $rows = mysqli_num_rows($tabelConn);
            if($rows >= 1){
                $sql = "SELECT locatie_id FROM locaties WHERE locatie_id = '$evenementLocatieId'";
                if($tabelConn = $conn->query($sql)){
                    $rows = mysqli_num_rows($tabelConn);
                    if($rows >= 1){
                        $sql = "INSERT INTO evenementen (datum, artiest_id, locatie_id, max_bezoekers) 
                        VALUES ('$evenementDatum', '$evenementArtiestId', '$evenementLocatieId', '$evenementMaxBezoekers')";

                        if ($tabelConn = $conn->query($sql)) {
                            $alertEvenement = "Evenement Toegevoegd";
                        } else {
                            $alertEvenement = "Toevoegen Gefaald";
                        }
                    }else{
                        $alertEvenement = "Ongeldig Locatie Id";
                    }
                }
            }else{
                $alertEvenement = "Ongeldig Artiest Id";
            }
        }
    } else {
        $alertEvenement = "Vul een Artiest Id en Locatie Id in";
    }
}

if (isset($_POST['submit-gebruiker-add'])) {
    if (!empty($_POST['gebruiker-gebruikersnaam-add']) && !empty($_POST['gebruiker-wachtwoord-add'])) {
        $gebruikerNaam = safe_string(trim($_POST['gebruiker-gebruikersnaam-add']));
        $gebruikerNaam = $conn->real_escape_string($gebruikerNaam);

        $gebruikerWachtwoord = safe_string($_POST['gebruiker-wachtwoord-add']);
        $gebruikerWachtwoord = $conn->real_escape_string($gebruikerWachtwoord);
        
        if(empty($_POST['gebruiker-toegang-add']) || $_POST['gebruiker-toegang-add'] < 1) {$gebruikerToegang = 1;} else {$gebruikerToegang = $_POST['gebruiker-toegang-add'];}

        $sql = "SELECT username FROM gebruikers WHERE username = '$gebruikerNaam'";
        if($tabelConn = $conn->query($sql)){
            $rows = mysqli_num_rows($tabelConn);
            if($rows < 1){
                $sql = "INSERT INTO gebruikers (username, password, permission) VALUES ('$gebruikerNaam', '$gebruikerWachtwoord', '$gebruikerToegang')";
                if ($tabelConn = $conn->query($sql)) {
                    $alertGebruiker = "Gebruiker Toegevoegd";
                } else {
                    $alertGebruiker = "Toevoegen Gefaald";
                }
            }else{$alertGebruiker = "Gebruikersnaam in gebruik";}
        }
    } else {
        $alertGebruiker = "Gebruikersnaam en Wachtwoord verplicht";
    }
}

if (isset($_POST['submit-locatie-add'])) {
    if (!empty($_POST['locatie-plaatsnaam-add'])) {
        $locatiePlaats = trim($_POST['locatie-plaatsnaam-add']);
        $locatieGebouw = trim($_POST['locatie-gebouw-add']);
        $locatieAdres = trim($_POST['locatie-adres-add']);
        $locatiePostcode = $_POST['locatie-postcode-add'];

        $sql = "INSERT INTO locaties (plaatsnaam, gebouw, adres, postcode) 
        VALUES ('$locatiePlaats', '$locatieGebouw', '$locatieAdres', '$locatiePostcode')";

        if ($tabelConn = $conn->query($sql)) {
            $alertLocatie = "Locatie Toegevoegd";
        } else {
            $alertLocatie = "Toevoegen Gefaald";
        }
    } else {
        $alertLocatie = "Vul een Plaatsnaam in";
    }
}

if (isset($_POST['submit-reactie-add'])) {
    if (!empty($_POST['reactie-titel-add'])) {
        $reactieEvenementId = $_POST['reactie-evenement-id-add'];
        $reactieTitel = trim($_POST['reactie-titel-add']);
        $reactieInhoud = trim($_POST['reactie-inhoud-add']);
        $reactieAuteur = trim($_POST['reactie-auteur-add']);

        $sql = "SELECT evenement_id FROM evenementen WHERE evenement_id = '$reactieEvenementId'";
        if($tabelConn = $conn->query($sql)){
            $rows = mysqli_num_rows($tabelConn);
            if($rows >= 1){
                $sql = "INSERT INTO reacties (evenement_id, titel, inhoud, auteur) 
                VALUES ('$reactieEvenementId', '$reactieTitel', '$reactieInhoud', '$reactieAuteur')";

                if ($tabelConn = $conn->query($sql)) {
                    $alertReactie = "Reactie Toegevoegd";
                } else {
                    $alertReactie = "Toevoegen Gefaald";
                }
            }else{
                $alertReactie = "Ongeldig Evenement Id";
            }
        }
    } else {
        $alertReactie = "Vul een Titel in";
    }
}


//delete
if (isset($_POST['submit-aanbieding-delete'])) {
    $aanbiedingId = $_POST['submit-aanbieding-delete'];
    $sql = "DELETE FROM aanbiedingen WHERE aanbiedingen_id = $aanbiedingId";
    if ($conn->query($sql)) {
        $alertAanbieding = "Aanbieding Verwijderd";
    } else {
        $alertAanbieding = "Verwijderen Gefaald";
    }
}

if (isset($_POST['submit-artiest-delete'])) {
    $artiestId = $_POST['submit-artiest-delete'];
    $sql = "DELETE FROM artiesten WHERE artiest_id = $artiestId";
    if ($conn->query($sql)) {
        $alertArtiest = "Artiest Verwijderd";
    } else {
        $alertArtiest = "Verwijderen Gefaald";
    }
}

if (isset($_POST['submit-evenement-delete'])) {
    $evenementId = $_POST['submit-evenement-delete'];
    $sql = "DELETE FROM evenementen WHERE evenement_id = $evenementId";
    if ($conn->query($sql)) {
        $alertEvenement = "Evenement Verwijderd";
    } else {
        $alertEvenement = "Verwijderen Gefaald";
    }
}

if (isset($_POST['submit-gebruiker-delete'])) {
    $gebruikerId = $_POST['submit-gebruiker-delete'];
    $sql = "DELETE FROM gebruikers WHERE gebruiker_id = $gebruikerId";
    if ($conn->query($sql)) {
        $alertGebruiker = "Gebruiker Verwijderd";
    } else {
        $alertGebruiker = "Verwijderen Gefaald";
    }
}

if (isset($_POST['submit-locatie-delete'])) {
    $locatieId = $_POST['submit-locatie-delete'];
    $sql = "DELETE FROM locaties WHERE locatie_id = $locatieId";
    if ($conn->query($sql)) {
        $alertLocatie = "Locatie Verwijderd";
    } else {
        $alertLocatie = "Verwijderen Gefaald";
    }
}

if (isset($_POST['submit-reactie-delete'])) {
    $reactieId = $_POST['submit-reactie-delete'];
    $sql = "DELETE FROM reacties WHERE reactie_id = $reactieId";
    if ($conn->query($sql)) {
        $alertReactie = "Reactie Verwijderd";
    } else {
        $alertReactie = "Verwijderen Gefaald";
    }
}


//update
if (isset($_POST['submit-aanbieding-update'])) {
    for($i = 0; $i < $_POST['submit-aanbieding-update']; $i++){
        $aanbiedingId = $_POST['aanbieding-id-'.$i];

        $aanbiedingTitel = trim($_POST['aanbieding-titel-'.$i]);
        $aanbiedingBegindatum = $_POST['aanbieding-begindatum-'.$i];
        $aanbiedingEinddatum = $_POST['aanbieding-einddatum-'.$i];
        $aanbiedingOmschrijving = trim($_POST['aanbieding-omschrijving-'.$i]);
        $aanbiedingAfbeelding = trim($_POST['aanbieding-afbeelding-'.$i]);
        $aanbiedingArtiestId = $_POST['aanbieding-artiest-id-'.$i];
        
        $sql = "SELECT artiest_id FROM artiesten WHERE artiest_id = '".$_POST['aanbieding-artiest-id-'.$i]."'";
        if($tabelConn = $conn->query($sql)){
            $rows = mysqli_num_rows($tabelConn);
            if($rows >= 1){
                $geldigArtiest = true;
            }else{
                $geldigArtiest = false;
            }
        }

        $sql = "SELECT * FROM aanbiedingen WHERE aanbiedingen_id = $aanbiedingId";
        if ($tabelConn = $conn->query($sql)) {
            $sqlUpdate = "UPDATE aanbiedingen SET";
            $update = false; 
            
            $row = $tabelConn->fetch_object();
            $titel = $row->titel;
            $begindatum = str_replace(" ", "T", substr($row->begindatum, 0, -3));
            $einddatum = str_replace(" ", "T", substr($row->einddatum, 0, -3));
            $omschrijving = $row->omschrijving;
            $afbeelding = $row->afbeelding;
            $artiest_id = $row->artiest_id;

            if($aanbiedingTitel != $titel && $aanbiedingTitel != NULL){$sqlUpdate = $sqlUpdate." titel = '$aanbiedingTitel'"; $update = true;}
            if($aanbiedingBegindatum != $begindatum){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." begindatum = '$aanbiedingBegindatum'"; $update = true;}
            if($aanbiedingEinddatum != $einddatum){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." einddatum = '$aanbiedingEinddatum'"; $update = true;}
            if($aanbiedingOmschrijving != $omschrijving){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." omschrijving = '$aanbiedingOmschrijving'"; $update = true;}
            if($aanbiedingAfbeelding != $afbeelding){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." afbeelding = '$aanbiedingAfbeelding'"; $update = true;}
            if($aanbiedingArtiestId != $artiest_id && $geldigArtiest){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." artiest_id = '$aanbiedingArtiestId'"; $update = true;}
            if($update){
                $sqlUpdate = $sqlUpdate." WHERE aanbiedingen_id = $aanbiedingId";
                if ($conn->query($sqlUpdate)) {
                    $alertAanbieding = "Aanbiedingen Geüpdatet";
                } else {
                    $alertAanbieding = "Update Gefaald";
                }
            }
        }
    }
}

if (isset($_POST['submit-artiest-update'])) {
    for($i = 0; $i < $_POST['submit-artiest-update']; $i++){
        $artiestId = $_POST['artiest-id-'.$i];

        $artiestNaam = trim($_POST['artiest-naam-'.$i]);
        $artiestAchternaam = trim($_POST['artiest-achternaam-'.$i]);
        $artiestVoornaam = trim($_POST['artiest-voornaam-'.$i]);
        $artiestTussenvoegsel = trim($_POST['artiest-tussenvoegsel-'.$i]);
        $artiestStatement = trim($_POST['artiest-statement-'.$i]);
        $artiestTelefoon = $_POST['artiest-telefoon-'.$i];
        $artiestActief = $_POST['artiest-actief-'.$i];

        $sql = "SELECT * FROM artiesten WHERE artiest_id = $artiestId";
        if ($tabelConn = $conn->query($sql)) {
            $sqlUpdate = "UPDATE artiesten SET";
            $update = false; 
            
            $row = $tabelConn->fetch_object();
            $naam = $row->naam;
            $achternaam = $row->achternaam;
            $voornaam = $row->voornaam;
            $tussenvoegsel = $row->tussenvoegsel;
            $statement = $row->statement;
            $telefoon = $row->telefoon;
            $actief = $row->actief;

            if($artiestNaam != $naam && $artiestNaam != NULL){$sqlUpdate = $sqlUpdate." naam = '$artiestNaam'"; $update = true;}
            if($artiestAchternaam != $achternaam){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." achternaam = '$artiestAchternaam'"; $update = true;}
            if($artiestVoornaam != $voornaam){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." voornaam = '$artiestVoornaam'"; $update = true;}
            if($artiestTussenvoegsel != $tussenvoegsel){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." tussenvoegsel = '$artiestTussenvoegsel'"; $update = true;}
            if($artiestStatement != $statement){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." statement = '$artiestStatement'"; $update = true;}
            if($artiestTelefoon != $telefoon){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." telefoon = '$artiestTelefoon'"; $update = true;}
            if($artiestActief != $actief){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." actief = '$artiestActief'"; $update = true;}
            if($update){
                $sqlUpdate = $sqlUpdate." WHERE artiest_id = $artiestId";
                if ($conn->query($sqlUpdate)) {
                    $alertArtiest = "Artiesten Geüpdatet";
                } else {
                    $alertArtiest = "Update Gefaald";
                }
            }
        }
    }
}

if (isset($_POST['submit-evenement-update'])) {
    for($i = 0; $i < $_POST['submit-evenement-update']; $i++){
        $evenementId = $_POST['evenement-id-'.$i];

        $evenementDatum = $_POST['evenement-datum-'.$i];
        $evenementArtiestId = $_POST['evenement-artiest-id-'.$i];
        $evenementLocatieId = $_POST['evenement-locatie-id-'.$i];
        $evenementMaxBezoekers = $_POST['evenement-max-bezoekers-'.$i];
        
        $sql = "SELECT artiest_id FROM artiesten WHERE artiest_id = '$evenementArtiestId'";
        if($tabelConn = $conn->query($sql)){
            $rows = mysqli_num_rows($tabelConn);
            if($rows >= 1){
                $geldigArtiest = true;
            }else{
                $geldigArtiest = false;
            }
        }

        $sql = "SELECT locatie_id FROM locaties WHERE locatie_id = '$evenementLocatieId'";
        if($tabelConn = $conn->query($sql)){
            $rows = mysqli_num_rows($tabelConn);
            if($rows >= 1){
                $geldigLocatie = true;
            }else{
                $geldigLocatie = false;
            }
        }

        $sql = "SELECT * FROM evenementen WHERE evenement_id = $evenementId";
        if ($tabelConn = $conn->query($sql)) {
            $sqlUpdate = "UPDATE evenementen SET";
            $update = false; 
            
            $row = $tabelConn->fetch_object();
            $datum = str_replace(" ", "T", substr($row->datum, 0, -3));
            $artiest_id = $row->artiest_id;
            $locatie_id = $row->locatie_id;
            $max_bezoekers = $row->max_bezoekers;

            if($evenementDatum != $datum){$sqlUpdate = $sqlUpdate." datum = '$evenementDatum'"; $update = true;}
            if($evenementArtiestId != $artiest_id && $geldigArtiest){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." artiest_id = '$evenementArtiestId'"; $update = true;}
            if($evenementLocatieId != $locatie_id && $geldigLocatie){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." locatie_id = '$evenementLocatieId'"; $update = true;}
            if($evenementMaxBezoekers != $max_bezoekers){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." max_bezoekers = '$evenementMaxBezoekers'"; $update = true;}
            if($update){
                $sqlUpdate = $sqlUpdate." WHERE evenement_id = $evenementId";
                if ($conn->query($sqlUpdate)) {
                    $alertEvenement = "Evenementen Geüpdatet";
                } else {
                    $alertEvenement = "Update Gefaald";
                }
            }
        }
    }
}

if (isset($_POST['submit-gebruiker-update'])) {
    for($i = 0; $i < $_POST['submit-gebruiker-update']; $i++){
        $gebruikerId = $_POST['gebruiker-id-'.$i];

        $gebruikerNaam = safe_string(trim($_POST['gebruiker-gebruikersnaam-'.$i]));
        $gebruikerNaam = $conn->real_escape_string($gebruikerNaam);

        $gebruikerWachtwoord = safe_string($_POST['gebruiker-wachtwoord-'.$i]);
        $gebruikerWachtwoord = $conn->real_escape_string($gebruikerWachtwoord);

        if(empty($_POST['gebruiker-toegang-'.$i]) || $_POST['gebruiker-toegang-'.$i] < 1) {$gebruikerToegang = 1;} else {$gebruikerToegang = $_POST['gebruiker-toegang-'.$i];}

        $sql = "SELECT * FROM gebruikers WHERE gebruiker_id = $gebruikerId";
        if ($tabelConn = $conn->query($sql)) {
            $sqlUpdate = "UPDATE gebruikers SET";
            $update = false; 

            $row = $tabelConn->fetch_object();
            $dbuser = $row->username;
            $dbpass = $row->password;
            $dbpermission = $row->permission;

            if($gebruikerNaam != $dbuser && $gebruikerNaam != NULL){$sqlUpdate = $sqlUpdate." username = '$gebruikerNaam'"; $update = true;}
            if($gebruikerWachtwoord != NULL){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." password = '$gebruikerWachtwoord'"; $update = true;}
            if($gebruikerToegang != $dbpermission){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." permission = $gebruikerToegang"; $update = true;}
            if($update){
                $sqlUpdate = $sqlUpdate." WHERE gebruiker_id = $gebruikerId";
                if ($conn->query($sqlUpdate)) {
                    $alertGebruiker = "Gebruikers Geüpdatet";
                  } else {
                    $alertGebruiker = "Update Gefaald";
                  }
            }
        }
    }
}

if (isset($_POST['submit-locatie-update'])) {
    for($i = 0; $i < $_POST['submit-locatie-update']; $i++){
        $locatieId = $_POST['locatie-id-'.$i];

        $locatiePlaats = trim($_POST['locatie-plaatsnaam-'.$i]);
        $locatieGebouw = trim($_POST['locatie-gebouw-'.$i]);
        $locatieAdres = trim($_POST['locatie-adres-'.$i]);
        $locatiePostcode = $_POST['locatie-postcode-'.$i];

        $sql = "SELECT * FROM locaties WHERE locatie_id = $locatieId";
        if ($tabelConn = $conn->query($sql)) {
            $sqlUpdate = "UPDATE locaties SET";
            $update = false; 
            
            $row = $tabelConn->fetch_object();
            $plaatsnaam = $row->plaatsnaam;
            $gebouw = $row->gebouw;
            $adres = $row->adres;
            $postcode = $row->postcode;

            if($locatiePlaats != $plaatsnaam && $locatiePlaats != NULL){$sqlUpdate = $sqlUpdate." plaatsnaam = '$locatiePlaats'"; $update = true;}
            if($locatieGebouw != $gebouw){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." gebouw = '$locatieGebouw'"; $update = true;}
            if($locatieAdres != $adres){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." adres = '$locatieAdres'"; $update = true;}
            if($locatiePostcode != $postcode){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." postcode = '$locatiePostcode'"; $update = true;}
            if($update){
                $sqlUpdate = $sqlUpdate." WHERE locatie_id = $locatieId";
                if ($conn->query($sqlUpdate)) {
                    $alertLocatie = "Locaties Geüpdatet";
                } else {
                    $alertLocatie = "Update Gefaald";
                }
            }
        }
    }
}

if (isset($_POST['submit-reactie-update'])) {
    for($i = 0; $i < $_POST['submit-reactie-update']; $i++){
        $reactieId = $_POST['reactie-id-'.$i];

        $reactieEvenementId = $_POST['reactie-evenement-id-'.$i];
        $reactieTitel = trim($_POST['reactie-titel-'.$i]);
        $reactieInhoud = trim($_POST['reactie-inhoud-'.$i]);
        $reactieAuteur = trim($_POST['reactie-auteur-'.$i]);

        $sql = "SELECT evenement_id FROM evenementen WHERE evenement_id = '$reactieEvenementId'";
        if($tabelConn = $conn->query($sql)){
            $rows = mysqli_num_rows($tabelConn);
            if($rows >= 1){
                $geldigEvenement = true;
            }else{
                $geldigEvenement = false;
            }
        }

        $sql = "SELECT * FROM reacties WHERE reactie_id = $reactieId";
        if ($tabelConn = $conn->query($sql)) {
            $sqlUpdate = "UPDATE reacties SET";
            $update = false; 
            
            $row = $tabelConn->fetch_object();
            $evenement_id = $row->evenement_id;
            $titel = $row->titel;
            $inhoud = $row->inhoud;
            $auteur = $row->auteur;

            if($reactieEvenementId != $evenement_id && $geldigEvenement){$sqlUpdate = $sqlUpdate." evenement_id = '$reactieEvenementId'"; $update = true;}
            if($reactieTitel != $titel && $reactieTitel != NULL){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." titel = '$reactieTitel'"; $update = true;}
            if($reactieInhoud != $inhoud){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." inhoud = '$reactieInhoud'"; $update = true;}
            if($reactieAuteur != $auteur){if($update){$sqlUpdate = $sqlUpdate.",";} $sqlUpdate = $sqlUpdate." auteur = '$reactieAuteur'"; $update = true;}
            if($update){
                $sqlUpdate = $sqlUpdate." WHERE reactie_id = $reactieId";
                if ($conn->query($sqlUpdate)) {
                    $alertReactie = "Reacties Geüpdatet";
                } else {
                    $alertReactie = "Update Gefaald";
                }
            }
        }
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
        <link rel="stylesheet" type="text/css" href="css/bewerken2.css">
        <script type="text/javascript" src="js/burger-menu.js"></script>
        <title>One-Up</title>
    </head>

    <body>
        <?php get_header(); ?>

        <main class="container">
            
            <div class="form-container" id="aanbiedingen">
                <h1 class="form__h1">Beheer Aanbiedingen</h1>
                <form method="POST" action="bewerken2.php#aanbiedingen">
                    <table>
                        <tr><?php echo "<strong>$alertAanbieding</strong>"; ?></tr>
                        <tr><th>Id</th><th>Titel</th><th>Begindatum</th><th>Einddatum</th><th>Omschrijving</th><th>Afbeelding</th><th>Artiest Id</th></tr>
                        <?php
                        $rijNummer = 0;
                        $sql = "SELECT * FROM aanbiedingen";
                        if ($tabelConn = $conn->query($sql)) {
                            while ($row = $tabelConn->fetch_object()) {
                                $aanbiedingen_id = $row->aanbiedingen_id;
                                $titel = $row->titel;
                                $begindatum = str_replace(" ", "T", substr($row->begindatum, 0, -3));
                                $einddatum = str_replace(" ", "T", substr($row->einddatum, 0, -3));
                                $afbeelding = $row->afbeelding;
                                $omschrijving = $row->omschrijving;
                                $artiest_id = $row->artiest_id;
                                
                                echo '
                                <tr>
                                    <td><input type="number" name="aanbieding-id-'.$rijNummer.'" value="'.$aanbiedingen_id.'" readonly></td>
                                    <td><input type="text" name="aanbieding-titel-'.$rijNummer.'" value="'.$titel.'"></td>
                                    <td><input type="datetime-local" name="aanbieding-begindatum-'.$rijNummer.'" value="'.$begindatum.'" min="0001-01-01"></td>
                                    <td><input type="datetime-local" name="aanbieding-einddatum-'.$rijNummer.'" value="'.$einddatum.'" min="0001-01-01"></td>
                                    <td><input type="text" name="aanbieding-omschrijving-'.$rijNummer.'" value="'.$omschrijving.'"></td>
                                    <td><input type="text" name="aanbieding-afbeelding-'.$rijNummer.'" value="'.$afbeelding.'"></td>
                                    <td><input type="number" name="aanbieding-artiest-id-'.$rijNummer.'" value="'.$artiest_id.'"></td>
                                    <td><button name="submit-aanbieding-delete" value="'.$aanbiedingen_id.'">Verwijder</button></td>
                                </tr>';
                                $rijNummer++;
                            }
                            $tabelConn->close();
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td><input type="text" name="aanbieding-titel-add"></td>
                            <td><input type="datetime-local" name="aanbieding-begindatum-add" min="0000-01-01"></td>
                            <td><input type="datetime-local" name="aanbieding-einddatum-add" min="0000-01-01"></td>
                            <td><input type="text" name="aanbieding-omschrijving-add"></td>
                            <td><input type="text" name="aanbieding-afbeelding-add"></td>
                            <td><input type="number" name="aanbieding-artiest-id-add"></td>
                            <td><button name="submit-aanbieding-add">Toevoegen</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button name="submit-aanbieding-update" class="update-knop" value="<?php echo $rijNummer ?>">Update</button></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="form-container" id="artiesten">
                <h1 class="form__h1">Beheer Artiesten</h1>
                <form method="POST" action="bewerken2.php#artiesten">
                    <table>
                        <tr><?php echo "<strong>$alertArtiest</strong>"; ?></tr>
                        <tr><th>Id</th><th>Naam</th><th>Achternaam</th><th>Voornaam</th><th>Tussenvoegsel</th><th>Statement</th><th>Telefoon</th><th>Actief</th></tr>
                        <?php
                        $rijNummer = 0;
                        $sql = "SELECT * FROM artiesten";
                        if ($tabelConn = $conn->query($sql)) {
                            while ($row = $tabelConn->fetch_object()) {
                                $artiest_id = $row->artiest_id;
                                $naam = $row->naam;
                                $achternaam = $row->achternaam;
                                $voornaam = $row->voornaam;
                                $tussenvoegsel = $row->tussenvoegsel;
                                $statement = $row->statement;
                                $telefoon = $row->telefoon;
                                $actief = $row->actief;
                                
                                echo '
                                <tr>
                                    <td><input type="number" name="artiest-id-'.$rijNummer.'" value="'.$artiest_id.'" readonly></td>
                                    <td><input type="text" name="artiest-naam-'.$rijNummer.'" value="'.$naam.'"></td>
                                    <td><input type="text" name="artiest-achternaam-'.$rijNummer.'" value="'.$achternaam.'"></td>
                                    <td><input type="text" name="artiest-voornaam-'.$rijNummer.'" value="'.$voornaam.'"></td>
                                    <td><input type="text" name="artiest-tussenvoegsel-'.$rijNummer.'" value="'.$tussenvoegsel.'"></td>
                                    <td><input type="text" name="artiest-statement-'.$rijNummer.'" value="'.$statement.'"></td>
                                    <td><input type="text" name="artiest-telefoon-'.$rijNummer.'" value="'.$telefoon.'"></td>
                                    <td><input type="number" name="artiest-actief-'.$rijNummer.'" value="'.$actief.'"></td>
                                    <td><button name="submit-artiest-delete" value="'.$artiest_id.'">Verwijder</button></td>
                                </tr>';
                                $rijNummer++;
                            }
                            $tabelConn->close();
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td><input type="text" name="artiest-naam-add"></td>
                            <td><input type="text" name="artiest-achternaam-add"></td>
                            <td><input type="text" name="artiest-voornaam-add"></td>
                            <td><input type="text" name="artiest-tussenvoegsel-add"></td>
                            <td><input type="text" name="artiest-statement-add"></td>
                            <td><input type="text" name="artiest-telefoon-add"></td>
                            <td><input type="number" name="artiest-actief-add"></td>
                            <td><button name="submit-artiest-add">Toevoegen</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button name="submit-artiest-update" class="update-knop" value="<?php echo $rijNummer ?>">Update</button></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="form-container" id="evenementen">
                <h1 class="form__h1">Beheer Evenementen</h1>
                <form method="POST" action="bewerken2.php#evenementen">
                    <table>
                        <tr><?php echo "<strong>$alertEvenement</strong>"; ?></tr>
                        <tr><th>Id</th><th>Datum</th><th>Artiest Id</th><th>Locatie Id</th><th>Max Bezoekers</th></tr>
                        <?php
                        $rijNummer = 0;
                        $sql = "SELECT * FROM evenementen";
                        if ($tabelConn = $conn->query($sql)) {
                            while ($row = $tabelConn->fetch_object()) {
                                $evenement_id = $row->evenement_id;
                                $datum = str_replace(" ", "T", substr($row->datum, 0, -3));
                                $artiest_id = $row->artiest_id;
                                $locatie_id = $row->locatie_id;
                                $max_bezoekers = $row->max_bezoekers;
                                
                                echo '
                                <tr>
                                    <td><input type="number" name="evenement-id-'.$rijNummer.'" value="'.$evenement_id.'" readonly></td>
                                    <td><input type="datetime-local" name="evenement-datum-'.$rijNummer.'" value="'.$datum.'" min="0001-01-01"></td>
                                    <td><input type="number" name="evenement-artiest-id-'.$rijNummer.'" value="'.$artiest_id.'"></td>
                                    <td><input type="number" name="evenement-locatie-id-'.$rijNummer.'" value="'.$locatie_id.'"></td>
                                    <td><input type="number" name="evenement-max-bezoekers-'.$rijNummer.'" value="'.$max_bezoekers.'"></td>
                                    <td><button name="submit-evenement-delete" value="'.$evenement_id.'">Verwijder</button></td>
                                </tr>';
                                $rijNummer++;
                            }
                            $tabelConn->close();
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td><input type="datetime-local" name="evenement-datum-add" min="0000-01-01"></td>
                            <td><input type="number" name="evenement-artiest-id-add"></td>
                            <td><input type="number" name="evenement-locatie-id-add"></td>
                            <td><input type="number" name="evenement-max-bezoekers-add"></td>
                            <td><button name="submit-evenement-add">Toevoegen</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button name="submit-evenement-update" class="update-knop" value="<?php echo $rijNummer ?>">Update</button></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="form-container" id="gebruikers">
                <h1 class="form__h1">Beheer gebruikers</h1>
                <form method="POST" action="bewerken2.php#gebruikers">
                    <table>
                        <tr><?php echo "<strong>$alertGebruiker</strong>"; ?></tr>
                        <tr><th>Id</th><th>Gebruikersnaam</th><th>Wachtwoord</th><th>Level van Toegang</th></tr>
                        <?php
                        $rijNummer = 0;
                        $sql = "SELECT * FROM gebruikers";
                        if ($tabelConn = $conn->query($sql)) {
                            while ($row = $tabelConn->fetch_object()) {
                                $dbid = $row->gebruiker_id;
                                $dbuser = $row->username;
                                $dbpass = $row->password;
                                $dbpermission = $row->permission;
                                echo '
                                <tr>
                                    <td><input type="number" name="gebruiker-id-'.$rijNummer.'" value="'.$dbid.'" readonly></td>
                                    <td><input type="text" name="gebruiker-gebruikersnaam-'.$rijNummer.'" value="'.$dbuser.'"></td>
                                    <td><input type="password" name="gebruiker-wachtwoord-'.$rijNummer.'" value=""></td>
                                    <td><input type="number" name="gebruiker-toegang-'.$rijNummer.'" value="'.$dbpermission.'"></td>
                                    <td><button name="submit-gebruiker-delete" value="'.$dbid.'">Verwijder</button></td>
                                </tr>';
                                $rijNummer++;
                            }
                            $tabelConn->close();
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td><input type="text" name="gebruiker-gebruikersnaam-add"></td>
                            <td><input type="password" name="gebruiker-wachtwoord-add"></td>
                            <td><input type="number" name="gebruiker-toegang-add"></td>
                            <td><button name="submit-gebruiker-add">Toevoegen</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button name="submit-gebruiker-update" class="update-knop" value="<?php echo $rijNummer ?>">Update</button></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="form-container" id="locaties">
                <h1 class="form__h1">Beheer Locaties</h1>
                <form method="POST" action="bewerken2.php#locaties">
                    <table>
                        <tr><?php echo "<strong>$alertLocatie</strong>"; ?></tr>
                        <tr><th>Id</th><th>Plaatsnaam</th><th>Gebouw</th><th>Adres</th><th>Postcode</th></tr>
                        <?php
                        $rijNummer = 0;
                        $sql = "SELECT * FROM locaties";
                        if ($tabelConn = $conn->query($sql)) {
                            while ($row = $tabelConn->fetch_object()) {
                                $locatie_id = $row->locatie_id;
                                $plaatsnaam = $row->plaatsnaam;
                                $gebouw = $row->gebouw;
                                $adres = $row->adres;
                                $postcode = $row->postcode;
                                
                                echo '
                                <tr>
                                    <td><input type="number" name="locatie-id-'.$rijNummer.'" value="'.$locatie_id.'" readonly></td>
                                    <td><input type="text" name="locatie-plaatsnaam-'.$rijNummer.'" value="'.$plaatsnaam.'"></td>
                                    <td><input type="text" name="locatie-gebouw-'.$rijNummer.'" value="'.$gebouw.'"></td>
                                    <td><input type="text" name="locatie-adres-'.$rijNummer.'" value="'.$adres.'"></td>
                                    <td><input type="text" name="locatie-postcode-'.$rijNummer.'" value="'.$postcode.'"></td>
                                    <td><button name="submit-locatie-delete" value="'.$locatie_id.'">Verwijder</button></td>
                                </tr>';
                                $rijNummer++;
                            }
                            $tabelConn->close();
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td><input type="text" name="locatie-plaatsnaam-add"></td>
                            <td><input type="text" name="locatie-gebouw-add"></td>
                            <td><input type="text" name="locatie-adres-add"></td>
                            <td><input type="text" name="locatie-postcode-add"></td>
                            <td><button name="submit-locatie-add">Toevoegen</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button name="submit-locatie-update" class="update-knop" value="<?php echo $rijNummer ?>">Update</button></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="form-container" id="reacties">
                <h1 class="form__h1">Beheer Reacties</h1>
                <form method="POST" action="bewerken2.php#reacties">
                    <table>
                        <tr><?php echo "<strong>$alertReactie</strong>"; ?></tr>
                        <tr><th>Id</th><th>Evenement Id</th><th>Titel</th><th>Inhoud</th><th>Auteur</th></tr>
                        <?php
                        $rijNummer = 0;
                        $sql = "SELECT * FROM reacties";
                        if ($tabelConn = $conn->query($sql)) {
                            while ($row = $tabelConn->fetch_object()) {
                                $reactie_id = $row->reactie_id;
                                $evenement_id = $row->evenement_id;
                                $titel = $row->titel;
                                $inhoud = $row->inhoud;
                                $auteur = $row->auteur;
                                
                                echo '
                                <tr>
                                    <td><input type="number" name="reactie-id-'.$rijNummer.'" value="'.$reactie_id.'" readonly></td>
                                    <td><input type="number" name="reactie-evenement-id-'.$rijNummer.'" value="'.$evenement_id.'"></td>
                                    <td><input type="text" name="reactie-titel-'.$rijNummer.'" value="'.$titel.'"></td>
                                    <td><input type="text" name="reactie-inhoud-'.$rijNummer.'" value="'.$inhoud.'"></td>
                                    <td><input type="text" name="reactie-auteur-'.$rijNummer.'" value="'.$auteur.'"></td>
                                    <td><button name="submit-reactie-delete" value="'.$reactie_id.'">Verwijder</button></td>
                                </tr>';
                                $rijNummer++;
                            }
                            $tabelConn->close();
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td><input type="number" name="reactie-evenement-id-add"></td>
                            <td><input type="text" name="reactie-titel-add"></td>
                            <td><input type="text" name="reactie-inhoud-add"></td>
                            <td><input type="text" name="reactie-auteur-add"></td>
                            <td><button name="submit-reactie-add">Toevoegen</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button name="submit-reactie-update" class="update-knop" value="<?php echo $rijNummer ?>">Update</button></td>
                        </tr>
                    </table>
                </form>
            </div>
            <a class="bewerken-button" href="bewerken.php" style="margin: 30px;">Database bewerken 1.0</a>
        </main>

        <?php get_footer(); ?>
    </body>
</html>