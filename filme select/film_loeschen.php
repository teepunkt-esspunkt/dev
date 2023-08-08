<?php
require_once 'functions.php';

/** @var int $loeschid ID des geschriebenen Datensatzes */
$loeschid = !empty($_GET['loeschid']) ? intval(trim($_GET['loeschid'])) : 0;

/** @var string $loeschok  Löschbestätigung */
$loeschok = $_GET['loeschok'] ?? false;


/** @var string $ergebnis  Fehlermeldung */
$ergebnis = '';

/** @var string[] $film Daten des gespeicherten Films */
$film = [];

// Wenn gültige Datensatz-ID übergeben wurde
if(0 < $loeschid) {
    
    // Verbindung zur Datenbank aufbauen
    $db = dbConnect(); 

    // Prüfen, ob Löschbestätigung gegeben wurde
    if(!$loeschok) {
        /*
         * Erfasste Daten aus der Datenbank lesen,
         * wenn noch keine Löschbestätigung gegeben wurde
         */
        // SQL-Statement erzeugen
        $sql = <<<EOT
            SELECT id, 
                   titel,
                   SUBSTR(inhalt,1,40) AS inhalt, 
                   land, 
                   DATE_FORMAT(premiere, '%d.%m.%Y') AS premiere, 
                   fsk, 
                   SEC_TO_TIME(laufzeit) AS laufzeit
            FROM filme 
            WHERE id = $loeschid
EOT;

        // SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
        if($result = mysqli_query($db, $sql)) {
            // Den ersten (und einzigen) Datensatz aus dem Resultset holen
            if($film = mysqli_fetch_assoc($result)) {
                // Felder für die Ausgabe in HTML-Seite vorbereiten
                foreach($film as $key => $value) {
                    $film[$key] = htmlspecialchars($value);
                }
            }

            // Resultset freigeben
            mysqli_free_result($result);
        }
        else {
            die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
        }
    }
    // Lösch-Bestätigung erhalten
    else {
        /*
         * Datensatz löschen
         */
        // SQL-Statement erzeugen
        $sql = "DELETE FROM filme WHERE id = $loeschid"; // WHERE NICHT VERGESSEN!!!
        
        // Statement an die DB schicken
        mysqli_query($db, $sql) || die('DB-Fehler');
        
        // Verbindung zur Datenbank trennen
        mysqli_close($db);
        
        // Weiterleiten auf Bestätigungsseite
        header("location: film_loeschen_ok.php");
        
    }

    // Verbindung zur Datenbank trennen
    mysqli_close($db);
}
elseif(!$loeschid) {
    // Datensatz-ID wurde nicht übergeben
    $ergebnis = 'Datensatz-ID fehlt!';
}
else {
    // Datensatz mit dieser ID existiert nicht
    $ergebnis = 'Ungültige Datensatz-ID!';
}

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Film löschen</title>
        <meta charset="UTF-8">
        <link href="styles.css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <h1>Film löschen!</h1>
            <?php if($ergebnis): ?>
            <h3><span><?= $ergebnis ?></span></h3>
            
            
            <?php else: ?>
            <h4><span>Soll dieser Film wirklich gelöscht werden?</span></h4>
            
            <table>
                <?php foreach($film as $name => $wert): ?>
                <tr>
                    <th><?= ucfirst($name) ?></th>
                    <td><?= $wert ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
                <input type="hidden" name="loeschid" value="<?= $film['id'] ?>">
                <div class="center">
                    <button type="submit" name="loeschok" value="1">löschen</button>
                </div>
            </form>
            
            <?php endif; ?>
            <h3><a href="filmliste.php">zurück zur Filmliste</a></h3>
        </div>
    </body>
</html>
