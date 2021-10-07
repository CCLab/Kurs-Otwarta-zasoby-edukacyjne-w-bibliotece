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

//Wczytywanie stopki
$hasfootnote = get_config('theme_spoled', 'footnote');

//Sprawdzenie czy strona główna jest po polsku - Jesli tak wyświet link do kursu PL z ustawień
if (current_language() == 'pl') {
    $slidercourseid = get_config('theme_spoled', 'sliderbtn');
} else {
    $slidercourseid = get_config('theme_spoled', 'sliderbtneng');
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}

// Załadowanie filmu tła z ustawiń
$sliderbg = $PAGE->theme->setting_file_url('sliderbgvid', 'sliderbgvid');

// Jeśli film tła nie został dodany w ustawieniach to zostanie nadany domyślne video z szablonu
if (empty($sliderbg)) {
$video_urlmp4 = new moodle_url('/theme/spoled/pix/movie_defaults.mp4'); }
else {
$video_urlmp4 = $sliderbg;
};

// Ukrycie burgerkowego menu dla niezalogowanych albo gości
$burgermenu = false;
if (!isloggedin() or isguestuser()) {
    $burgermenu = true;
    $extraclasses[] .= 'drawer-hide';
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$blockscenter = $OUTPUT->blocks('side-center');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$hasblockscenter = strpos($blockscenter, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'spoledlogocc' => $OUTPUT->image_url('logo_CC', 'theme'),
    'spoledlogoccby' => $OUTPUT->image_url('ccby', 'theme'),
    'spoledlogohewlett' => $OUTPUT->image_url('hewlett_logo', 'theme'),
    'spoledheaderhome' => $OUTPUT->image_url('header-home2', 'theme'),
    'spoledlogoopensociety' => $OUTPUT->image_url('Open-Society-Foundations_2', 'theme'),
    'footnote' => format_text($hasfootnote, FORMAT_HTML, ['noclean' => true]),
    'videobgmp4' => $video_urlmp4,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'sidecenterblocks' => $blockscenter,
    'hascenterblocks' => $hasblockscenter,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'slidercourseid' => $slidercourseid,
    'burgermenu' => $burgermenu,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
];

$nav = $PAGE->flatnav;
$templatecontext['flatnavigation'] = $nav;
$templatecontext['firstcollectionlabel'] = $nav->get_collectionlabel();
echo $OUTPUT->render_from_template('theme_spoled/frontpage', $templatecontext);
