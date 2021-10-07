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

$string['pluginname'] = 'Table of Contents SpolEd';
$string['coursetoc'] = 'Table of Contents';
$string['resourcestoc'] = 'Resources';
$string['blockstring'] = 'teststring';
$string['blocktoc'] = 'Lesson contents';
$string['blocktoc_help'] = 'Jedna linia to jeden rozdział/podrozdział. Jeśli na początku jest myślnik <strong>"-"</strong> linia zostanie rozpoznana jako podrozdział.
<br>Nazwę rozdziału/podrozdział odzielaj znakiem <strong>|</strong> od linku do niego kierującego.
<br>
<br>Przykład:
<br>1. Rozdział a|/?redirect=0
<br>- Podrozdział 1|/?redirect=0
<br>2. Rozdział b|/?redirect=0
<br>- Podrozdział 1|/?redirect=0
<br>- Podrozdział 2|/?redirect=0';
$string['blocktoc_def'] = '1. Rozdział a|/?redirect=0
- Podrozdział 1|/?redirect=0
2. Rozdział b|/?redirect=0
- Podrozdział 1|/?redirect=0
- Podrozdział 2|/?redirect=0';
$string['blocktype'] = 'Type';
$string['blocktype_course'] = 'Course';
$string['blocktype_resources'] = 'Resource';
$string['coursetoc:addinstance'] = 'Add new Table of Contents SpolEd block';
$string['coursetoc:myaddinstance'] = 'Add a new Table of Contents SpolEd block to the My Moodle page';
$string['privacy:metadata:userid'] = 'User id storage';
$string['privacy:metadata:devicetype'] = 'Storage of information such as a using device is computer, tablet or smartphone';
$string['privacy:metadata:block_coursetoc'] = 'Table of Contents SpolEd block';
