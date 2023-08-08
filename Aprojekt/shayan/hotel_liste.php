<?php

$datei = fopen('hotel_reservierungen.csv', 'r');


?>
<!doctype html>
<html lang="de">
    <head>
        <title>Hotel Liste</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles/styles2.css" rel="stylesheet">
    </head>
    <body>
        <h1>Kundenliste</h1>
        <table>
            <th>Anrede</th>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>Telefon</th>
            <th>Straße</th>
            <th>Nr.</th>
            <th>PLZ</th>
            <th>Ort</th>
            <th>Email</th>
            <th>Datum</th>
            <?php

            while (($daten = fgetcsv($datei)) !== false) {
            echo '<tr>';
                foreach ($daten as $wert) {
                echo '<td>' . $wert . '</td>';
                }
            echo '</tr>';
            }
            fclose($datei);

            ?>
        </table>
        <div class="links">
        <button onclick="window.location.href='hotel_reservierungen_ok.php';">Bestätigung</button>
        <button onclick="window.location.href='hotel_formular.php';">Hauptseite</button>
        </div>  
        <br>
        <h2>Nachnamen Input</h2>
    <form method="post">
        <label for="nachname">Nachname:</label>
        <input type="text" name="nachname" id="nachname" required>

        <input class="suche" type="submit" value="Suchen">
    </form>

    <?php
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $nachname = $_POST["nachname"];
        $csvFile = 'hotel_reservierungen.csv';

        if (($handle = fopen($csvFile, 'r')) !== false) {
            $found = false;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                
                if ($data[2] === $nachname) {
                    
                    echo "<h2>Kundendaten:</h2>";
                    echo "<p>Anrede: " . $data[0] . "</p>";
                    echo "<p>Vorname: " . $data[1] . "</p>";
                    echo "<p>Nachname: " . $data[2] . "</p>";
                    echo "<p>Straße: " . $data[3] . "</p>";
                    echo "<p>Hausnummer: " . $data[4] . "</p>";
                    echo "<p>PLZ: " . $data[5] . "</p>";
                    echo "<p>Ort: " . $data[6] . "</p>";
                    echo "<p>Telefon: " . $data[7] . "</p>";
                    echo "<p>Email: " . $data[8] . "</p>";
                    echo "<p>Buchungstag: " . $data[9] . "</p>";
                    echo "<p>Dauer: " . $data[10] . "</p>";
                    echo "<p>Anzahl: " . $data[11] . "</p>";
                    echo "<p>Leistung 1: " . $data[12] . "</p>";
                    echo "<p>Leistung 2: " . $data[13] . "</p>";
                    echo "<p>Leistung 3: " . $data[14] . "</p>";
                    echo "<p>Verpflegung: " . $data[15] . "</p>";
                    echo "<p>Kategorie: " . $data[16] . "</p>";
                    
                    
                    $found = true;
                    break; 
                }
            }
            fclose($handle);
            
            
            if (!$found) {
                echo "<h2>Leider kein Treffer gefunden.</h2>";
            }
        }
    }
    ?>
    </body>
</html>
