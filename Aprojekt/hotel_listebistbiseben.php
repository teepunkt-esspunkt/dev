<?php
require_once 'init.php';
require_once 'funktionen_strings.php';
require_once 'functions.php';

// Array für die Adressen
$adressen = [];
$ausgabe = '';

$datei = fopen('buchung.csv', 'r');

// Adressen aus der Datei lesen
//while($adresse = fgetcsv($datei)) {
//    
//        
//    //$adresse[0] = $titel[$adressen][0];
//$adressen[] = $adresse;
//}
// Adressen aus der Datei lesen Herr aus 1 ersetzen
// Verplegung hizufügen
// $verpflegung = [
//     '1' => 'Frühstück',
//     '2' => 'Halbpension',
//     '3' => 'Vollpension',
// ];
//$anrede =[
//
//    '1' =>'H',
//    '2' =>'F',
//    '3' =>'V',
//    ];
//Kategorei
//$hotelkategorie=[
//    '1' =>'Standard',
//    '2' =>'Comfort',
//    '3' =>'Premium',
//    '4' =>'KingSize',
//    '5' =>'President',
//];
while ($adresse = fgetcsv($datei)) {

    //   $adresse[0]=$anrede[$adresse[0]];
//    $adresse[14]=$verpflegung[$adresse[14]];
//$adresse[15]=$hotelkategorie[$adresse[15]];
    $adressen[] = $adresse;
}
// Datei schließen
fclose($datei);

$zaehler = 0;

$form['suche'] = $_GET['suche'] ?? '0';
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Adressausgabe</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="new2_1.css" rel="stylesheet">
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
                    <h1> Adressliste </h1>
                    <table>
                        <caption> Adressen </caption>
                        <colgroup>
<!--                            <thead>
                                <tr> 
                                   
                                   <th> </th>
                    
                                    
                                   
                                </tr> 
                            </thead>-->
                            <?php
                            for ($i = 1; $i <= count($adressen); $i++):
                                ?>
                                <col>

                                <?php
                            endfor;
                            ?>
                        </colgroup>

                        <?php
                        $i = 1;
                        foreach ($adressen as $key => $value):
                            ?>
                            <tr>
                                <?php
                                echo "<th>" . $zaehler . "</th>";
                                $zaehler++;

                                foreach ($adressen[$key] as $key2 => $value2):

                                    if ($adressen[$key] == $adressen[0]):
                                        ?>
                                        <th> <?php echo $value2 ?></th>

                                        <?php
                                    else:
                                        if ($adressen[$key][0] == $adressen[$key][$key2]):
                                            switch ($value2) {
                                                case 1:
                                                    $ausgabe = 'Herr';
                                                    break;
                                                case 2:
                                                    $ausgabe = 'Frau';
                                                    break;
                                                case 3:
                                                    $ausgabe = 'Firma';
                                                    break;
                                                default:
                                                    $ausgabe = $value2;
                                            }
                                        elseif ($adressen[$key][12] == $adressen[$key][$key2]):
                                            if ($value2 == 1):
                                                $ausgabe = 'Ja';
                                            else:
                                                $ausgabe = 'n.g.';
                                            endif;
                                        elseif ($adressen[$key][14] == $adressen[$key][$key2]):
                                            switch ($value2) {
                                                case 1:
                                                    $ausgabe = 'Frühstück';
                                                    break;
                                                case 2:
                                                    $ausgabe = 'Halbpension';
                                                    break;
                                                case 3:
                                                    $ausgabe = 'Vollpension';
                                                    break;
                                                default:
                                                    $ausgabe = $value2;
                                            }
                                        elseif ($adressen[$key][15] == $adressen[$key][$key2]):
                                            switch ($value2) {
                                                case 1:
                                                    $ausgabe = 'Standard';
                                                    break;
                                                case 2:
                                                    $ausgabe = 'Comfort';
                                                    break;
                                                case 3:
                                                    $ausgabe = 'Premium';
                                                    break;
                                                default:
                                                    $ausgabe = $value2;
                                            }
                                        else:
                                            $ausgabe = $value2;
                                        endif;
                                        ?> <td><?php echo $ausgabe ?></td><?php
                                    endif;
                                endforeach;
                                ?>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                    </table>
                    <?php dieDump($adressen) ?>
                    <div>
                        <label for="suche">Suchfeld</label>
                        <input type="text" name="suche" id="suche"><br>
                        <span class="suche"></span><br>
                        <!--                                Ich habe mich zu lange mit der CSS aufgehalten, da die Fieldsets mein Layout zerstört haben, jetzt fehlt mir die Zeit für das Suchfeld,
                                                        ich wollte auch noch die Tabellenköpfe generieren lassen und das n.g. bzw die bezeichnungen der werte (nicht der keys) in der liste anzeigen lassen
                                                        aber ich glaube es ist besser etwas halbfertiges abzugeben als etwas das gar nicht funktioniert-->

                    </div>
                </article>
            </main>
        </div>  
    </body>
</html>
