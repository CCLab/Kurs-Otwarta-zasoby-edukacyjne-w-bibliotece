<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Course TOC
 *
 * @package    block_coursetoc
 * @copyright  2019 INTERSIEĆ amalkowski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Spis treści SpołEd';
$string['coursetoc'] = 'Spis treści';
$string['resourcestoc'] = 'Zasoby';
$string['blocktoc'] = 'Spis treści lekcji';
$string['blocktoc_help'] = 'Jedna linia to jeden rozdział/podrozdział. Jeśli na początku jest myślnik <strong>"-"</strong> linia zostanie rozpoznana jako podrozdział.
<br>Nazwę rozdziału/podrozdział odzielaj znakiem <strong>|</strong> od linku do niego kierującego.
<br>
<br>Przykład:
<br>1. Rozdział a|/mod/lesson/view.php?id=2
<br>- Podrozdział 1|/mod/lesson/view.php?id=2&pageid=1
<br>2. Rozdział b|/mod/lesson/view.php?id=3
<br>- Podrozdział 1|/mod/lesson/view.php?id=2&pageid=3
<br>- Podrozdział 2|/mod/lesson/view.php?id=2&pageid=4';
$string['blocktoc_def'] = '1. Rozdział a|/mod/lesson/view.php?id=2
- Podrozdział 1|/mod/lesson/view.php?id=2&pageid=2
2. Rozdział b|/mod/lesson/view.php?id=2
- Podrozdział 1|/mod/lesson/view.php?id=2&pageid=3
- Podrozdział 2|/mod/lesson/view.php?id=2&pageid=4';
$string['blocktype'] = 'Typ';
$string['blocktype_course'] = 'Kurs';
$string['blocktype_resources'] = 'Zasoby';
$string['coursetoc:addinstance'] = 'Dodaj nowy blok spisu kursu SpołEd';
$string['coursetoc:myaddinstance'] = 'Dodaj nowy blok analizy urządzeń do mojej platformy Moodle';
$string['privacy:metadata:userid'] = 'Przechowywanie id użytkownika';
$string['privacy:metadata:devicetype'] = 'Przechowywanie informacji czy używane urządzenie to komputer, tablet czy smartfon';
$string['privacy:metadata:block_coursetoc'] = 'Table of Contents SpolEd block';