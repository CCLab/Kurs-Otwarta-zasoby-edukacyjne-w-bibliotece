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
 * A two column layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

if (isloggedin()) {
    $navdraweropen = false;
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}

//Wczytywanie stopki
$hasfootnote = get_config('theme_spoled', 'footnote');

$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$blockshtml = $OUTPUT->blocks('side-pre');
$blockscenter = $OUTPUT->blocks('side-center');
$blockspost = $OUTPUT->blocks('side-post');

$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$hasblockscenter = strpos($blockscenter, 'data-block=') !== false;
$hasblockspost = strpos($blockspost, 'data-block=') !== false;

// Ukrycie burgerkowego menu
$burgermenu = false;
if (!isloggedin() or isguestuser()) {
    $burgermenu = true;
}

$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'spoledlogocc' => $OUTPUT->image_url('logo_CC', 'theme'),
    'spoledlogoccby' => $OUTPUT->image_url('ccby', 'theme'),
    'spoledlogohewlett' => $OUTPUT->image_url('hewlett_logo', 'theme'),
    'spoledlogoopensociety' => $OUTPUT->image_url('Open-Society-Foundations_2', 'theme'),
    'footnote' => format_text($hasfootnote, FORMAT_HTML, ['noclean' => true]),
    'sidepreblocks' => $blockshtml,
    'sidecenterblocks' => $blockscenter,
    'sidepostblocks' => $blockspost,
    'hasblocks' => $hasblocks,
    'hascenterblocks' => $hasblockscenter,
    'haspostblocks' => $hasblockspost,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'burgermenu' => $burgermenu,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
];

$nav = $PAGE->flatnav;
$templatecontext['flatnavigation'] = $nav;
$templatecontext['firstcollectionlabel'] = $nav->get_collectionlabel();

// Sprawdzenie czy występuje ID jeli tak czy jest to ID strony wskazanej jako strona "o projekcie" i czy jest to aktywnosc mod-page-view
// Jesli tak - zostaje wyswietlony template projectpage

if ($PAGE->pagelayout == 'spoledpage') {
    echo $OUTPUT->render_from_template('theme_spoled/spoledpage', $templatecontext);
} else {
    echo $OUTPUT->render_from_template('theme_spoled/columns2', $templatecontext);
}
echo '<!-- PAGETYPE '.$PAGE->pagetype.' -->';
