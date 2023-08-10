<?php
require_once 'functions.php';

$ausgabe['titel'] = 'Dokumentation';
include TEMPLATES . 'htmlkopf.phtml';
?>
<div class="doku">
    <p>Eine gutgemeinte Dokumentation der Planung und Durchführung unseres Projektes.</p>


    <h2>Inhaltsverzeichnis:</h2>
    <ol>
        <li><a href="#dbstruktur">Datenbank Struktur</a></li>
        <li><a href="#allgemeinstruktur">Allgemeine Struktur</a></li>
        <li><a href="#tag1">Montag</a></li>
        <li><a href="#tag2">Dienstag</a></li>
        <li><a href="#tag3">Mittwoch</a></li>
        <li><a href="#tag4">Donnerstag</a></li>
        <li><a href="#tag6">Freitag</a></li>
        <li><a href="#probleme">Probleme</a></li>
    </ol>

    <h4>Teilnehmer:</h4>
    <ul>
        <li>Tarek Saleh</li>
        <li>Vadim Nasyrov</li>
        <li>Shayan Hamzavi-Fard</li>
    </ul>
    <p>Projekt:</p>
    <p>Erstellung einer Eventseite mit Besucherseite und Adminbereich</p>

    <h2 id="dbstruktur">Datenbankstruktur:</h2>
    <p>Definition und Benennung der Spalten:</p>
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

    <h4>Datenbankname: veranstaltungen</h4>
    <h4>Tabellennamen: veranstaltungen, orte</h4>

    <h2 id="allgemeinstruktur">Allgemeine Strukturen:</h2>
    <p>Wir trennen php und HTML und benutzen Templates wo wir können, um bei eventuellen Änderungen nicht dieselben Änderungen an verschiedenen Stellen vornehmen zu müssen.</p>
    <p>Bei einigen Seiten, bei denen der HTML oder der PHP - Teil nur sehr klein ausfällt, sparen wir uns das, da es im Endeffekt mehr Arbeit bringt durch weitere Feinanpassungen der Templates.</p>
    <p>Für unseren eigenen einfacheren Zugriff benennen wir die "index.php" in "pindex.php" um und genauso die "indexadmin.php" in "pindexadmin.php".</p>
    <p>Dies belassen wir auch bei der Abgabe so, da es für den Zugriff für erfahrene Benutzer mehr Vorteile als Nachteile hat.</p>

    <h2 id="tag1">Montag: Datenbankstruktur - Einrichtung der Hauptseiten</h2>

    <h4>Vadim:</h4>
    <ul>
        <li>Dateinamen umbenennen</li>
        <li>Funktionsanpassungen</li>
    </ul>

    <h4>Shayan:</h4>
    <ul>
        <li>Einrichtung der Besucherseite</li>
        <li>Datenbank einrichten</li>
    </ul>

    <h4>Tarek:</h4>
    <ul>
        <li>Einrichtung des Administrationsbereichs</li>
        <li>Aufteilung von HTML und PHP in Templates</li>
    </ul>
    <h2 id="tag2">Dienstag: HTML-PHP Trennung - Suchfunktion</h2>
    <h4>Vadim:</h4>
    <ul>
        <li>Füttern der Datenbank</li>
        <li>Dokumentation</li>
        <li>Unterstützung</li>
    </ul>

    <h4>Shayan:</h4>
    <ul>
        <li>Besucherseite Suche und Überlegungen</li>
        <li>Datenbank bearbeiten</li>
    </ul>
    <h4>Tarek:</h4>
    <ul>
        <li>Rückgängigmachung der HTML-PHP-Trennung vom Vortag (Neustrukturierung von HTML in Templates)</li>
        <li>Fehlerkorrekturen</li>
    </ul>

    <h2 id="tag3">Mittwoch: Verfeinerung der Funktionen und Tests</h2>
    <h4>Vadim:</h4>
    <ul>
        <li>Unterstützung bei der Rückgängigmachung der Trennung</li>
        <li>Aktualisierung von Einträgen in der Datenbank</li>
    </ul>
    <h4>Shayan:</h4>
    <ul>
        <li>Verfeinerung der Suchfunktion</li>
        <li>Implementierung mehrerer Suchen im Formular, Verwendung von WHERE-Klauseln für mehrere Kriterien</li>
        <li>Implementierung von HTML-Code</li>
        <li>CSS bearbeiten</li>
        <li>Fehlerbehebung</li>
    </ul>



    <h4>Tarek:</h4>
    <ul>
        <li>Homogenisierung der Seiten "pindex" und "pindexadmin" für die Benutzung eines einzigen HTML-Templates</li>
        <li>Fehlerbehebung</li>
        <li>Tests von Abfragen</li>
    </ul>
    <h2 id="tag4">Donnerstag: Verfeinerung der Funktionen und Tests</h2>
    <h4>Vadim:</h4>
    <ul>
        <li>Einträge in der Datenbank erstellen</li>
        <li>Anpassung vorhandener Datensätze</li>
    </ul>
    <h4>Shayan:</h4>
    <ul>
        <li>Kommentare</li>
        <li>Sicherheit</li>
    </ul>
    <h4>Tarek:</h4>
    <ul>
        <li>Sicherheit</li>
        <li>Validierung</li>
    </ul>
    <h2 id="tag5">Freitag: Letzte Tests und Änderungen</h2>
    <h4>Vadim:</h4>
    <ul>
        <li>Stresstest</li>
        <li>Abgabe</li>
    </ul>
    <h4>Shayan:</h4>
    <ul>
        <li>Stresstest</li>
        <li>Abgabe</li>
    </ul>
    <h4>Tarek:</h4>
    <ul>
        <li>Stresstest</li>
        <li>Abgabe</li>
    </ul>

    <h2 id="probleme">Begegnete Probleme:</h2>
        <h4>Datenbankbereich:</h4>
        <ul>
            <li>sinnvolle Datenbankstruktur erarbeiten</li>
            <li>sinnvolle Normalisierung</li>
            
        </ul>
        <p> Lösung: durch Brainstorming in der Gruppe auf Zwei-Tabellen-Struktur</p>

        <h4>HTML/CSS Bereich</h4>
        <ul>
            <li>Paginator verschob sich mit der Breite der Tabelle</li>
            <li>Farben nicht optimal</li>
            <li>Titel anklickbar machen und dennoch mit einer Schleife arbeiten</li>
            <li>Ausrichtung der Formfelder und Suchbutton.</li>
        </ul>
        <p> Lösung: Paginator eigene Klasse gegeben und von der Caption getrennt. Wodurch wir 
            ihn ankern konnten und ihn zur besseren Klickbarkeit etwas vergrößert haben.<br>
            Farben angepasst, Formfeld Name eine Klasse gegeben in einem Container und 
            dann per CSS angepasst. Das gleiche beim Button.</p>

        <h4>PHP-Bereich</h4>
        <ul>
            <li>Adminbereich ID Übergabe beim Löschen</li>
            <li>Sortierung der Felder Datum und PLZ</li>
            <li>Where Klausel erzeugte SQL Fehler</li>
            <li>Probleme durch benötigte neue Template-Struktur</li>
        </ul>
        <p>Lösung: PHP-Code strukturiert durchgehen und nach Logikfehlern absuchen. (z.B. 
            Klammern , Schleifen, Bedingungen). SQL Dumps erzeugen um Fehler 
            aufzuspüren und dann effektiv in PHP zu lösen</p>
</div>
<?php
include TEMPLATES . 'htmlfuss.phtml';
