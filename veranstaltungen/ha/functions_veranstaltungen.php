<?php
/**
 * Bibliothek mit speziellen Funktionen für die Filmdb
 */
require_once 'functions.php';


/**
 * Prüft und gibt zurück, od eine ID in der Tabelle filme existiert
 * 
 * @param string|int $id
 * @return bool
 */
function veranstaltungExist($id)
{
    // Verbindung zur Datenbank aufbauen
    $db = dbConnect();
    
    /** @var bool $gefunden  Schalter, ob Datensatz gefunden wurde */
    $gefunden = false;

    // SQL-Statement erzeugen
    $sql = "SELECT vid FROM veranstaltungen WHERE vid = $id";

    // SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
    if($result = mysqli_query($db, $sql)) {

        // Schalter, ob Datensatz gefunden wurde
        $gefunden = boolval(mysqli_num_rows($result));

        // Resultset freigeben
        mysqli_free_result($result);
    }
    else {
        die('DB-Fehler');
    }

    // Datenbank schließen
    mysqli_close($db);
    
    // Zurückgeben des Ergebnisses
    return $gefunden;
}

/**
 * Liefert ein Assoziatives Array mit allen Orten, wobei der Schlüssel die ID ist und
 * der Wert die Bezeichnung des Landes
 * @return string[]
 */
function getOrte()
{
    // Verbindung zur Datenbank aufbauen
    $db = dbConnect();
    
    /** @var string[] $laender  Assoziatives Array mit den Ländern */
    $orte = [];

    // SQL-Statement erzeugen
    $sql = "SELECT * FROM orte ORDER BY oid ASC";

    // SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
    if($result = mysqli_query($db, $sql)) {

        // Datensätze auslesen und in Ergebnisarray speichern
        while($ort = mysqli_fetch_assoc($result)) {
            $orte[$ort['oid']] = htmlspecialchars($ort['ort'], ENT_DISALLOWED | ENT_HTML5 | ENT_QUOTES);
        }

        // Resultset freigeben
        mysqli_free_result($result);
    }
    else {
        die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
    }

    // Datenbank schließen
    mysqli_close($db);
    
    // Zurückgeben des Ergebnisses
    return $orte;
}