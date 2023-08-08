<?php
/**
 * Bibliothek mit allgemeinen Funktionen
 * 
 */

// Konstanten für die DB-Verbindung
const DBSERVER = 'localhost';
const DBUSER   = 'phpkurs';
const DBPASSWD = 'profil';
const DBNAME   = 'finf28_filmdb';


/** @const var_dump() als Methode für Variablen-Dumps */
const DUMP_VARDUMP = 'v';

/** @const print_r() als Methode für Variablen-Dumps */
const DUMP_PRINTR  = 'p';

/** @const Übertragungsmethode GET bei HTTP-Request */
const METHOD_GET  = 'GET';

/** @const Übertragungsmethode POST bei HTTP-Request */
const METHOD_POST = 'POST';

/**
 * Stellt die Verbindung zur Datenbank her
 * @return ressource  Datenbankverbindung
 */
function dbconnect() {
    try {
        // Datenbank öffnen
        $db = mysqli_connect(DBSERVER, DBUSER, DBPASSWD, DBNAME);

        // Zeichensatz für die Verbindung setzen
        mysqli_set_charset($db, 'UTF8');
    }
    catch(mysqli_sql_exception $e) {
        echo 'Verbindungsfehler: ' . $e->getMessage();
        die;
    }
    
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
