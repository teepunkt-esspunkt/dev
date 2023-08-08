<?php
require_once 'functions.php';

/** @var string[] $form alle Formularfelder */
$form = [];

/** @var string[] $fehler Fehlermeldungen für Formularfelder */
$fehler = [];

/** @var string $datendatei Pfad und Dateiname der Datei zum Speichern der Datensätze */
$datendatei = './adressen.csv';


// Formularwerte holen
$form['anrede']       = !empty($_GET['anrede'])       ? htmlspecialchars(trim($_GET['anrede']))       : '';
$form['vorname']      = !empty($_GET['vorname'])      ? htmlspecialchars(trim($_GET['vorname']))      : '';
$form['nachname']     = !empty($_GET['nachname'])     ? htmlspecialchars(trim($_GET['nachname']))     : '';
$form['plz']          = !empty($_GET['plz'])          ? htmlspecialchars(trim($_GET['plz']))          : '';
$form['ort']          = !empty($_GET['ort'])          ? htmlspecialchars(trim($_GET['ort']))          : '';
$form['telefon']      = !empty($_GET['telefon'])      ? htmlspecialchars(trim($_GET['telefon']))      : '';
$form['geburtsdatum'] = !empty($_GET['geburtsdatum']) ? htmlspecialchars(trim($_GET['geburtsdatum'])) : '';

/*
 * Prüfen, ob Formular abgeschickt wurde
 * Falls ja, dann weitere Prüfungen durchführen
 */
if(isset($_GET['okbutton'])) {
    // Anrede prüfen
    if(!$form['anrede']) {
        $fehler['anrede'] = 'Bitte Anrede auswählen';
    }
    elseif('w' != $form['anrede'] && 'm' != $form['anrede']) {
        $fehler['anrede'] = 'Ungültige Anrede';
    }

    // Vorname prüfen
    if(!$form['vorname']) {
        $fehler['vorname'] = 'Bitte Vornamen eingeben';
    }
    elseif(strlen($form['vorname']) < 2) {
        $fehler['vorname'] = 'Vorname muss mindestens zwei Zeichen lang sein';
    }
    
    // Nachname prüfen
    if(!$form['nachname']) {
        $fehler['nachname'] = 'Bitte Nachnamen eingeben';
    }
    
    // Postleitzahl prüfen
    if(!$form['plz']) {
        $fehler['plz'] = 'Bitte PLZ eingeben';
    }
    elseif(intval($form['plz']) < 100 || intval($form['plz']) > 99999) {
        $fehler['plz'] = 'Bitte eine gültige deutsche PLZ eingeben';
    }
    
    // Ort prüfen
    if(!$form['ort']) {
        $fehler['ort'] = 'Bitte Ort eingeben';
    }
    
    // Geburtsdatum prüfen
    if(!$form['geburtsdatum']) {
        $fehler['geburtsdatum'] = 'Bitte Geburtsdatum eingeben';
    }
    else {
        // Geburtsdatum extrahieren
        $jahr  = substr($form['geburtsdatum'], 0, 4);
        $monat = substr($form['geburtsdatum'], 5, 2);
        $tag   = substr($form['geburtsdatum'], 8, 2);
        // Datum auf allgemeine Gültigkeit prüfen
        if(!checkdate($monat, $tag, $jahr)) {
            $fehler['geburtsdatum'] = 'Bitte gültiges Geburtsdatum eingeben';
        }
        // Prüfen, ob Mindestalter erreicht (18 Jahre)
        else {
            $achtzehn = mktime(0, 0, 0, date('n'), date('j'), intval(date('Y'))-18);
            $geburtstag = mktime(0, 0, 0, $monat, $tag, $jahr);
            if($geburtstag > $achtzehn) {
                $fehler['geburtsdatum'] = 'Mindestalter ist 18 Jahre';
            }
        }
    }
    
    // Wenn keine Fehler aufgetreten sind ...
    if(!count($fehler)) 
	{
            // 1. Datenbank öffnen
            $db = mysqli_connect(DBSERVER, DBUSER, DBPASSWD, DBNAME);

            // 2. SQL-Statement erzeugen
            /*
             * HEREDOC-Schreibweise
             * Hinter der Anfangsmarke darf NICHTS stehen (auch kein Leerzeichen)
             * Die Endemarke muss in einer eigenen Zeile GANZ VORNE stehen (auch darf nichts dahinter stehen)
             */
            $sql = <<<EOT
                INSERT INTO adressen
                (anrede, vorname, nachname, plz, ort, telefon, geburtsdatum)
                VALUES ('{$form['anrede']}', 
                        '{$form['vorname']}',
                        '{$form['nachname']}',
                        '{$form['plz']}',
                        '{$form['ort']}',
                        '{$form['telefon']}',
                        '{$form['geburtsdatum']}')
EOT;
                    
            // 3. SQL-Statement an DB schicken
            mysqli_query($db, $sql);
        
            // 4. Erzeugte ID des Datensatzes ermitteln
            $id = mysqli_insert_id($db);
            
            // 5. Schließen der DB
            mysqli_close($db);
        
            // Weiterleiten auf Erfolgsseite und Programm beenden
            header('location: adressen-ok.php?id=' . $id);
            die;
        }
}

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Adressverwaltung</title>
        <link href="adressen.css" rel="stylesheet">
    </head>
    <body>
        <h1>Adresse erfassen!</h1>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
            <div>
                <label for="anrede" class="pflicht">Anrede</label>
                <select name="anrede" id="anrede">
                    <option value=""></option>
                    <option value="w" <?= 'w' == $form['anrede'] ? 'selected' : '' ?>>Frau</option>
                    <option value="m" <?= 'm' == $form['anrede'] ? 'selected' : '' ?>>Herr</option>
                </select>
                <span><?= $fehler['anrede'] ?? '' ?></span>
            </div>

            <div>
                <label for="vorname" class="pflicht">Vorname</label>
                <input type="text" name="vorname" id="vorname" value="<?= $form['vorname'] ?>">
                <span><?= $fehler['vorname'] ?? '' ?></span>
            </div>

            <div>
                <label for="nachname" class="pflicht">Nachname</label>
                <input type="text" name="nachname" id="nachname" value="<?= $form['nachname'] ?>">
                <span><?= $fehler['nachname'] ?? '' ?></span>
            </div>

            <div>
                <label for="plz" class="pflicht">PLZ</label>
                <input type="text" name="plz" id="plz" value="<?= $form['plz'] ?>">
                <span><?= $fehler['plz'] ?? '' ?></span>
            </div>

            <div>
                <label for="ort" class="pflicht">Ort</label>
                <input type="text" name="ort" id="ort" value="<?= $form['ort'] ?>">
                <span><?= $fehler['ort'] ?? '' ?></span>
            </div>

            <div>
                <label for="telefon">Telefon</label>
                <input type="text" name="telefon" id="telefon" value="<?= $form['telefon'] ?>">
                <span><?= $fehler['telefon'] ?? '' ?></span>
            </div>

            <div>
                <label for="geburtsdatum" class="pflicht">Geburtsdatum</label>
                <input type="date" name="geburtsdatum" id="geburtsdatum" value="<?= $form['geburtsdatum'] ?>">
                <span><?= $fehler['geburtsdatum'] ?? '' ?></span>
            </div>

            <div>
                <button type="submit" name="okbutton" value="1">speichern</button>
            </div>

            <div class='formcaption'>Felder in <span class='pflicht'>blau</span> sind Pflichtfelder</div>
        </form>
    </body>
</html>
