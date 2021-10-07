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
 * INTMark - Moodle Simple Benchmark System.
 *
 * @package   local_intmark
 * @author 2020 INTERSIEC.com.pl
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright (C) 2019 onwards INTERSIEĆ (https://intersiec.com.pl)
 */
defined('MOODLE_INTERNAL') || die();

$hassiteconfig = has_capability('moodle/site:config', context_system::instance());

$benchmarkstring = get_string('benchmark', 'local_intmark');
$runbenchmarkstring = get_string('benchmarkrun', 'local_intmark');

if($hassiteconfig) {
    $ADMIN->add('root', new admin_category('intmark', $benchmarkstring));
    $ADMIN->add('intmark', new admin_externalpage('intmarkrun', $runbenchmarkstring,
            $CFG->wwwroot.'/local/intmark/benchmark.php'));
}