<?php
require_once 'functions.php';
require_once 'functions_veranstaltungen.php';

/** @var string[] $form alle Formularfelder */
$form = [];

$orte = getOrte();


/** @var string[] $fehler Fehlermeldungen für Formularfelder */
$fehler = [];

// Formularwerte holen
$form['vid']            = !empty($_POST['vid']) ? htmlspecialchars(trim($_POST['vid'])) : '';
$form['name']           = !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
$form['beschreibung']   = !empty($_POST['beschreibung']) ? htmlspecialchars(trim($_POST['beschreibung'])) : '';
$form['oid']            = !empty($_POST['oid']) ? htmlspecialchars(trim($_POST['oid'])) : '';
$form['datum']          = !empty($_POST['datum']) ? htmlspecialchars(trim($_POST['datum'])) : '';

/*
 * Prüfen, ob Formular abgeschickt wurde
 * Falls ja, dann weitere Prüfungen durchführen
 */
if (isset($_POST['okbutton'])) {

    // name prüfen
    if (!$form['name']) {
        $fehler['name'] = 'Bitte Namen eingeben';
    } elseif (strlen($form['name']) < 2) {
        $fehler['name'] = 'Veranstaltungsname muss mindestens zwei Zeichen lang sein';
    }

    // Datum prüfen
    if (!$form['datum']) {
        $fehler['datum'] = 'Bitte Datum eingeben';
    } 
    else {
        // Datum extrahieren
        $jahr = substr($form['datum'], 0, 4);
        $monat = substr($form['datum'], 5, 2);
        $tag = substr($form['datum'], 8, 2);
        // Datum auf allgemeine Gültigkeit prüfen
        if (!checkdate($monat, $tag, $jahr)) {
            $fehler['datum'] = 'Bitte gültiges Datum eingeben';
        }
        // Prüfen, dass Datum nicht in der Vergangenheit liegt
        else {
            $jetzt = mktime(0, 0, 0, date('n'), date('j'), intval(date('Y')));
            $datum = mktime(0, 0, 0, $monat, $tag, $jahr);
            if ($datum < $jetzt) {
                $fehler['datum'] = 'Datum liegt in der Vergangenheit';
            }
        }
    }
    

    // Wenn keine Fehler aufgetreten sind ...
    if (!count($fehler)) {
        // 1. Datenbank öffnen
        $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWD, DB_NAME);

        // 2. SQL-Statement erzeugen
        /*
         * HEREDOC-Schreibweise
         * Hinter der Anfangsmarke darf NICHTS stehen (auch kein Leerzeichen)
         * Die Endemarke muss in einer eigenen Zeile GANZ VORNE stehen (auch darf nichts dahinter stehen)
         */
        $sql = <<<EOT
                INSERT INTO veranstaltungen
                (vid, name, beschreibung, oid, datum)
                VALUES ('{$form['vid']}', 
                        '{$form['name']}',
                        '{$form['beschreibung']}',
                        '{$form['oid']}',
                        '{$form['datum']}')
EOT;

        // 3. SQL-Statement an DB schicken
        mysqli_query($db, $sql);

        // 4. Erzeugte ID des Datensatzes ermitteln
        $vid = mysqli_insert_id($db);

        // 5. Schließen der DB
        mysqli_close($db);

        // Weiterleiten auf Erfolgsseite und Programm beenden
        header('location: veranstaltung-ok.php?vid=' . $vid);
        die;
}}


include TEMPLATES . 'hinzufuegenform.phtml';