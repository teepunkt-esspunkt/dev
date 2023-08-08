<?php
require_once 'functions.php';
session_start();

// Daten für die hinzugefuegte Adresse

$form = [];

$vid = !empty($_GET['vid']) ? intval($_GET['vid']) : 0;
//$vid                    = !empty($_POST['vid'])             ? intval($_POST['vid'])                     : 0;
$form['name']           = !empty($_POST['name'])            ? trim(strip_tags($_POST['name']))          : '';
$form['beschreibung']   = !empty($_POST['beschreibung'])    ? trim(strip_tags($_POST['beschreibung']))  : '';
$form['oid']            = !empty($_POST['oid'])             ? trim(strip_tags($_POST['oid']))           : '';
$form['datum']          = !empty($_POST['datum'])           ? trim(strip_tags($_POST['datum']))         : '';


if($vid > 0) {
    // 1. Datenbank öffnen
    $db = dbConnect();

    // 2. SQL-Statement erzeugen 
    $sql = <<<EOT
        SELECT vid,
               name,
               beschreibung,
               oid,
               DATE_FORMAT(datum, '%d.%m.%Y') as datum
        FROM veranstaltungen 
        WHERE vid = $vid
EOT;
    
    // 3. SQL-Statement an DB schicken und Ergebnis (Resultset) speichern
    $result = mysqli_query($db, $sql);
    
    // 4. Ersten (und einzigen) Datensatz aus dem Resultset holen
    $form = mysqli_fetch_assoc($result);
    
    // 5. Schließen der DB
    mysqli_close($db);
    
    
    // Datum formatieren brauchen wir das noch?
    // $form['datum'] = date_format(date_create_from_format('Y-m-d', $form['datum']), 'd.m.Y');
}
$ausgabe['titel'] = 'Hinzugefügt!';
include TEMPLATES . 'htmlkopf.phtml';
?>


            <table>
                <caption>Sie haben folgende Daten eingegeben:</caption>
                <?php foreach($form as $key => $value): ?>
                <tr>
                    <th><?= ucfirst($key) ?></th>
                    <td><?= $value ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <h3><a href="veranstaltung_hinzufuegen.php">Weitere Veranstaltung erfassen</a></h3>
            <h3><a href="pindex.php">Veranstaltungen anzeigen</a></h3>
  <?php
include TEMPLATES . 'htmlfuss.phtml';