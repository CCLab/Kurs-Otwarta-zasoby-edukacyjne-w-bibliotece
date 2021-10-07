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
 * Pesel checker.
 *
 * @package   local_pesel
 * @copyright 2020 INTERSIEC.com.pl
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');

require_login();

require 'pesel_form.php';

// Deciding where to send the user back in most cases.
$returnurl = $CFG->wwwroot;
$baseurl = '/local/pesel/index.php';
$PAGE->set_url($baseurl);
$systemcontext   = context_system::instance();
$PAGE->set_context($systemcontext);
$PAGE->navbar->add('Uzupełnij numer PESEL', $baseurl);

if (!isloggedin() and !isguestuser()) {
    require_login();
}

if ($USER && !empty($USER->idnumber)) {
    redirect($returnurl);
}

$mform = new pesel_form();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($data = $mform->get_data()) {
    $user = $DB->get_record('user', ['id' => $USER->id]);
    $user->idnumber = $data->pesel;
    $DB->update_record('user', $user);

    if ($USER->id == $user->id) {
        $USER->idnumber = $user->idnumber;
    }

    // Trigger event.
    \core\event\user_updated::create_from_userid($user->id)->trigger();

    redirect($returnurl);
}

$header = get_string('pesel_insert', 'local_pesel');
$title = get_string('pesel', 'local_pesel');
$PAGE->set_title($title);
$PAGE->set_heading($header);
echo $OUTPUT->header();
echo $OUTPUT->heading($title);
$mform->display();
echo $OUTPUT->footer();
