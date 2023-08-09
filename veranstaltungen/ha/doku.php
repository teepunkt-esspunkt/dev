<?php

require_once 'functions.php';

$ausgabe['titel'] = 'Dokumentation';
include TEMPLATES . 'htmlkopf.phtml';
?>
<p>Eine gutgemeinte Dokumentation der Planung und Durchführung unseres Projektes.</p>

<h2>Inhaltsverzeichnis:</h2>
<ol>
    <li><a href="#tag1">Tag 1</a></li>
    <li><a href="#tag2">Tag 2</a></li>
    <li><a href="#tag3">Tag 3</a></li>
</ol>

<h2 id="tag1">Tag 1: Aufbau der Datenbankstruktur</h2>
<p><strong>Teilnehmer:</strong> Alle</p>
<p><strong>Sitzung:</strong> Aufbau der Datenbankstruktur</p>
<p><strong>Definition und Benennung der Spalten:</strong></p>
<ul>
    <li>vid: Primärschlüssel für die Tabelle "Veranstaltungen", Veranstaltungs-ID (Auto-Increment)</li>
    <li>name: Veranstaltungsname (VARCHAR 100)</li>
    <li>beschreibung: Kurze Beschreibung der Veranstaltung (VARCHAR 500)</li>
    <li>oid: Primärschlüssel für die Tabelle "Orte", Fremdschlüssel, der auf die Tabelle "Veranstaltungen" verweist (Auto-Increment)</li>
    <li>datum: Veranstaltungsdatum (DATE)</li>
    <li>ort: Veranstaltungsort, Name der Location (VARCHAR 100)</li>
    <li>plz: Postleitzahl (INT)</li>
    <li>stadt: Stadtname (VARCHAR 100)</li>
    <li>adresse: Straßenadresse und Hausnummer (VARCHAR 100)</li>
</ul>
<p><strong>Datenbankname:</strong> veranstaltungen</p>
<p><strong>Tabellennamen:</strong> veranstaltungen, orte</p>

<h4>Vadim:</h4>
<li>Dateinamen umbenennen, Funktionsanpassungen, Einrichten der Bearbeitungs- und Löschfunktion</li></p>

<h4>Shayan:</h4>
<li>Einrichtung der Besucherseite</p>

<h4>Tarek:</h4>
<li>Einrichtung des Administrationsbereichs<br>
<li>Aufteilung von HTML und PHP in Templates</p>

<h2 id="tag2">Tag 2: HTML-PHP Trennung und Fehlerkorrekturen</h2>
<h4>Tarek:</h4>
<li>Rückgängigmachung der HTML-PHP-Trennung vom Vortag (Neustrukturierung von HTML in Templates)<br>
<li>Fehlerkorrekturen<br>
<li>Unterstützung</p>

<h2 id="tag3">Tag 3: Verfeinerung der Funktionen und Tests</h2>
<h4>Shayan:</h4>
<li>Verfeinerung der Suchfunktion<br>
<li>Implementierung mehrerer Suchen im Formular, Verwendung von WHERE-Klauseln für mehrere Kriterien<br>
<li>Implementierung von HTML-Code<br>
<li>Fehlerbehebung</p>

<h4>Vadim:</h4>
<li>Unterstützung bei der Rückgängigmachung der Trennung<br>
<li>Aktualisierung von Einträgen in der Datenbank</p>

<h4>Tarek:</h4>
<li>Homogenisierung der Seiten "pindex" und "pindexadmin" für die Benutzung eines einzigen HTML-Templates<br>
<li>Fehlerbehebung<br>
<li>Tests von Abfragen</p>

<h4>Vadim:</h4>
<li>Datenbanktests<br>
<li>Bereitstellung von Unterstützung</p>

<h2 id="tag4">Tag 4: Verfeinerung der Funktionen und Tests</h2>
<?php

include TEMPLATES . 'htmlfuss.phtml';
