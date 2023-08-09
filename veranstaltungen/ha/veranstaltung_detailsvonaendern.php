<?php
require_once 'functions.php';
require_once 'functions_veranstaltungen.php';

/** @var string[] $form alle Formularfelder */
$form = [];

//** @var string $updateid  ID des zu ändernden Datensatzes */
$updateid = !empty($_GET['detailsid']) ? intval(trim($_GET['detailsid'])) : 0;

/** @var string[] $orte  Array mit allen Orten (Locations) */
$orte = getOrte();

/*
 *  Prüfen, ob ID zum Ändern übergeben wurde und ob ID korrekt ist
 */
if (0 < $detailsid && veranstaltungExist($detailsid)) {
    // ID zum Ändern wurde übergeben.
    // Datensatz aus DB lesen und zur Anzeige vorbereiten
    // Verbindung zur Datenbank aufbauen
    $db = dbConnect();

    // SQL-Statement erzeugen
    $sql = <<<EOT
        SELECT veranstaltungen.vid,
           veranstaltungen.name,
           orte.ort,
           orte.plz,
           DATE_FORMAT(datum, '%d.%m.%Y') AS datumm,
           CONCAT(LEFT(beschreibung, 80), IF(CHAR_LENGTH(beschreibung)>80,'...','')) AS beschreibung,
           orte.adresse,
           orte.stadt       
    FROM veranstaltungen
    LEFT JOIN orte ON veranstaltungen.oid = orte.oid
        WHERE vid = $detailsid
EOT;

    // SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
    if ($result = mysqli_query($db, $sql)) {
        // den ersten (und einzigen) Datensatz aus dem Resultset holen
        if ($form = mysqli_fetch_assoc($result)) {
            // Felder für die Ausgabe in HTML-Seite vorbereiten
            foreach ($form as $key => $value) {
                $form[$key] = htmlspecialchars($value);
            }
        }

        // Resultset freigeben
        mysqli_free_result($result);
    } else {
        die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
    }
}

    /*
     * Wenn Fehler in Formularfeldern gefunden
     */ 
    else {
        // Formularfelder für die Ausgabe im Formular vorbereiten
        foreach ($form as $key => $value) {
            if (is_string($value)) {
                $form[$key] = htmlspecialchars($value);
            }
        }
    }


include TEMPLATES . 'details.phtml';