<?php 
require_once 'functions_veranstaltungen.php';

/** @var int $detailsid ID des geschriebenen Datensatzes */
$detailsid = !empty($_GET['detailsid']) ? intval(trim($_GET['detailsid'])) : 0;

/** @var string $loeschok  Löschbestätigung */
//$detailsok = $_GET['detailsid'] ?? false;


/** @var string $ergebnis  Fehlermeldung */
$ergebnis = '';

/** @var string[] $veranstaltung Daten des gespeicherten Eintrags */
$veranstaltung = [];

// Wenn gültige Datensatz-ID übergeben wurde
if(0 < $detailsid && veranstaltungExist($detailsid)) {
    
    // Verbindung zur Datenbank aufbauen
    $db = dbConnect(); 

    // Prüfen, ob Löschbestätigung gegeben wurde
    if($detailsid) {
        /*
         * Erfasste Daten aus der Datenbank lesen,
         * wenn noch keine Löschbestätigung gegeben wurde
         */
        // SQL-Statement erzeugen
        $sql = <<<EOT
            SELECT veranstaltungen.vid,
           veranstaltungen.name,
           orte.ort,
           orte.plz,
           DATE_FORMAT(datum, '%d.%m.%Y') AS datumm,
           beschreibung,
           orte.adresse,
           orte.stadt       
    FROM veranstaltungen
    LEFT JOIN orte ON veranstaltungen.oid = orte.oid
            WHERE vid = $detailsid
EOT;

        // SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
        if($result = mysqli_query($db, $sql)) {
            // Den ersten (und einzigen) Datensatz aus dem Resultset holen
            if($veranstaltung = mysqli_fetch_assoc($result)) {
                // Felder für die Ausgabe in HTML-Seite vorbereiten
                foreach($veranstaltung as $key => $value) {
                    $veranstaltung[$key] = htmlspecialchars($value);
                }
            }

            // Resultset freigeben
            mysqli_free_result($result);
        }
        else {
            die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
        }
    }
    // Verbindung zur Datenbank trennen
    mysqli_close($db);
}
//elseif(!$detailsid) {
//    // Datensatz-ID wurde nicht übergeben
//    $ergebnis = 'Datensatz-ID fehlt!';
//}
else {
    // Datensatz mit dieser ID existiert nicht
    $ergebnis = 'Ungültige Datensatz-ID!';
}
$ausgabe['titel'] = 'Details';
include TEMPLATES . 'htmlkopf.phtml';
?>

            <?php if($ergebnis): ?>
                <?php if(!veranstaltungExist($detailsid)): ?>
                    
               
            <h4><span>Ungültige ID</span></h4>
              <?php       endif; ?>
            
            
            <?php else: ?>
            <h3><span><?= $ergebnis ?></span></h3>
            
            
            <table>
                <?php foreach($veranstaltung as $name => $wert): ?>
                <tr>
                    <th><?= ucfirst($name) ?></th>
                    <td><?= $wert ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            
            
            <?php endif; ?>
            <h3><a href="pindexadmin.php">zurück zur Liste</a></h3>
       <?php
