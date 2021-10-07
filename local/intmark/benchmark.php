<?php
$global_start = $start_time = microtime(true);
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
 * INTMark - Moodle Simple Benchmark System.
 *
 * @package   local_intmark
 * @author 2020 INTERSIEC.com.pl
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright (C) 2019 onwards INTERSIEĆ (https://intersiec.com.pl)
 */

// Include config.php
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->dirroot."/lib/completionlib.php");

$returnurl = new moodle_url('/local/intmark/benchmark.php');

$PAGE->set_url($returnurl);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('frontpage');
$PAGE->set_pagetype('admin');
$context = context_system::instance();
$strheading = 'INTMark Benchmark';
$PAGE->navbar->add($strheading);
$PAGE->set_heading($strheading);
$PAGE->set_title($strheading);


echo $OUTPUT->header();
echo html_writer::tag('h1',$strheading);

echo '&nbsp;';
echo html_writer::start_tag('table', ['class'=>'table general-table']);
echo "<tr><th>Operation</th><th>Time taken (sec)</th></tr>";
$url = new moodle_url($CFG->wwwroot . '/local/intmark/benchmark.php');
$users = $DB->get_records('user');
$deleted = $DB->get_records('user', ['deleted'=>1]);

// UsersMark.
//echo html_writer::tag('p', "users: ".count($users));
//echo html_writer::tag('li', "deleted: ".count($deleted));
echo intmark($start_time, 'get users');
$start_time = microtime(true);

// CoursesMark.
/*$courses = $DB->get_records('course');
$enrolments = $completedcourses = 0;
foreach($courses as $course){
    $coursecontext = context_course::instance($course->id);
    foreach($users AS $user){
        if(is_enrolled($coursecontext, $user)){
            $enrolments++;
            $completion = new completion_info($course);
            if($completion->is_course_complete($user->id)){
                $completedcourses++;
            }
        }
    }
}
echo html_writer::tag('li', $enrolments . " enrolments in  ".count($courses));
echo html_writer::tag('li', $completedcourses . " completed courses");
echo intmark($start_time, 'get courses, enrolments, course completions');
$start_time = microtime(true);
*/

// SettingsMark.
$config = $DB->get_records('config');
$conflen = 0;
foreach($config as $conf){
    $conflen += strlen($conf->value);
}
//echo html_writer::tag('li', count($config) . " config benchmark");
//echo html_writer::tag('li', $conflen . " characters of config values length");
echo intmark($start_time, 'get config');
$start_time = microtime(true);


// RecursiveMark.
$Directory = new RecursiveDirectoryIterator($CFG->dirroot);
$Iterator = new RecursiveIteratorIterator($Directory);
$files = array();
foreach ($Iterator as $info) {
    if (preg_match("/[a-zA-Z\d]+\.php/", $info->getPathname())) {
        $files[] = $info->getPathname();
    }
}
//echo html_writer::tag('li', count($files) . " php files inside Moodle");
echo intmark($start_time, 'recursive iterator');
$start_time = microtime(true);

//DatabasaMark.
global $DB;
$dbman = $DB->get_manager();
$table = new xmldb_table('intmark_benchmark');
if ($dbman->table_exists($table)) {
    //$dbman->drop_table($table); // Nie robimy dropa.
}
// Adding fields to table quiz_slot_tags.
$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
$table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
$table->add_field('email', XMLDB_TYPE_CHAR, '255', null, null, null, null);
$table->add_field('story', XMLDB_TYPE_CHAR, '255', null, null, null, null);
$table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
// Conditionally launch create table for quiz_slot_tags.
if (!$dbman->table_exists($table)) {
    $dbman->create_table($table);
}
echo intmark($start_time, 'database table created');
$start_time = microtime(true);

$dbdata = generate_fake_data();
$inserted = 0;
foreach($dbdata as $dbrec){
    if($DB->insert_record('intmark_benchmark', $dbrec)){
        $inserted++;
    }
}
//echo html_writer::tag('li', $inserted . " inserted fake data to database");
echo intmark($start_time, 'database data inserted');
$start_time = microtime(true);


echo intmark($global_start, 'overalltime');
echo html_writer::end_tag('table');
echo '<a class="btn btn-primary" href="'.$url.'"> Uruchom ponownie</a>';

echo $OUTPUT->footer();


