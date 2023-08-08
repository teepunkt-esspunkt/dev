<?php
require_once 'functions.php';

// ID aus der URL holen
$id = !empty($_GET['id']) ? intval($_GET['id']) : 0;

// Daten für die anzuzeigende Adresse
$adresse = [];

if($id > 0) {
    // 1. Datenbank öffnen
    $db = mysqli_connect(DBSERVER, DBUSER, DBPASSWD, DBNAME);

    // 2. SQL-Statement erzeugen 
    $sql = <<<EOT
        SELECT IF(anrede='w','Frau','Herr') AS anrede,
               vorname,
               nachname,
               plz,
               ort,
               telefon,
               DATE_FORMAT(geburtsdatum, '%d.%m.%Y') as geburtsdatum
        FROM adressen 
        WHERE id = $id
EOT;
    
    // 3. SQL-Statement an DB schicken und Ergebnis (Resultset) speichern
    $result = mysqli_query($db, $sql);
    
    // 4. Ersten (und einzigen) Datensatz aus dem Resultset holen
    $adresse = mysqli_fetch_assoc($result);
    
    // 5. Schließen der DB
    mysqli_close($db);
    
    // Anrede formatieren
    // $adresse['anrede'] = 'w' == $adresse['anrede'] ? 'Frau' : 'Herr';
    
    // Geburtsdatum formatieren
    // $form['geburtsdatum'] = date_format(date_create_from_format('Y-m-d', $form['geburtsdatum']), 'd.m.Y');
}


?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Adresserfassung</title>
        <meta charset="UTF-8">
        <link href="adressen.css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <h1>Vielen Dank für Ihre Anmeldung</h1>
            <table>
                <caption>Sie haben folgende Daten eingegeben:</caption>
                <?php foreach($adresse as $key => $value): ?>
                <tr>
                    <th><?= ucfirst($key) ?></th>
                    <td><?= $value ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <h3><a href="adressen.php">Weitere Adresse erfassen</a></h3>
            <h3><a href="adressausgabe.php">Adressliste anzeigen</a></h3>
        </div>
    </body>
</html>
