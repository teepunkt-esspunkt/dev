<?php 
/**
 * Gibt den längsten der drei angegebenen Strings zurück
 * 
 * @param string $string1 erster String
 * @param string $string2 zweiter String
 * @param string $string3 dritter String
 * @param string $kurz    [false] wenn true, dann wird der kürzeste String zurückgegeben
 * @return string  Der längste der angegebenen String
 */
function lString($string1, $string2, $string3, $kurz = false, $reverse = false) {
//    $l1 = laenger($string1, $string2);
//    $ergebnis = laenger($l1, $string3);
//    return $ergebnis;
    $ergebnis = '';
    if($kurz) {
        $ergebnis = kuerzer(kuerzer($string1, $string2), $string3);
    }
    else {
        $ergebnis = laenger(laenger($string1, $string2), $string3);  
    }
    
    return $reverse == true ? mb_strrev($ergebnis) : $ergebnis;
}

/**
 * Gibt einen String in umgekehrter Reihenfolge zurück (multibytesicher)
 * 
 * @param string $string  der umzudrehende String
 * @return string  umgedrehter String
 */
function mb_strrev($string) {
    $ergebnis = '';
    
    for($i = mb_strlen($string)-1; $i >= 0; $i--) {
        $ergebnis .= mb_substr($string, $i, 1);
    }
    return $ergebnis;
}

/**
 * Gibt den längeren von 2 Strings zurück
 * @param string $string1  erster String
 * @param string $string2  zweiter String
 * @return string   der längere der beiden Strings
 */
function laenger($string1, $string2) {
    return mb_strlen($string1) > mb_strlen($string2) ? $string1 : $string2;
}

/**
 * Gibt den kürzeren von 2 Strings zurück
 * @param string $string1  erster String
 * @param string $string2  zweiter String
 * @return string   der kürzeren der beiden Strings
 */
function kuerzer($string1, $string2) {
    return mb_strlen($string1) < mb_strlen($string2) ? $string1 : $string2;
}

function meineLinkeNavBarLeiste(){
    $meineStandardLinkeNavBarDatei = fopen('meineStandardLinkeNavBar.csv', 'r');
    while($meineStandardLinkeNavBarLinks = fgetcsv($meineStandardLinkeNavBarDatei)){
     $meineStandardLinkeNavBarArray[] = $meineStandardLinkeNavBarLinks;
}
fclose($meineStandardLinkeNavBarDatei);  
    foreach($meineStandardLinkeNavBarArray as $key => $value){
        echo  "<li><a href = " . $meineStandardLinkeNavBarArray[$key][0] . ">" . $meineStandardLinkeNavBarArray[$key][1] . "</a></li>";
}
}