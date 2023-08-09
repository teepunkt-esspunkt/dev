<?php
//session_start();
require_once 'functions.php';
require_once 'functions_veranstaltungen.php';

/** @var string[] $form alle Formularfelder */
$form = [];

//** @var string $updateid  ID des zu ändernden Datensatzes */
$updateid = !empty($_GET['updateid']) ? intval(trim($_GET['updateid'])) : 0;

/** @var string[] $orte  Array mit allen Orten (Locations) */
$orte = getOrte();


/*
 *  Prüfen, ob ID zum Ändern übergeben wurde und ob ID korrekt ist
 */
if (0 < $updateid && veranstaltungExist($updateid)) {
    // ID zum Ändern wurde übergeben.
    // Datensatz aus DB lesen und zur Anzeige vorbereiten
    // Verbindung zur Datenbank aufbauen
    $db = dbConnect();

    // SQL-Statement erzeugen
    $sql = <<<EOT
        SELECT vid,
            name,
            beschreibung,
            datum,
            oid
        FROM veranstaltungen 
        WHERE vid = $updateid
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
 * Prüfen, ob Formular abgeschickt wurde
 * Falls ja, dann weitere Prüfungen durchführen
 */ else {
    /** @var string[] $fehler Fehlermeldungen für Formularfelder */
    $fehler = [];

    /*
     * Werte sämtlicher Formularfelder holen
     */
    $form['vid']                = !empty($_POST['vid']) ? intval(trim($_POST['vid'])) : 0;
    $form['name']               = !empty($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
    $form['beschreibung']       = !empty($_POST['beschreibung']) ? trim(strip_tags($_POST['beschreibung'])) : '';
    $form['oid']                = !empty($_POST['oid']) ? trim(strip_tags($_POST['oid'])) : '';
    $form['datum']              = !empty($_POST['datum']) ? trim(strip_tags($_POST['datum'])) : '';


     // ID prüfen
    if (0 >= $form['vid']) {
        $fehler['vid'] = 'Kein Datensatz!';
    } elseif (!veranstaltungExist($form['vid'])) {
        $fehler['vid'] = 'Datensatz nicht gefunden!';
    }
    
    // Veranstaltungsname prüfen
   if (strlen($form['name']) < 2) {
        $fehler['name'] = 'Veranstaltungsname muss mindestens 2 Zeichen lang sein';
    }
    
   

    // Beschreibung prüfen
    if (strlen($form['name']) > 10000) {
        $fehler['name'] = 'Beschreibung darf höchstens 10.000 Zeichen lang sein';
    }

    // Veranstaltungsort prüfen
    if (!$form['oid']) {
        $fehler['oid'] = 'Bitte Veranstaltungsort auswählen';
    }

    // Tag der Veranstaltung prüfen
    if ($form['datum']) {
        // Datum extrahieren
        $jahr = substr($form['datum'], 0, 4);
        $monat = substr($form['datum'], 5, 2);
        $tag = substr($form['datum'], 8, 2);
    } else {
        // Nullwert setzen, wenn Datum nicht angegeben wurde
        $form['datum'] = null;
    }
    /*
     * Wenn keine Fehler in Formularfeldern gefunden
     */
    if (!count($fehler)) {
        /*
         * Erfasste Daten in eine Datenbank schreiben
         */
        // Verbindung zur Datenbank aufbauen
        $db = dbConnect();

        // Formularwerte für die Datenbank escapen
        foreach ($form as $key => $value) {
            // Strings escapen
            if (is_string($value)) {
                $form[$key] = mysqli_real_escape_string($db, $value);
            }
        }

        // String für Datum erzeugen (wegen möglichem NULL-Wert)
        $datum = is_null($form['datum']) ? 'NULL' : "'${form['datum']}'";

        // SQL-Statement erzeugen
        $sql = <<<EOT
        UPDATE veranstaltungen
        SET name    = '{$form['name']}',
            beschreibung   = '{$form['beschreibung']}',
            datum = '{$form['datum']}',
            oid = '{$form['oid']}'
        WHERE vid = {$form['vid']}
EOT;

        // SQL-Statement an die Datenbank schicken
        mysqli_query($db, $sql) || die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));

        // Verbindung zur Datenbank trennen
        mysqli_close($db);

        // Weiterleiten auf Bestätigungsseite, dabei die ID des erzeugten Datensatzes übergeben
        header("location: veranstaltung-aendern-ok.php?vid=" . $form['vid']);
    }
    /*
     * Wenn Fehler in Formularfeldern gefunden
     */ else {
        // Formularfelder für die Ausgabe im Formular vorbereiten
        foreach ($form as $key => $value) {
            if (is_string($value)) {
                $form[$key] = htmlspecialchars($value);
            }
        }
    }
}

include TEMPLATES . 'aendernform.phtml';
