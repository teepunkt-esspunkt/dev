-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 10. Aug 2023 um 09:22
-- Server-Version: 10.4.27-MariaDB
-- PHP-Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `veranstaltungen`
--
CREATE DATABASE IF NOT EXISTS `veranstaltungen` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `veranstaltungen`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orte`
--

DROP TABLE IF EXISTS `orte`;
CREATE TABLE `orte` (
  `oid` int(11) NOT NULL,
  `ort` varchar(100) NOT NULL,
  `plz` int(10) NOT NULL,
  `stadt` varchar(100) NOT NULL,
  `adresse` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `orte`
--

INSERT INTO `orte` (`oid`, `ort`, `plz`, `stadt`, `adresse`) VALUES
(1, 'Capitol', 30159, 'Hannover', 'Cebitgasse 11'),
(2, 'Kongresszentrum', 30171, 'Hannover', 'Kongresszentrum Str.17'),
(3, 'Cebit', 30173, 'Hannover', 'Frankfurterstr. 45'),
(4, 'Waterloo Platz', 30165, 'Hannover', 'Waterloo Platz 1'),
(5, 'Schützen Platz ', 30698, 'Hannover', 'Schützen Platz 1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `veranstaltungen`
--

DROP TABLE IF EXISTS `veranstaltungen`;
CREATE TABLE `veranstaltungen` (
  `vid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `beschreibung` varchar(500) NOT NULL,
  `oid` int(11) NOT NULL,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `veranstaltungen`
--

INSERT INTO `veranstaltungen` (`vid`, `name`, `beschreibung`, `oid`, `datum`) VALUES
(12, 'Cebits', '01100010 01101001 01110100 01110100 01100101 00100000 01100111 01101001 01100010 00100000 01110101 01101110 01110011 00100000 01100101 01101001 01101110 01100101 00100000 01100111 01110101 01110100 01100101 00100000 01101110 01101111 01110100 01100101', 3, '2024-09-18'),
(13, 'Anime Convention', 'Für alle Anime und Manga Fans!', 2, '2024-09-26'),
(14, 'Weltmeißterschaft Basketball', 'Die Weltmeißterschaft im Basketball ausgetragen von zwei zufällig gewählten Fußballvereinen', 1, '2023-08-30'),
(15, 'Kongressversammlung', '-', 2, '2024-09-09'),
(16, 'Richtig Wichtig', 'Eine unglaublich wichtige Veranstaltung, bloß nicht verpassen', 4, '2024-12-31'),
(17, 'Helene Fischer Konzert', 'Für ihr Live-Album erhielt Fischer 2013 zwei Echos in den Kategorien Deutschsprachiger Schlager und Erfolgreichste DVD-Produktion National. Die Verleihung fand im März 2013 in Berlin statt und wurde von ihr selbst moderiert. Für die Neuauflage der ZDF-Zeichentrickserie Biene Maja sang sie den Titelsong Die Biene Maja neu ein. Im September 2013 veröffentlichte sie die erste Single Fehlerfrei aus ihrem nächsten Studioalbum Farbenspiel, das im Oktober 2013 erschien. Das Album erreichte Platz eins i', 1, '2024-09-12'),
(18, 'Sido Konzert', 'Sidos Musik gehört zu verschiedenen Stilrichtungen des Rap: Gangsta-Rap, Pop-Rap, Rap-Rock, Trap, Comedy-Rap, Battle-Rap oder „Rap-Balladen“. Zurzeit steht er bei Urban/Universal Music unter Vertrag. Sein Künstlername stand nach eigenen Angaben zunächst für „Scheiße in dein Ohr“ (eine Zeile Sidos aus dem Royal-TS-Track Terroarr!) und später für „super-intelligentes Drogenopfer“, hat heute jedoch keine Bedeutung mehr.', 2, '2024-10-12'),
(19, 'Grüne Woche Messe', 'Die Internationale Grüne Woche Hannover, meist kurz Grüne Woche genannt, ist eine Messe in Hannover, auf der landwirtschaftliche Erzeugnisse (im weitesten Sinne) von Herstellern und Vermarktern der weltweiten Agrarindustrie präsentiert werden und die nicht nur Fachbesuchern, sondern auch dem allgemeinen Publikum offensteht. Sie ist die international wichtigste Messe für Ernährungswirtschaft, Landwirtschaft und Gartenbau und findet traditionell jeweils am Jahresanfang in den Messehallen unter dem', 3, '2024-06-03'),
(20, 'Schützenfest Hannover 2024', 'Während die Schütz*innen viele Jahre in ziviler Kleidung antraten, entstand im Jahr 1837 eine neue Schützenordnung, die es ihnen gestattete, auch in gleichmäßig gekleideten Gruppen aufzutreten. Deshalb gilt dieses Jahr zeitgleich als Geburtsstunde der Schützenvereine, weil sich so verschiedene Vereinigungen von Schütz*innen aus gleichen Stadtteilen bildeten.', 4, '2024-06-28'),
(21, 'Maschseefest Hannover 2024', 'Das Maschseefest in Hannover ist das grösste Volksfest in Niedersachsen. Landestypische Küche und musikalische Highlights unter freiem Himmel warten auf die Besucher. Während des Festes haben Sie beim größten Volksfest in Niedersachsen die Möglichkeit zu einer kulinarischen und künstlerischen Weltumsegelung. Lassen Sie sich in Urlaubsstimmung versetzen und geniessen Sie die vielfältigen Attraktionen rund um den Hannover Maschsee.', 5, '2024-07-26'),
(22, 'Triathlon Hannover 2023', 'Beim Hannover Triathlon am 2. und 3. September freut sich der Veranstalter wie schon in diesem Jahr auf die Elite, die auf dem schnellen und zuschauerfreundlichen Kurs im und am Maschsee das Bundesliga-Finale austragen wird.', 1, '2023-06-11'),
(23, 'Feuerwerkswettbewerb: Österreich 16.05.2023', 'Jahr für Jahr begeistern die feurigen Monumente, fesselt die kunstvolle Symbiose aus Klang & Licht. Bevor sich die Dunkelheit über den Barockgarten legt, bringt gespannte Vorfreude die Luft zum Flimmern. Dann prägen Live-Musik, Walking-Acts und Schauspielerei die bunte Szenerie. Kreative Reminiszenzen an das Teilnehmerland und kulinarische Vielfalt lassen die Zeit wie im Flug vergehen. Die Herrenhäuser Gärten bilden die perfekte Kulisse für dieses einzigartige Erlebnis.', 5, '2023-05-16'),
(24, 'EMO Hannover 2023', 'Zur EMO kommen Aussteller aus aller Welt und aus allen Branchen der Metallbearbeitungstechnik. Das Innovationspotenzial von Produktivität und Effizienz ist beispiellos. Die Aussteller haben seit Langem ihre Innovationszyklen auf die Messe ausgerichtet. Dadurch werden so viele Innovationen gezeigt wie auf keiner anderen Messe der Branche. Alle Produktionsbereiche von Werkzeugmaschinen und Produktionssystemen für die spanende und umformende Bearbeitung als Nukleus der Fertigung, Präzisionswerkzeug', 4, '2023-07-30');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `orte`
--
ALTER TABLE `orte`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `veranstaltungen`
--
ALTER TABLE `veranstaltungen`
  ADD PRIMARY KEY (`vid`),
  ADD KEY `oid` (`oid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `orte`
--
ALTER TABLE `orte`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `veranstaltungen`
--
ALTER TABLE `veranstaltungen`
  MODIFY `vid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
