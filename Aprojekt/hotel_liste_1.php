<?php
require_once 'init.php';
require_once 'funktionen_strings.php';
require_once 'functions.php';

// Array für die Adressen
$adressen = [];
$ausgabe = '';

$datei = fopen('buchung.csv', 'r');

while ($adresse = fgetcsv($datei)) {
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
                            $ausgabe = '';
                            ?>
                            <tr>
                                <?php
                                echo "<th>" . $zaehler . "</th>";
                                $zaehler++;

                                foreach ($adressen[$key] as $key2 => $value2):
                                    $ausgabe = $value2;
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
                                            }
                                        elseif ($adressen[$key][12] == $adressen[$key][$key2]):
                                            if ($value2 == 12):
                                                $ausgabe = 'Ja';
                                            else:
                                                $ausgabe = 'n.g.';
                                            endif;
                                        elseif ($adressen[$key][11] == $adressen[$key][$key2]):
                                            if ($value2 == 11):
                                                $ausgabe = 'Ja';
                                            else:
                                                $ausgabe = 'n.g.';
                                            endif;
                                        elseif ($adressen[$key][13] == $adressen[$key][$key2]):
                                            if ($value2 == 13):
                                                $ausgabe = 'Ja';
                                            else:
                                                $ausgabe = 'n.g.';
                                            endif;
                                        elseif ($adressen[$key][14] == $adressen[$key][$key2]):
                                            switch ($value2) {
                                                case 4:
                                                    $ausgabe = 'Frühstück';
                                                    break;
                                                case 5:
                                                    $ausgabe = 'Halbpension';
                                                    break;
                                                case 6:
                                                    $ausgabe = 'Vollpension';
                                                    break;
                                            }
                                        elseif ($adressen[$key][15] == $adressen[$key][$key2]):
                                            switch ($value2) {
                                                case 7:
                                                    $ausgabe = 'Standard';
                                                    break;
                                                case 8:
                                                    $ausgabe = 'Comfort';
                                                    break;
                                                case 9:
                                                    $ausgabe = 'Premium';
                                                    break;
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

                    </div>
                </article>
            </main>
        </div>  
    </body>
</html>
