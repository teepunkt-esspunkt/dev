<?php 
require_once 'functions.php';

// Array mit den Filmdaten
$form = [];

// Fehlerarray
$fehler = [];

// ID des zu ändernden Datensatzes holen
$updateid = !empty($_GET['updateid']) ? intval($_GET['updateid']) : 0;

// Liste der Länder holen
$laender = getLandliste();

/*
 * Prüfen, ob korrekte ID übergeben wurde
 */
if(0 < $updateid) {
    // ID wurde übergeben
    $db = dbconnect();
    
    // SQL-Statement
    $sql = <<<EOT
            SELECT id,
                   titel,
                   inhalt,
                   land,
                   premiere,
                   fsk,
                   SEC_TO_TIME(laufzeit) AS laufzeit
            FROM filme
            WHERE id = $updateid
EOT;
    
    // Statement an DB schicken
    $result = mysqli_query($db, $sql);
    
    // Prüfen, ob ein Film gefunden wurde
    if(mysqli_num_rows($result) > 0) {
        $form = mysqli_fetch_assoc($result);
    }
    else {
        $fehler['id'] = 'Ungültige Film-ID';
    }
    
    
}
elseif(!empty($_GET['okbutton'])) {
    // Formular wurde abgeschickt
    // Formularwerte holen
    $form['id']       = !empty($_GET['id'])       ? intval($_GET['id'])     : 0;
    $form['titel']    = !empty($_GET['titel'])    ? trim($_GET['titel'])    : '';
    $form['inhalt']   = !empty($_GET['inhalt'])   ? trim($_GET['inhalt'])   : '';
    $form['land']     = !empty($_GET['land'])     ? trim($_GET['land'])     : '';
    $form['premiere'] = !empty($_GET['premiere']) ? trim($_GET['premiere']) : '';
    $form['fsk']      = !empty($_GET['fsk'])      ? trim($_GET['fsk'])      : '';
    $form['laufzeit'] = !empty($_GET['laufzeit']) ? trim($_GET['laufzeit']) : '';
    
    // Alle Felder prüfen
    if(0 > $form['id']) {
        $fehler['id'] = 'ungültige Datensatz-ID';
    }
    
    // Weitere Felder prüfen (Prüfungen müssen noch eingefügt werden)
    
    
    
    // Premierendatum auf NULL setzen, wenn nicht vorhanden (oder nicht gültig)
    if(empty($form['premiere'])) {
        $form['premiere'] = null;
    }
    
    
    // Altersfreigabe prüfen
    if(!in_array($form['fsk'], ['null', '0', '6', '12', '16', '18'])) {
        $fehler['fsk'] = 'Bitte gültige FSK angeben';
    }
    elseif ('null' == $form['fsk']) {
        $form['fsk'] = null;
    }
    
    // Laufzeit prüfen
    if(!preg_match("/^(2[0-3]|[01][0-9])(:[0-5][0-9]){2}$/", $form['laufzeit'])) {
        $fehler['laufzeit'] = 'Bitte im Format \'hh:mm:ss\' angeben';
    }
    
    /*
     * Wenn keine Fehler aufgetreten sind
     */
    if(!count($fehler)) {
        // DB Verbindung
        $db = dbconnect();
        
        // Werte für DB escapen
        foreach($form as $key => $value) {
            // Strings escapen
            if(is_string($value)) {
                $form[$key] = mysqli_real_escape_string($db, $value);
            }
        }
        
        // Strings für Premierendatum und FSK erzeugen
        $premiere = is_null($form['premiere']) ? 'NULL' : "'{$form['premiere']}'";
        $fsk      = is_null($form['fsk'])      ? 'NULL' : "'{$form['fsk']}'";
        
        // SQL-Statement
        $sql = <<<EOT
        UPDATE filme
        SET titel  = '{$form['titel']}',
            inhalt = '{$form['inhalt']}',
            land   = '{$form['land']}',
            premiere = $premiere,
            fsk      = $fsk,
            laufzeit = TIME_TO_SEC('{$form['laufzeit']}')
        WHERE id = {$form['id']}
EOT;
        // SQL an DB schicken
        mysqli_query($db, $sql);
        
        // Weiterleiten auf Bestätigungsseite
        header('Location: film_aendern_ok.php?id=' . $form['id']);
        die;
    }
}
else {
    $fehler['id'] = 'Fehler';
}

/**
 * Gibt eine assoziative Liste aller Länder zurück
 * @return string[]  assoziative Liste aller Länder
 */
function getLandliste() {
    $laender = [];
    
    $db = dbconnect();
    
    $sql = 'SELECT * FROM laender ORDER BY bezeichnung';
    
    $result = mysqli_query($db, $sql);
    
    while($land = mysqli_fetch_assoc($result)) {
        $laender[$land['id']] = $land['bezeichnung'];
    }
    
    return $laender;
}
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Film ändern</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet">
    </head>
    <body>
        <h1>Film ändern</h1>
        <?php if(!empty($fehler['id'])): ?>
        <h3><span><?= $fehler['id'] ?></span></h3>
        <h4><a href="filmliste.php">zurück zur Übersicht</a></h4>
        <?php else: ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
            <div>
                <input type="hidden" name="id" value="<?= $form['id'] ?>">
            </div>
            <div>
                <label for="titel">Titel</label>
                <input type="text" name="titel" id="titel" value="<?= htmlspecialchars($form['titel']) ?>">
                <span><?= $fehler['titel'] ?? '' ?></span>
            </div>
            <div>
                <label for="inhalt">Inhalt</label>
                <textarea name="inhalt" id="inhalt"><?= htmlspecialchars($form['inhalt']) ?></textarea>
                <span><?= $fehler['inhalt'] ?? '' ?></span>
            </div>
            <div>
                <label for="land">Land</label>
                <select name="land" id="land">
                    <option value="" label="Land auswählen"></option>
                    <?php foreach($laender as $key => $value): ?>
                    <option value="<?= $key ?>" <?= $key == $form['land'] ? 'selected' : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
                <span><?= $fehler['land'] ?? '' ?></span>
            </div>
            <div>
                <label for="premiere">Premiere</label>
                <input type="date" name="premiere" id="premiere" value="<?= htmlspecialchars($form['premiere']) ?>">
                <span><?= $fehler['premiere'] ?? '' ?></span>
            </div>
            <div>
                <label for="fsk">FSK</label>
                <select name="fsk" id="fsk">
                    <option value="null">unbekannt</option>
                    <option value="0" <?= '0' === $form['fsk'] ? 'selected' : '' ?>>0</option>
                    <option value="6" <?= '6' === $form['fsk'] ? 'selected' : '' ?>>6</option>
                    <option value="12" <?= '12' === $form['fsk'] ? 'selected' : '' ?>>12</option>
                    <option value="16" <?= '16' === $form['fsk'] ? 'selected' : '' ?>>16</option>
                    <option value="18" <?= '18' === $form['fsk'] ? 'selected' : '' ?>>18</option>
                </select>
                <span><?= $fehler['fsk'] ?? '' ?></span>
            </div>
            <div>
                <label for="laufzeit">Laufzeit</label>
                <input type="text" name="laufzeit" id="laufzeit" value="<?= htmlspecialchars($form['laufzeit']) ?>">
                <span><?= $fehler['laufzeit'] ?? '' ?></span>
            </div>
            <div>
                <button type="submit" name="okbutton" value="42">speichern</button>
            </div>
            
        </form>
        <?php endif; ?>
    </body>
</html>
