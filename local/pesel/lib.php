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

defined('MOODLE_INTERNAL') || die();

/**
 * Dummy function that makes this file has to be loaded each time Moodle reloads.
 *
 * @throws coding_exception
 * @throws moodle_exception
 */
function local_pesel_extend_navigation()
{
    check_redir();
}

/**
 * Checks that user should or not be redirected.
 *
 * @return bool
 * @throws coding_exception
 */
function check_redir()
{
    global $CFG, $PAGE, $USER;
    require_once($CFG->dirroot . '/user/profile/lib.php');
    $pagetypes = ['site-index', 'my-index', 'course', 'course-view', 'course-view-topics', 'mod-lesson-view'];
    $notpagetypes = ['admin-tool-policy-view', 'local-pesel-index', 'admin-tool-policy-index'];
    $systemcontext = context_system::instance();
    profile_load_data($USER);
    if (!has_capability('moodle/site:config', $systemcontext) && isloggedin() && !isguestuser()
            && !in_array($PAGE->pagetype, $notpagetypes)
            && !$PAGE->user_is_editing()) {
        if ($USER && empty($USER->idnumber) && !empty($USER->profile_field_requiredIdNumber)) {
            $url = new moodle_url('/local/pesel/index.php');
            redirect($url);
        }
    }
    return false;
}
