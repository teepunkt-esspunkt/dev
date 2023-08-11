<?php
/**
 * Bibliothek mit allgemeinen Funktionen
 * 
 */
// Verzeichnis für Bilder
const BILDER = './images/';
const TEMPLATES = './templates/';


/** @const var_dump() als Methode für Variablen-Dumps */
const DUMP_VARDUMP = 'v';

/** @const print_r() als Methode für Variablen-Dumps */
const DUMP_PRINTR  = 'p';


// Zugansdaten für den DB-Server
const DB_SERVER = 'localhost';
const DB_USER   = 'phpkurs';
const DB_PASSWD = 'profil';
const DB_NAME   = 'veranstaltungen';

/**
 * Gibt eine Verbindung zur Datenbank zurück
 * 
 * @return mysqli
 */
function dbConnect() {
    // Verbindung zur Datenbank aufbauen
    if(!$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWD, DB_NAME)) {
        die('Verbindungsfehler (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }

    // Zeichensatz für die Verbindung explizit angeben
    mysqli_set_charset($db, 'UTF8');

    // Zurückgeben der DB-Verbindung
    return $db;
}

/**
 * Gibt einen Dump der übergebenen Variable in einem präformatierten HTML-Block aus
 *
 * @param  mixed   $varToDump  Variable, deren Dump ausgegeben wird
 * @param  string  $title      Titelzeile für die Ausgabe
 * @param  string  $method     [DUMP_VARDUMP] Dump-Methode
 */
function dump($varToDump, $title = '', $method = DUMP_VARDUMP) 
{
    // Block für präformatierten Text öffnen
    echo '<pre>';
    // Ausgabe des Titels, falls angegeben
    if($title) {
        echo '<strong><u>'.(string) $title.':</u></strong><br>';
    }
    // Dump der Variablen mit angeforderter Funktion
    if(DUMP_PRINTR == $method) {
        print_r($varToDump);
    }
    else {
        var_dump($varToDump);
    }
    echo '</pre>';
}

/**
 * Gibt einen Dump der übergebenen Variable in einem präformatierten HTML-Block aus
 * und beendet dann die Programmausführung
 *
 * @param  mixed   $varToDump  Variable, deren Dump ausgegeben wird
 * @param  string  $title      Titelzeile für die Ausgabe
 * @param  string  $method     [DUMP_VARDUMP] Dump-Methode
 */
function dieDump($varToDump, $title = '', $method = DUMP_VARDUMP) 
{
    // Dump der Variablen
    dump($varToDump, $title, $method);
    // Programmausführung beenden
    die;
}


// Funktion fuer die linke Navigationsleiste, mit IF-Abfrage um Links auf fremde Webseiten in einem neuen Tab zu oeffnen
function meineLinkeNavBarLeiste(){
    $meineStandardLinkeNavBarDatei = fopen('meineStandardLinkeNavBar.csv', 'r');
    while($meineStandardLinkeNavBarLinks = fgetcsv($meineStandardLinkeNavBarDatei)){
     $meineStandardLinkeNavBarArray[] = $meineStandardLinkeNavBarLinks;
}
    fclose($meineStandardLinkeNavBarDatei);  
    foreach($meineStandardLinkeNavBarArray as $key => $value){
        if(str_contains($meineStandardLinkeNavBarArray[$key][0], 'http://')) {
            echo  "<li><a href = " . $meineStandardLinkeNavBarArray[$key][0] . 'target="_blank">' . $meineStandardLinkeNavBarArray[$key][1] . "</a></li>";
        } else {
        echo  "<li><a href = " . $meineStandardLinkeNavBarArray[$key][0] . ">" . $meineStandardLinkeNavBarArray[$key][1] . "</a></li>";
}
}
}
