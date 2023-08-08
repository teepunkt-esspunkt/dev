<?php
require_once 'functions.php';

// ID aus der URL holen
$id = !empty($_GET['id']) ? intval($_GET['id']) : 0;

// Daten für die anzuzeigende Adresse
$film = [];

if($id > 0) {
    // 1. Datenbank öffnen
    $db = dbconnect();

    // 2. SQL-Statement erzeugen 
    $sql = <<<EOT
        SELECT titel,
               inhalt,
               land,
               DATE_FORMAT(premiere, '%d.%m.%Y') as premiere,
               fsk,
               SEC_TO_TIME(laufzeit) AS laufzeit
        FROM filme 
        WHERE id = $id
EOT;
    // 3. SQL-Statement an DB schicken und Ergebnis (Resultset) speichern
    $result = mysqli_query($db, $sql);
    
    // 4. Ersten (und einzigen) Datensatz aus dem Resultset holen
    $film = mysqli_fetch_assoc($result);
    
    // 5. Schließen der DB
    mysqli_close($db);
    
}


?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Filmerfassung</title>
        <meta charset="UTF-8">
        <link href="styles.css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <h1>Vielen Dank</h1>
            <?php if(is_array($film)): ?>
            <table>
                <caption>Sie haben folgende Daten eingegeben:</caption>
                    <?php foreach($film as $key => $value): ?>
                    <tr>
                        <th><?= ucfirst($key) ?></th>
                        <td><?= $value ?></td>
                    </tr>
                    <?php endforeach; ?>
            </table>
            <?php endif; ?>
            <h3><a href="filmliste.php">Filmliste anzeigen</a></h3>
        </div>
    </body>
</html>
