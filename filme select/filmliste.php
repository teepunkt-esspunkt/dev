<?php
require_once 'functions.php';

// Parameter einlesen
$sort     = $_GET['sort']  ?? 'id';
$suche    = isset($_GET['suche']) ? trim($_GET['suche']) : '';
$richtung = $_GET['richtung'] ?? 'ASC';

/** @var string[] $filme alle Filme */
$filme = [];

// 1. Datenbank öffnen
$db = dbconnect();

// Suchbegriff für die Benutzung in SQL escapen
$suchesql = mysqli_real_escape_string($db, $suche);


// SQL-Statement für die Gesamtzahl der Treffer
$sql = "SELECT COUNT(*) AS anzahl FROM filme WHERE titel LIKE '%$suchesql%'";

// Trefferanzahl ermitteln
$result = mysqli_query($db, $sql);
$treffer = mysqli_fetch_assoc($result);

// Wert für Sortierspalte prüfen
$sortfelder = ['id', 'titel', 'inhalt', 'land', 'premiere', 'fsk', 'laufzeit'];
if(!in_array($sort, $sortfelder)) {
    $sort = 'id';
}

// Wert für Sortierrichtung prüfen
$richtung = 'DESC' == $richtung ? 'DESC' : 'ASC';

// 2. SQL-Statement erzeugen
$sql = <<<EOT
    SELECT id,
           titel,
           CONCAT(LEFT(inhalt, 40), IF(CHAR_LENGTH(inhalt)>40,'...','')) AS inhalt,
           land,
           DATE_FORMAT(premiere, '%d.%m.%Y') AS premiere,
           fsk,
           SEC_TO_TIME(laufzeit) AS laufzeit
    FROM filme 
    WHERE titel LIKE '%$suchesql%'
    ORDER BY filme.$sort $richtung
    LIMIT 20
EOT;

// 3. SQL-Statement an DB schicken und Ergebnis (Resultset) speichern
$result = mysqli_query($db, $sql);

// 4. Alle Datensätze aus dem Resultset holen
while($film = mysqli_fetch_assoc($result)) {
    $filme[] = $film;
}

// 5. Schließen der DB
mysqli_close($db);

// Suchetext für die Benutzung in HTML escapen
$suchehtml = htmlspecialchars($suche);

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Filmliste</title>
        <link href="styles.css" rel="stylesheet">
    </head>
    <body>
        <h1>Filmliste</h1>
        <h3><?= $treffer['anzahl'] ?> Film(e) gefunden</h3>
        <table>
            <caption>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
                    <input type="hidden" name="sort" value="<?= $sort ?>">
                    <input type="hidden" name="richtung" value="<?= $richtung ?>">
                    <label for="suche">Suche nach</label>
                    <input type="text" name="suche" id="suche" value="<?= $suchehtml ?>">
                    <button type="submit" name="suchbutton" value="42">suchen</button>
                </form>
            </caption>
            <tr>
                <th><a href="<?= $_SERVER['PHP_SELF'] ?>?sort=id&suche=<?= $suchehtml ?>&richtung=<?= $sort == 'id' && $richtung == 'ASC' ? 'DESC' : 'ASC' ?>">ID</a></th>
                <th><a href="<?= $_SERVER['PHP_SELF'] ?>?sort=titel&suche=<?= $suchehtml ?>&richtung=<?= $sort == 'titel' && $richtung == 'ASC' ? 'DESC' : 'ASC' ?>">Titel</a></th>
                <th><a href="<?= $_SERVER['PHP_SELF'] ?>?sort=inhalt&suche=<?= $suchehtml ?>&richtung=<?= $sort == 'inhalt' && $richtung == 'ASC' ? 'DESC' : 'ASC' ?>">Inhalt</a></th>
                <th><a href="<?= $_SERVER['PHP_SELF'] ?>?sort=land&suche=<?= $suchehtml ?>&richtung=<?= $sort == 'land' && $richtung == 'ASC' ? 'DESC' : 'ASC' ?>">Land</a></th>
                <th><a href="<?= $_SERVER['PHP_SELF'] ?>?sort=premiere&suche=<?= $suchehtml ?>&richtung=<?= $sort == 'premiere' && $richtung == 'ASC' ? 'DESC' : 'ASC' ?>">Premiere</a></th>
                <th><a href="<?= $_SERVER['PHP_SELF'] ?>?sort=fsk&suche=<?= $suchehtml ?>&richtung=<?= $sort == 'fsk' && $richtung == 'ASC' ? 'DESC' : 'ASC' ?>">FSK</a></th>
                <th><a href="<?= $_SERVER['PHP_SELF'] ?>?sort=laufzeit&suche=<?= $suchehtml ?>&richtung=<?= $sort == 'laufzeit' && $richtung == 'ASC' ? 'DESC' : 'ASC' ?>">Laufzeit</a></th>
                <th colspan="2">&nbsp;</th>
            </tr>
            <?php foreach($filme as $film): ?>
            <tr>
                <?php foreach($film as $wert): ?>
                <td><?= $wert ?></td>
                <?php endforeach; ?>
                <td><a href="film_aendern.php?updateid=<?= $film['id'] ?>">ändern</a></td>
                <td><a href="film_loeschen.php?loeschid=<?= $film['id'] ?>">löschen</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>
