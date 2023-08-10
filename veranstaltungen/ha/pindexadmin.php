<?php
require_once 'functions.php';

// Anzahl anzuzeigender Adressen pro Seite
const PROSEITE = 3;

// Starten der Session
session_start();

// Standardwerte für Sessionvariablen setzen
$_SESSION['veranstaltungen_sort']           = $_SESSION['veranstaltungen_sort']         ?? 'vid';
$_SESSION['veranstaltungen_dest']           = $_SESSION['veranstaltungen_dest']         ?? 'ASC';
$_SESSION['veranstaltungen_seite']          = $_SESSION['veranstaltungen_seite']        ?? '1';
$_SESSION['veranstaltungen_datum']          = $_SESSION['veranstaltungen_datum']        ?? '';
$_SESSION['veranstaltungen_plz_von']        = $_SESSION['veranstaltungen_plz_von']      ?? '';
$_SESSION['veranstaltungen_plz_bis']        = $_SESSION['veranstaltungen_plz_bis']      ?? '';
$_SESSION['veranstaltungen_name']           = $_SESSION['veranstaltungen_name']         ?? '';
$_SESSION['veranstaltungen_beschreibung']   = $_SESSION['veranstaltungen_beschreibung'] ?? '';
$_SESSION['veranstaltungen_ort']            = $_SESSION['veranstaltungen_ort']          ?? '';
$_SESSION['veranstaltungen_stadt']          = $_SESSION['veranstaltungen_stadt']        ?? '';
$_SESSION['veranstaltungen_adresse']        = $_SESSION['veranstaltungen_adresse']      ?? '';

// AUCH Suche fuer Admin
$suche_besucher = [
    'name'          => $_GET['name']            ?? '',
    'beschreibung'  => $_GET['beschreibung']    ?? '',
    'ort'           => $_GET['ort']             ?? '',
    'stadt'         => $_GET['stadt']           ?? '',
    'adresse'       => $_GET['adresse']         ?? '',
    'plz_von'       => $_GET['plz_von']         ?? '',
    'plz_bis'       => $_GET['plz_bis']         ?? '',
    'datum'         => $_GET['datum']           ?? ''
];

/** @var array für die Veranstaltungsdaten */
$veranstaltungen = [];

//Array für etwaige Fehler
$fehler = [];

$ausgabe['veranstaltungen'] = [];

/*
 *  Suchformular auswerten und die WHERE-Klausel für die Abfrage erstellen
 */
/** @var string $suche  Als Parameter übergebener Suchstring für das Titelfeld */
if (isset($_GET['name'])) {
    $_SESSION['veranstaltungen_name'] = trim(strip_tags($_GET['name']));
    $_SESSION['veranstaltungen_seite'] = '1';
}


if (isset($_GET['beschreibung'])) {
    $_SESSION['veranstaltungen_beschreibung'] = trim(strip_tags($_GET['beschreibung']));
    $_SESSION['veranstaltungen_seite'] = '1';
}


if (isset($_GET['ort'])) {
    $_SESSION['veranstaltungen_ort'] = trim(strip_tags($_GET['ort']));
    $_SESSION['veranstaltungen_seite'] = '1';
}


if (isset($_GET['stadt'])) {
    $_SESSION['veranstaltungen_stadt'] = trim(strip_tags($_GET['stadt']));
    $_SESSION['veranstaltungen_seite'] = '1';
}


if (isset($_GET['adresse'])) {
    $_SESSION['veranstaltungen_adresse'] = trim(strip_tags($_GET['adresse']));
    $_SESSION['veranstaltungen_seite'] = '1';
}

if (isset($_GET['plz_von'])) {
    $_SESSION['veranstaltungen_plz_von'] = trim(strip_tags($_GET['plz_von']));
    $_SESSION['veranstaltungen_seite'] = '1';
}


if (isset($_GET['plz_bis'])) {
    $_SESSION['veranstaltungen_plz_bis'] = trim(strip_tags($_GET['plz_bis']));
    $_SESSION['veranstaltungen_seite'] = '1';
}


if (isset($_GET['datum'])) {
    $_SESSION['veranstaltungen_datum'] = trim(strip_tags($_GET['datum']));
    $_SESSION['veranstaltungen_seite'] = '1';
}



/** @var string $sort Sortierfeld aus Formular */
if (isset($_GET['sort'])) {
    $sort = trim(strip_tags($_GET['sort']));
    // übergebene Sortierung prüfen
    $felder = ['vid', 'name', 'beschreibung', 'datum', 'ort', 'adresse', 'stadt', 'plz'];
    $sort = in_array($sort, $felder) ? $sort : 'vid';

    // Prüfen, ob alte Sortierung der neuen entspricht, dann Richtung umdrehen
    if ($sort == $_SESSION['veranstaltungen_sort']) {
        $_SESSION['veranstaltungen_dest'] = 'ASC' == $_SESSION['veranstaltungen_dest'] ? 'DESC' : 'ASC';
    } else {
        $_SESSION['veranstaltungen_dest'] = 'ASC';
    }
    $_SESSION['veranstaltungen_sort'] = $sort;
    $_SESSION['veranstaltungen_seite'] = '1';
}

/** @var string $seite  Aktuell anzuzeigende Seite */
if (isset($_GET['seite'])) {
    $_SESSION['veranstaltungen_seite'] = intval(strip_tags($_GET['seite']));
}



// Verbindung zur Datenbank aufbauen
$db = dbConnect();

$where_array = [];
foreach ($suche_besucher as $key => $value) {
    if (!empty($value)) {
        if ($key === 'name') {
            $escape = mysqli_real_escape_string($db, $value);
            $where_array[] = "(name LIKE '%$escape%' OR beschreibung LIKE '%$escape%')";
        } elseif ($key === 'plz_von' && !empty($suche_besucher['plz_bis'])) {
            $plz_von = mysqli_real_escape_string($db, $value);
            $plz_bis = mysqli_real_escape_string($db, $suche_besucher['plz_bis']);
            $where_array[] = "plz BETWEEN '$plz_von' AND '$plz_bis'";
        } elseif ($key === 'plz_von' && empty($suche_besucher['plz_bis'])) {
            $plz_von = mysqli_real_escape_string($db, $value);
            $where_array[] = "plz >= '$plz_von'";
        } elseif ($key === 'plz_bis' && empty($suche_besucher['plz_von'])) {
            $plz_bis = mysqli_real_escape_string($db, $value);
            $where_array[] = "plz <= '$plz_bis'";
        } elseif ($key !== 'plz_bis') {
            $escape = mysqli_real_escape_string($db, $value);
            $where_array[] = "$key LIKE '%$escape%'";
        }
    }
}

// Leicht angepasste WHERE Klausel, da auch vergangene Veranstaltungen angezeigt werden sollen
$where_klausel = '';
if (!empty($where_array)) {
    $where_klausel = 'WHERE ' . implode(' AND ', $where_array);
}

/*
 * Gesamtzahl gefundener Datensätze ermitteln
 */
/** @var int $anzahl  Anzahl gefundener Datensätze */
$anzahl = 0;

$sql = "SELECT vid FROM veranstaltungen LEFT JOIN orte ON veranstaltungen.oid = orte.oid $where_klausel";


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
$_SESSION['veranstaltungen_seite'] = max(min($_SESSION['veranstaltungen_seite'], $seiten), 1);

/** @var int $offset  Offset für anzuzeigende Datensätze */
$offset = ($_SESSION['veranstaltungen_seite'] - 1) * PROSEITE;

// LIMIT-Klausel erstellen
$limit = "LIMIT $offset, " . PROSEITE;

/*
 * Gespeicherte Daten aus der Datenbank lesen
 */
// Sortierung formulieren
$order = "ORDER BY {$_SESSION['veranstaltungen_sort']} {$_SESSION['veranstaltungen_dest']}";

//SQL-Statement zum Lesen der anzuzeigenden Einträge
$sql = <<<EOT
    SELECT veranstaltungen.vid,
           veranstaltungen.name,
           orte.ort,
           orte.plz,
           DATE_FORMAT(datum, '%d.%m.%Y') AS datumm,
           CONCAT(LEFT(beschreibung, 80), IF(CHAR_LENGTH(beschreibung)>80,'...','')) AS beschreibung,
           orte.adresse,
           orte.stadt       
    FROM veranstaltungen
    LEFT JOIN orte ON veranstaltungen.oid = orte.oid
        $where_klausel
        $order          
        $limit            
EOT;

// SQL-Statement an die Datenbank schicken und Ergebnis (Resultset) in $result speichern
if ($result = mysqli_query($db, $sql)) {
    while ($veranstaltung = mysqli_fetch_assoc($result)) {
        // Felder für die Ausgabe in HTML-Seite vorbereiten
        foreach ($veranstaltung as $key => $value) {
            $veranstaltung[$key] = htmlspecialchars($value);
        }
        $veranstaltungen[] = $veranstaltung;
    }
    // Resultset freigeben
    mysqli_free_result($result);
} else {
    die('DB-Fehler (' . mysqli_errno($db) . ') ' . mysqli_error($db));
}

// Verbindung zum DB-Server schließen
mysqli_close($db);

// Suchtext für Ausgabe im Formular escapen
$suchstring_name                = htmlspecialchars($_SESSION['veranstaltungen_name']);

$suchstring_ort                 = htmlspecialchars($_SESSION['veranstaltungen_ort']);

$suchstring_beschreibung        = htmlspecialchars($_SESSION['veranstaltungen_beschreibung']);

$suchstring_plz_von             = htmlspecialchars($_SESSION['veranstaltungen_plz_von']);

$suchstring_plz_bis             = htmlspecialchars($_SESSION['veranstaltungen_plz_bis']);

$suchstring_datum               = htmlspecialchars($_SESSION['veranstaltungen_datum']);

$suchstring_stadt               = htmlspecialchars($_SESSION['veranstaltungen_stadt']);

$ausgabe['titel'] = "Adminbereich Tabelle";
$ausgabe['spaltenanzahl'] = 10;
$ausgabe['admin'] = 1;
include TEMPLATES . 'veranstaltungstabelleulti.phtml';
