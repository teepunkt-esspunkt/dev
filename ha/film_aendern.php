<?php
require_once 'functions.php';
require_once 'functions-filmdb.php';

session_start();

/** @var string[] $form alle Formularfelder */
$form = [];


//** @var string $updateid  ID des zu ändernden Datensatzes */
$updateid = !empty($_GET['updateid']) ? intval(trim($_GET['updateid'])) : 0;

/** @var string[] $laender  Array mit allen Ländern */
$laender = getLaender();

/*
 *  Prüfen, ob ID zum Ändern übergeben wurde und ob ID korrekt ist
 */
if(0 < $updateid && filmExist($updateid)) {
    // ID zum Ändern wurde übergeben.
    // Datensatz aus DB lesen und zur Anzeige vorbereiten
    
    // Verbindung zur Datenbank aufbauen
    $db = dbConnect();
    
    // SQL-Statement erzeugen
    $sql = <<<EOT
        SELECT id,
           titel,
            inhalt,
            land,
           premiere,
           fsk,
           SEC_TO_TIME(laufzeit) AS laufzeit,
            plakat
        FROM filme 
        WHERE id = $updateid
EOT;
    
    // SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
    if($result = mysqli_query($db, $sql)) {
        // den ersten (und einzigen) Datensatz aus dem Resultset holen
        if($form = mysqli_fetch_assoc($result)) {
            // Felder für die Ausgabe in HTML-Seite vorbereiten
            foreach($form as $key => $value) {
                $form[$key] = htmlspecialchars($value);
            }
        }

        // Resultset freigeben
        mysqli_free_result($result);
    }
    else {
        die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
    }
}
/*
 * Prüfen, ob Formular abgeschickt wurde
 * Falls ja, dann weitere Prüfungen durchführen
 */
elseif(!empty($_POST['okbutton'])) {
    
    /** @var string[] $ergebnis Fehlermeldungen für Formularfelder */
    $ergebnis = [];

    /*
     * Werte sämtlicher Formularfelder holen
     */
    $form['id']       = !empty($_POST['id'])       ? intval(trim($_POST['id']))           : 0;
    $form['titel']    = !empty($_POST['titel'])    ? trim(strip_tags($_POST['titel']))    : '';
    $form['inhalt']   = !empty($_POST['inhalt'])   ? trim(strip_tags($_POST['inhalt']))   : '';
    $form['land']     = !empty($_POST['land'])     ? trim(strip_tags($_POST['land']))     : '';
    $form['premiere'] = !empty($_POST['premiere']) ? trim(strip_tags($_POST['premiere'])) : '';
    $form['fsk']      = isset($_POST['fsk'])       ? trim(strip_tags($_POST['fsk']))      : '';
    $form['laufzeit'] = !empty($_POST['laufzeit']) ? trim(strip_tags($_POST['laufzeit'])) : '';
    $form['plakat']   = $_FILES['plakat'] ?? null;

    // ID prüfen
    if(0 >= $form['id']) {
        $ergebnis['id'] = 'Ungültige Datensatz-ID!';
    }
    elseif(!filmExist($form['id'])) {
        $ergebnis['id'] = 'Datensatz nicht gefunden!';
    }
    
    
    // Filmtitel prüfen
    if(!$form['titel']) {
        $ergebnis['titel'] = 'Bitte Filmtitel angeben';
    }
    elseif(strlen($form['titel']) > 255) {
        $ergebnis['titel'] = 'Filmtitel darf höchstens 255 Zeichen lang sein';
    }
    
    // Inhaltsangabe prüfen
    if(strlen($form['inhalt']) > 10000) {
        $ergebnis['inhalt'] = 'Inhaltsangabe darf höchstens 10.000 Zeichen lang sein';
    }
    
    // Land prüfen
    if(!$form['land']) {
        $ergebnis['land'] = 'Bitte Land auswählen';
    }
    elseif(!array_key_exists($form['land'], $laender)) {
        $ergebnis['land'] = 'Ungültiges Land eingeben';
        $form['land'] = null;
    }
    
    // Premierendatum prüfen (wenn angegeben)
    if($form['premiere']) {
        // Premierendatum extrahieren
        $jahr  = substr($form['premiere'], 0, 4);
        $monat = substr($form['premiere'], 5, 2);
        $tag   = substr($form['premiere'], 8, 2);
        // Datum auf allgemeine Gültigkeit prüfen
        if(!checkdate($monat, $tag, $jahr)) {
            $ergebnis['premiere'] = 'Bitte gültiges Premierendatum eingeben';
        }
    }
    else {
        // Nullwert setzen, wenn Premierendatum nicht angegeben wurde
        $form['premiere'] = null;
    }
    
    // Altersfreigabe prüfen (wenn angegeben)
    if(strlen($form['fsk']) && !in_array($form['fsk'], ['null', '0', '6', '12', '16', '18'])) {
        $ergebnis['fsk'] = 'Bitte gültige Altersfreigabe eingeben';
        $form['fsk'] = '';
    }
    elseif('null' == $form['fsk']) {
        // Nullwert setzen, wenn FSK nicht angegeben wurde
        $form['fsk'] = null;
    }
    
    // Laufzeit prüfen (wenn angegeben)
    if($form['laufzeit'] && !preg_match("/^(2[0-3]|[01]{0,1}[0-9])(:[0-5]{0,1}[0-9]){2}$/", $form['laufzeit'])) {
        $ergebnis['laufzeit'] = 'Bitte gültige Laufzeit eingeben';
    }
    
    // Filmplakat prüfen
    if(!empty($form['plakat']['tmp_name'])) {
        // Dateierweiterung ermitteln
        $extension = strtolower(pathinfo($form['plakat']['name'], PATHINFO_EXTENSION));
        // MIME-Typ auslesen
        $typ = exif_imagetype($form['plakat']['tmp_name']);

        // Dateierweiterung prüfen
        if(!in_array($extension, ['png', 'gif', 'jpg', 'jpeg'])) {
            $ergebnis['plakat'] = "Ungültige Dateierweiterung: $extension";
        }
//        // Dateityp prüfen
//        elseif($typ != IMAGETYPE_JPEG) {
//            $ergebnis['plakat'] = "Bitte nur jpg-Bilder hochladen";
//        }
        // Dateigröße prüfen
        elseif($form['plakat']['size'] > 500 * 1024) {
            $ergebnis['plakat'] = "Filmplakat darf höchstens 500KB groß sein";
        }
        
        // Datei in Bildordner verschieben, wenn kein Fehler aufgetreten ist
        if(!count($ergebnis)) {
            // Bildnamen erzeugen
            $bildname = uniqid() . '.' . $extension;
            // Datei verschieben/umbenennen
            move_uploaded_file($form['plakat']['tmp_name'], BILDER . $bildname);
            // Dateiname in Formulardaten speichern
            $form['plakat'] = $bildname;
        }
    }
    

    /*
     * Wenn keine Fehler in Formularfeldern gefunden
     */
    if(!count($ergebnis)) {
        /*
         * Erfasste Daten in eine Datenbank schreiben
         */
        // Verbindung zur Datenbank aufbauen
        $db = dbConnect();
        
        // Formularwerte für die Datenbank escapen
        foreach($form as $key => $value) {
            // Strings escapen
            if(is_string($value)) {
                $form[$key] = mysqli_real_escape_string($db, $value);
            }
        }
        
        // String für Premierendatum erzeugen (wegen möglichem NULL-Wert)
        $premiere = is_null($form['premiere']) ? 'NULL' : "'${form['premiere']}'";
        
        // String für FSK erzeugen (wegen möglichem NULL-Wert)
        $fsk = is_null($form['fsk']) ? 'NULL' : $form['fsk'];
        
        // String für Portraitbild erzeugen (wegen möglichem NULL-Wert)
      //  $plakat = is_string($form['plakat']) ? $form['plakat'] : '';
        
              $plakat = empty($form['plakat']) ? 'plakat' : "'{$form['plakat']}'";
        
        // SQL-Statement erzeugen
        $sql = <<<EOT
        UPDATE filme
        SET titel    = '${form['titel']}',
            inhalt   = '${form['inhalt']}',
            land     = '${form['land']}',
            premiere = $premiere,
            fsk      = $fsk,
            laufzeit = TIME_TO_SEC('${form['laufzeit']}'),
            plakat   = $plakat
        WHERE id = {$form['id']}
EOT;
        
        // SQL-Statement an die Datenbank schicken
        mysqli_query($db, $sql) || die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
        
        // Verbindung zur Datenbank trennen
        mysqli_close($db);

        // Weiterleiten auf Bestätigungsseite, dabei die ID des erzeugten Datensatzes übergeben
        header("location: film-aendern-ok.php?id=" . $form['id']);
    }
    /*
     * Wenn Fehler in Formularfeldern gefunden
     */
    else {
        // Formularfelder für die Ausgabe im Formular vorbereiten
        foreach($form as $key => $value) {
            if(is_string($value)) {
                $form[$key] = htmlspecialchars($value);
            }
        }
    }
}
include TEMPLATES . 'aendernform.phtml';