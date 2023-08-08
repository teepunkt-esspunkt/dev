<?php 
// Zu lange habe ich mich anfangs mit der CSS Aufgehalten, jetzt fehlt mir die Zeit
// Mir ist bewusst dass da noch einige Fehler sind aber ich gebe es jetzt lieber so ab bevor ich es ganz kaputt mache


require_once 'funktionen_strings.php';
require_once 'init.php';

// Array für die Formularwerte
$form = [];

// Array für die Fehlermeldungen
$fehler = [];

// Werte aus Formular holen
// Adressen Fieldset (1)
$form['anrede']         = $_GET['anrede'] ?? '0';
$form['vname']          = $_GET['vname'] ?? '';
$form['nname']          = $_GET['nname'] ?? '';
$form['plz']            = $_GET['plz'] ?? '';
$form['ort']            = $_GET['ort'] ?? '';
$form['strasse']        = $_GET['strasse'] ?? '';
$form['telefon']        = $_GET['telefon'] ?? '';                  // NULL-Coalescing-Operator
$form['email']          = $_GET['email'] ?? '';
// Adressen-Fieldset Ende
// 
// Zeitraum und Anzahl der Personen Fieldset (2)
$form['datum']   = $_GET['datum'] ?? '';
$form['dauer']   = $_GET['dauer'] ?? '0';
$form['personen']   = $_GET['personen'] ?? '0';
// Zeitraum und Anzahl der Personen Fieldset Ende
// Zusatzleistung Fieldset (3)
$form['wlan'] = $_GET['wlan'] ?? '0';
$form['sauna'] = $_GET['sauna'] ?? '0';
$form['fitness'] = $_GET['fitness'] ?? '0';
// Zusatuleistung Fieldset Ende
// Verpflegung Fieldset Anfang (4)
$form['verpflegung'] = $_GET['verpflegung'] ?? '';
// Verpflegung Fieldset Ende
// Hotelkategorie Fieldset Anfang (5)
$form['hotelkategorie']         = $_GET['hotelkategorie'] ?? '0';
// Hotelkategorie Fieldset Ende

// Prüfen, ob das Formular abgeschickt wurde
    if(isset($_GET['okbutton'])) {
    /*
     * Prüfen der Formulardaten
     */
        
        // erstes Fieldset Anfang
    /*
     * Prüfung der Anrede
     */
    if(!array_key_exists($form['anrede'], $anrede)) {
        $fehler['anrede'] = 'Bitte gültige Anrede auswählen';
        $form['anrede'] = '0';
    }
    /*
     * Vorname:
     * - Pflichtfeld
     * - Min. 2 Zeichen, max. 100 Zeichen
     */
    if('' == $form['vname']) {
        $fehler['vname'] = 'Bitte Vorname eingeben';
    }
    elseif(mb_strlen($form['vname']) < 2 || mb_strlen($form['vname']) > 100) {
        $fehler['vname'] = 'Min 2 bis max. 100 Zeichen';
        $form['vname'] = mb_substr($form['vname'], 0, 100); // Name auf max. 100 Zeichen kürzen
    }
     /*
     * Nachname:
     * - Pflichtfeld
     * - Min. 2 Zeichen, max. 100 Zeichen
     */
    if('' == $form['nname']) {
        $fehler['nname'] = 'Bitte Nachnamen eingeben';
    }
    elseif(mb_strlen($form['nname']) < 2 || mb_strlen($form['nname']) > 100) {
        $fehler['nname'] = 'Min 2 bis max. 100 Zeichen';
        $form['nname'] = mb_substr($form['nname'], 0, 100); // Name auf max. 100 Zeichen kürzen
    }
    /*
     * Straße
     * - Pflichtfeld
     *- Min. 2 Zeichen, max. 100 Zeichen
     * 
     */
    if('' == $form['strasse']){
        $fehler['strasse'] = 'Bitte Straße eingeben';
    }
    elseif(mb_strlen($form['strasse']) < 2 || mb_strlen($form['strasse']) > 100) {
        $fehler['strasse'] = 'Min 2 bis max. 100 Zeichen';
        $form['strasse'] = mb_substr($form['strasse'], 0, 100); // Straße auf max. 100 Zeichen kürzen
    } 
    /*
     * PLZ prüfen (nur deutsche)
     * - Nur Ziffern
     * - Zahl muss zwischen 100 und 99999 (inklusive) liegen
     * 
     */
    if(!is_numeric($form['plz'])) {
        $fehler['plz'] = 'ungültige PLZ';
        $form['plz'] = '';
    }
    elseif((int) $form['plz'] < 100 || (int) $form['plz'] > 99999) {
        $fehler['plz'] = 'PLZ muss 3- bis 5-stelling sein';
        $form['plz'] = '';
    }
    /*
     * Ort
     * - Pflichtfeld
     * Min. 2 Zeichen, max. 100 Zeichen
     */
    if('' == $form['ort']){
    $fehler['ort'] = 'Bitte Ort eingeben';
    }
    elseif(mb_strlen($form['ort']) < 2 || mb_strlen($form['ort']) > 100) {
        $fehler['ort'] = 'Min 2 bis max. 100 Zeichen';
        $form['ort'] = mb_substr($form['strasse'], 0, 100); // Ort auf max. 100 Zeichen kürzen
    }
    /*
     * Telefon:
     * - Pflichtfeld
     * - mind. 7 Zeichen, max. 50 Zeichen
     */
    if('' == $form['telefon']){
        $fehler['telefon'] = 'Bitte Telefonnummer eingeben';
    }
    elseif(mb_strlen($form['telefon']) < 7 || mb_strlen($form['telefon']) > 50) {
        $fehler['telefon'] = 'Min 7 bis max. 50 Zeichen';
        $form['telefon'] = mb_substr($form['telefon'], 0, 50); // Telefon auf max. 50 Zeichen kürzen
    }
    /*
     * E-Mail
     * - Pflichtfeld
     * @ . und mindestens 4 Zeichen müssen enthalten sein, also mindestens 6 Zeichen
     * @ muss enthalten sein, . muss enthalten sein
     */
    if('' == $form['email']){
    $fehler['email'] = 'Bitte E-Mail eingeben';
    }
    elseif(!(str_contains($form['email'], '@') || !str_contains($form['email'], '.')) ){
        $fehler['email'] = 'Bitte eine gültige E-Mail Adresse eingeben';
    }
    elseif(mb_strlen($form['email']) < 6 || mb_strlen($form['email']) > 100) {
        $fehler['email'] = 'Min 6 bis max. 100 Zeichen';
        $form['email'] = mb_substr($form['email'], 0, 100); // E-Mail auf max. 100 Zeichen kürzen
    }
    // erstes Fieldset Ende
    
    // zweites Fieldset Anfang
    /*
     * Datum wählen
     * - gültiger Datumsstring im Format JJJJ-MM-TT
     * - korrektes Datum                 0123456789
     * - darf nicht in der Vergangenheit liegen
     */
    // Einzelwerte extrahieren
    $jahr = (int) substr($form['datum'], 0, 4);
    $monat = (int) substr($form['datum'], 5, 2);
    $tag = (int) substr($form['datum'], 8, 2);
    // Datum auf Gültigkeit prüfen
    if(!checkdate($monat, $tag, $jahr)){
        $fehler['datum'] = 'ungültiges Datum';
        $form['datum'] = '';
    } // Datum liegt nicht in der Vergangenheit
    elseif(mktime(0, 0, 0, $monat, $tag, $jahr) < time()){
            $fehler['datum'] = 'Zeitreisen bieten wir noch nicht an ☻';
    }    
    /*
     * Prüfung der Dauer
     */
    if(!is_numeric($form['dauer'])) {
        $fehler['dauer'] = 'ungültige Dauer';
        $form['dauer'] = '';
    }
    elseif((int) $form['dauer'] < 1 || (int) $form['dauer'] > 100) {
        $fehler['dauer'] = 'Bitte eine gültige Dauer wählen';
        $form['dauer'] = '';
    }
  /*
   * Prüfung der Anzahl an Personen
   */
    if(!is_numeric($form['personen'])) {
        $fehler['personen'] = 'ungültige Personenanzahl';
        $form['personen'] = '';
    }
    elseif((int) $form['personen'] < 1 || (int) $form['personen'] > 50) {
        $fehler['personen'] = 'Bitte eine Anzahl an Personen wählen';
        $form['personen'] = '';
    }
    // zweites Fieldset Ende
    //Drittes Fieldset Anfang
    /*
     * Zusatzleistung:
     * - Ist Zahl?
     * - Zahl zwischen 1 und 3 (inklusive)
     */
    if(!is_numeric($form['wlan']) && !is_numeric($form['sauna']) && !is_numeric($form['fitness'])){
        $fehler['zusatz'] = "Bitte eine Zusatzleistung auswählen";
        $form['zusatz'] = '0';
    }
   
    if(!is_numeric($form['wlan']) || $form['wlan'] != 11) {
        $fehler['wlan'] = 'Ungültig';
        $form['wlan'] = '';
    }
      if(!is_numeric($form['sauna'])) {
        $fehler['sauna'] = 'Ungültig';
        $form['sauna'] = '';
    }
      if(!is_numeric($form['fitness'])) {
        $fehler['fitness'] = 'Ungültig';
        $form['fitness'] = '';
    }
//    elseif((int) $form['zusatz'] < 1 || (int) $form['zusatz'] > 3) {
//        $fehler['zusatz'] = 'Bitte eine Zusatzleistung wählen';
//        $form['zusatz'] = '';
//    }
    // Drittes Fieldset Ende
    // Viertes Fieldset Anfang 
     /*
     * Verpflegung:
     * - Ist Zahl?
     * - Zahl zwischen 1 und 3 (inklusive)
     */
    if(!is_numeric($form['verpflegung'])) {
        $fehler['verpflegung'] = 'Bitte eine Verpflegungsart wählen';
        $form['verpflegung'] = '';
    }
    elseif((int) $form['verpflegung'] < 4 || (int) $form['verpflegung'] > 6) {
        $fehler['verpflegung'] = 'Bitte eine Verpflegungsart wählen';
        $form['verpflegung'] = '';
    }
    
    //Fünftes Fieldset Anfang
    if(!is_numeric($form['hotelkategorie'])) {
        $fehler['hotelkategorie'] = 'Bitte eine Kategorie wählen';
        $form['hotelkategorie'] = '';
    }
    elseif((int) $form['hotelkategorie'] < 7 || (int) $form['hotelkategorie'] > 9) {
        $fehler['hotelkategorie'] = 'Bitte eine Kategorie wählen';
        $form['hotelkategorie'] = '';
    }
    //Fünftes Fieldset Ende
    
    
    
    /*
     * Prüfen ob ein Fehler augetreten ist
     */
    if(0 == count($fehler)) {
        // ... Daten speichern
        
        // Datei öffnen
        $datei = fopen('buchung.csv', 'a');
        
        
        // Daten schreiben
        fputcsv($datei, $form);
        
        // Datei schließen
        fclose($datei);
        
        // Formularwerte für die Weiterleitung codieren
        $querystring = http_build_query($form);
      
        
        // Weiterleiten zur Erfolgsseite
        header('Location: hotel_danke.php?' . $querystring);
        die; // Programm beenden
    }
}


?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Formulareingabe</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet">
    </head>
    <body>
        <div id="wrapper">
            <nav id="top"> <!-- Anfang Top Navigation -->
                <ul>
                    <li><a href = "hotel_formular.php">Home</a></li>
                </ul>
                <ul>
                    <li><a href = "hotel_danke.php">Danke |</a></li>
                    <li><a href = "hotel_liste.php">Listenansicht</a></li>
                </ul>
            </nav> <!-- Ende Top Navigation -->
            <main> 
                <nav id="left"> <!-- Anfang linke Navigationsleiste -->
                    <ul>
                        <?php meineLinkeNavBarLeiste(); ?>
                    </ul>
                </nav> <!-- Ende linke Navigationsleiste -->
        
                <article>
                    <h1> Hotel-Buchung </h1>
                    
                     <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
                         
                    <div>
                        <fieldset> <legend>Adresse</legend>
                            <div>
                                <label for="anrede">Anrede</label>
                                <select name="anrede" id="anrede">
                                    <option value="0" <?= '0'== $form['anrede'] ? 'selected' : '' ?>>Bitte auswählen</option>
                                    <?php foreach($anrede as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= $key == $form['anrede'] ? 'selected' : '' ?>><?= $value ?> </option>
                                    <?php
                                    endforeach; ?>
                                </select><br>                    
                                <span class="fehler"><?= $fehler['anrede'] ?? '' ?></span><br>
                            </div>
                            <div>
                                <label for="vname" class="pflicht">Vorname</label>
                                <input type="text" name="vname" id="vname" value="<?= htmlspecialchars($form['vname']) ?>"><br>
                                <span class="fehler"><?= $fehler['vname'] ?? '' ?></span><br>
                            </div>
                            <div>
                                <label for="nname" class="pflicht">Nachname</label>
                                <input type="text" name="nname" id="nname" value="<?= htmlspecialchars($form['nname']) ?>"><br>
                                <span class="fehler"><?= $fehler['nname'] ?? '' ?></span><br>
                            </div>
                            <div>
                                <label for="strasse" class="pflicht">Straße</label>
                                <input type="text" name="strasse" id="strasse" value="<?= htmlspecialchars($form['strasse']) ?>"><br>
                                <span class="fehler"><?= $fehler['strasse'] ?? '' ?></span><br>
                            </div>
                            <div>
                                <label for="plz" class="pflicht">PLZ</label>
                                <input type="text" name="plz" id="plz" value="<?= htmlspecialchars($form['plz']) ?>"><br>
                                <span class="fehler"><?= $fehler['plz'] ?? '' ?></span><br>
                            </div>
                            <div>
                                <label for="ort" class="pflicht">Ort</label>
                                <input type="text" name="ort" id="ort" value="<?= htmlspecialchars($form['ort']) ?>"><br>
                                <span class="fehler"><?= $fehler['ort'] ?? '' ?></span><br>
                            </div>
                            <div>
                            <label for="telefon" class="pflicht">Telefon</label>
                            <input type="text" name="telefon" id="telefon" value="<?= htmlspecialchars($form['telefon']) ?>"><br>
                            <span class="fehler"><?= $fehler['telefon'] ?? '' ?></span><br>
                            </div>
                            <div>
                            <label for="email" class="pflicht">E-Mail</label>
                            <input type="text" name="email" id="email" value="<?= htmlspecialchars($form['email']) ?>"><br>
                            <span class="fehler"><?= $fehler['email'] ?? '' ?></span><br>
                            </div>
                        </fieldset> 
                    </div>
                   
                    <div>
                        <fieldset> <legend>Zeitraum und Anzahl der Personen</legend>
                            <div>
                                <label for="geburtsdatum">Reise Start auswählen</label>
                                <input type ="date" name="datum" id="datum" value="<?= htmlspecialchars($form['datum']) ?>"><br>
                                <span class="datum"><?= $fehler['datum'] ?? '' ?></span>
                            </div> 
                            <div>
                                <label for="dauer">Bitte Anzahl der Übernachtungen auswählen</label>
                                <select name="dauer" id="dauer">
                                <option value="0" <?php '0'== $form['dauer'] ? 'selected' : '' ?>>Bitte auswählen</option>
                                    <?php for($i = 0; $i <= 100; $i++): ?>
                                <option value = "<?= $i ?>" <?php $i == $form['dauer'] ? 'selected' : ''?>><?= $i ?></option>
                                    <?php
                                    endfor; ?>
                                </select><br>                    
                                <span class="fehler"><?= $fehler['dauer'] ?? '' ?></span><br>
                            </div>       
                            <div>
                                <label for="personen">Bitte Anzahl der Personen auswählen</label>
                                <select name="personen" id="personen">
                                <option value="0" <?php '0'== $form['personen'] ? 'selected' : '' ?>>Bitte auswählen</option>
                                    <?php for($i = 1; $i <= 50; $i++): ?>
                                <option value = "<?= $i ?>" <?php $i == $form['personen'] ? 'selected' : ''?>><?= $i ?></option>
                                    <?php
                                    endfor; ?>
                                </select><br>                    
                                <span class="fehler"><?= $fehler['personen'] ?? '' ?></span><br>
                            </div>       
                        </fieldset>
                    </div>
                    <div>
                        <fieldset><legend>Zusatzleistung</legend>
                             <div>
                                <input type="checkbox" name="wlan" id ="wlan" value="11">
                                <label for="wlan">WLan</label>
                                <input type="checkbox" name="sauna" id ="sauna" value="12">
                                <label for="sauna">Sauna</label>
                                <input type="checkbox" name="fitness" id ="fitness" value="13">
                                <label for="fitness">Fitness</label>
                                            <br>
                            </div>
                        </fieldset>
                        <span class="fehler"><?= $fehler['zusatz'] ?? '' ?></span><br>
                    </div>
                    <div>
                        <fieldset><legend>Verpflegung</legend>
                            <div>
                                <?php foreach($verpflegung as $key => $value): ?>
                                    <input type="radio" name="verpflegung" id="verpflegung" value="<?= $key ?>">
                                    <label for="verpflegung"><?= $value ?></label>
                                <?php endforeach; ?> <br>
                                
                            </div>
                        </fieldset>
                         <span class="fehler"><?= $fehler['verpflegung'] ?? '' ?></span>
                    </div>
                    <div>
                        <fieldset><legend>Hotelkategorie</legend>
                            <div>
                                <label for="hotelkategorie">Hotelkategorie</label>
                                <select name="hotelkategorie" id="hotelkategorie">
                                    <option value="0" <?= '0'== $form['hotelkategorie'] ? 'selected' : '' ?>>Bitte auswählen</option>
                                    <?php foreach($hotelkategorie as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= $key == $form['hotelkategorie'] ? 'selected' : '' ?>><?= $value ?> </option>
                                    <?php
                                    endforeach; ?>
                                </select><br>                    
                                <span class="fehler"><?= $fehler['hotelkategorie'] ?? '' ?></span><br>   
                            </div>
                        </fieldset>   
                    </div>                 
                    <div>
                        <button type="submit" name="okbutton" value="42">Jetzt bestellen</button>
                    </div>
                    <div>
                        <span class="pflicht">Blaue Felder</span> sind Pflichtfelder.
                    </div>
                    </form>
                    
                </article>
            </main>
        </div>  
    </body>
</html>
