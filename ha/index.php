<?php
require_once 'functions.php';


// Anzahl anzuzeigender Adressen pro Seite
const PROSEITE = 25;

// Starten der Session
session_start();

// Standardwerte für Sessionvariablen setzen
$_SESSION['filmdb_suche'] = $_SESSION['filmdb_suche'] ?? '';
$_SESSION['filmdb_sort'] = $_SESSION['filmdb_sort'] ?? 'id';
$_SESSION['filmdb_dest'] = $_SESSION['filmdb_dest'] ?? 'ASC';
$_SESSION['filmdb_seite'] = $_SESSION['filmdb_seite'] ?? '1';

/** @var array $filme Daten der gespeicherten Filme */
$filme = [];

/*
 *  Suchformular auswerten und die WHERE-Klausel für die Abfrage erstellen
 */
/** @var string $suche  Als Parameter übergebener Suchstring für das Titelfeld */
if (isset($_GET['suche'])) {
    $_SESSION['filmdb_suche'] = trim(strip_tags($_GET['suche']));
    $_SESSION['filmdb_seite'] = '1';
}

/** @var string $sort Sortierfeld aus Formular */
if (isset($_GET['sort'])) {
    $sort = trim(strip_tags($_GET['sort']));
    // übergebene Sortierung prüfen
    $felder = ['id', 'titel', 'filmreihe', 'land', 'premiere', 'fsk', 'laufzeit'];
    $sort = in_array($sort, $felder) ? $sort : 'id';

    // Prüfen, ob alte Sortierung der neuen entspricht, dann Richtung umdrehen
    if ($sort == $_SESSION['filmdb_sort']) {
        $_SESSION['filmdb_dest'] = 'ASC' == $_SESSION['filmdb_dest'] ? 'DESC' : 'ASC';
    } else {
        $_SESSION['filmdb_dest'] = 'ASC';
    }
    $_SESSION['filmdb_sort'] = $sort;
    $_SESSION['filmdb_seite'] = '1';
}

/** @var string $seite  Aktuell anzuzeigende Seite */
if (isset($_GET['seite'])) {
    $_SESSION['filmdb_seite'] = intval(strip_tags($_GET['seite']));
}



// Verbindung zur Datenbank aufbauen
$db = dbConnect();

/** @var string $where  Abfragebedingung für die Filmsuche */
$titel = mysqli_escape_string($db, $_SESSION['filmdb_suche']);
$where = $_SESSION['filmdb_suche'] ? "WHERE f.titel LIKE '%$titel%'" : '';

/*
 * Gesamtzahl gefundener Datensätze ermitteln
 */
/** @var int $anzahl  Anzahl gefundener Datensätze */
$anzahl = 0;

//SQL-Statement zum Ermitteln der Anzahl der gefundenen Filme
$sql = "SELECT id FROM filme AS f $where";

// SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
if ($result = mysqli_query($db, $sql)) {
    // Anzahl der Treffer ermitteln
    $anzahl = mysqli_num_rows($result);
    // Resultset freigeben
    mysqli_free_result($result);
} else {
    die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
}

// Anzahl der Seiten, aktuelle Seite sowie Suchoffset bestimmen
/** @var int $seiten  Anzahl der Seiten */
$seiten = ceil($anzahl / PROSEITE);

// aktuelle Seite prüfen
$_SESSION['filmdb_seite'] = max(min($_SESSION['filmdb_seite'], $seiten), 1);

/** @var int $offset  Offset für anzuzeigende Datensätze */
$offset = ($_SESSION['filmdb_seite'] - 1) * PROSEITE;

// LIMIT-Klausel erstellen
$limit = "LIMIT $offset, " . PROSEITE;

/*
 * Gespeicherte Daten aus der Datenbank lesen
 */
// Sortierung formulieren
$order = "ORDER BY {$_SESSION['filmdb_sort']} {$_SESSION['filmdb_dest']}";

//SQL-Statement zum Lesen der anzuzeigenden Filme
$sql = <<<EOT
    SELECT f.id,
           f.titel,
           fr.titel AS filmreihe,
           l.bezeichnung AS land,
           DATE_FORMAT(premiere, '%d.%m.%Y') AS premiere_date,
           fsk,
           SEC_TO_TIME(laufzeit) AS laufzeit,
        plakat
    FROM filme AS f
    LEFT JOIN laender AS l ON f.land = l.id 
    LEFT JOIN filmreihen AS fr ON f.filmreihe = fr.id 
    $where
    $order
    $limit
EOT;

// SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
if ($result = mysqli_query($db, $sql)) {
    // Alle Datensätze aus dem Resultset holen und in $filme speichern
    while ($film = mysqli_fetch_assoc($result)) {
        // Felder für die Ausgabe in HTML-Seite vorbereiten
        foreach ($film as $key => $value) {
            $film[$key] = htmlspecialchars($value);
        }
        // Film an Filme-Array anhängen
        $filme[] = $film;
    }

    // Resultset freigeben
    mysqli_free_result($result);
} else {
    die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
}

// Verbindung zum DB-Server schließen
mysqli_close($db);

// Suchtext für Ausgabe im Formular escapen
$suchstring = htmlspecialchars($_SESSION['filmdb_suche']);

include TEMPLATES . 'filmtabelle.phtml';