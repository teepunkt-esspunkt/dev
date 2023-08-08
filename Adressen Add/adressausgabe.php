<?php
require_once 'functions.php';

/** @var string[] $adressen alle Adressen */
$adressen = [];

// 1. Datenbank öffnen
$db = mysqli_connect(DBSERVER, DBUSER, DBPASSWD, DBNAME);

// 2. SQL-Statement erzeugen
$sql = <<<EOT
    SELECT id,
           IF(anrede='w','Frau','Herr') AS anrede,
           nachname,
           vorname,
           plz,
           ort,
           telefon,
           DATE_FORMAT(geburtsdatum, '%d.%m.%Y') as geburtsdatum
    FROM adressen 
EOT;

// 3. SQL-Statement an DB schicken und Ergebnis (Resultset) speichern
$result = mysqli_query($db, $sql);

// 4. Alle Datensätze aus dem Resultset holen
while($adresse = mysqli_fetch_assoc($result)) {
    $adressen[] = $adresse;
}

// 5. Schließen der DB
mysqli_close($db);

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Adressliste</title>
        <link href="adressen.css" rel="stylesheet">
    </head>
    <body>
        <h1>Adressliste</h1>
        <table>
            <tr>
                <?php foreach(array_keys($adressen[0]) as $key): ?>
                <th><?= ucfirst($key) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach($adressen as $adresse): ?>
            <tr>
                <?php foreach($adresse as $wert): ?>
                <td><?= $wert ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </table>
        <h4><a href="adressen.php">Adresse erfassen</a></h4>
    </body>
</html>
