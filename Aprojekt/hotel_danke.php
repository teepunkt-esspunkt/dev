<?php 
 require_once 'init.php';
require_once 'funktionen_strings.php';
 


$zaehler = 0;

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
////$form['zusatz'][] = $_GET['zusatz'] ?? [];
// Zusatuleistung Fieldset Ende
// Verpflegung Fieldset Anfang (4)
$form['verpflegung'] = $_GET['verpflegung'] ?? '';
// Verpflegung Fieldset Ende
// Hotelkategorie Fieldset Anfang (5)
$form['hotelkategorie']         = $_GET['hotelkategorie'] ?? '0';
// Hotelkategorie Fieldset Ende

//$form['ok']             = $_GET['ok'] ?? '';

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Danke</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet">
    </head>
    <body>
        <div id="wrapper">
            <nav id="top">
                <ul>
                    <li><a href = "">Home</a></li>
                </ul>
                <ul>
                    <li><a href = "">Kontakt |</a></li>
                    <li><a href = "">Impressum</a></li>
                </ul>
            </nav>
            <main>
                <nav id="left">
                    <ul>
                        <?php meineLinkeNavBarLeiste(); ?>
                    </ul>
                </nav>
        
                <article>
                    <h1> Vielen Dank! </h1>
                    <table>
                        <?php foreach($form as $key => $value): ?>
                        <tr>
                                    
                               
                            
                                    
                       
                               <th><?= $key ?></th>
                                <td><?= htmlspecialchars($value) ?></td>
                         </tr>
                           
                        <?php endforeach; ?>
        </table>
                </article>
            </main>
        </div>  
    </body>
</html>
