<?php
require_once 'functions.php';

/** @var int $id ID des geschriebenen Datensatzes */
$id = !empty($_GET['id']) ? intval(trim($_GET['id'])) : 0;

/** @var string[] $film Daten des gespeicherten Films */
$film = [];

// Daten holen, wenn ID übergeben wurde
if ($id) {
    /*
     * Erfasste Daten aus der Datenbank lesen
     */
    // Verbindung zur Datenbank aufbauen
    $db = dbConnect();

    // SQL-Statement erzeugen
    $sql = <<<EOT
        SELECT titel,
               SUBSTR(inhalt,1,70) as inhalt, 
               land, 
               DATE_FORMAT(premiere, '%d.%m.%Y') AS premiere,
               fsk, 
               SEC_TO_TIME(laufzeit) AS laufzeit,
            plakat
        FROM filme 
        WHERE id = $id
EOT;

    // SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
    if ($result = mysqli_query($db, $sql)) {
        // Den ersten (und einzigen) Datensatz aus dem Resultset holen
        if ($film = mysqli_fetch_assoc($result)) {
            // Felder für die Ausgabe in HTML-Seite vorbereiten
            foreach ($film as $key => $value) {
                $film[$key] = htmlspecialchars($value);
            }
        }

        // Resultset freigeben
        mysqli_free_result($result);
    } else {
        die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
    }

    // Verbindung zur Datenbank trennen
    mysqli_close($db);
}

$ausgabe['titel'] = 'Aendern OK';
include TEMPLATES . 'htmlkopf.phtml';
?>

<?php if ($film): ?>
                <table>
                    <caption>Sie haben folgende Daten eingegeben:</caption>
    <?php foreach ($film as $key => $value): ?>
                        <tr>
                            <th><?= ucfirst($key) ?></th>
        <?php if ('plakat' == $key): ?>
                                <td><img src="<?= BILDER . $value ?>" class="klein" alt="<?= $value ?>"></td>
                            <?php else: ?>
                                <td><?= $value ?></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                </table>
                <?php endif; ?>
            <h3><a href="filmliste.php">Filmliste anzeigen</a></h3>
       <?php
include TEMPLATES . 'htmlfuss.phtml';