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

function intmark($start_time, $string) {
    // End clock time in seconds
    $end_time = microtime(true);

    // Calculate script execution time
    $execution_time = round( ($end_time - $start_time),3 );

    return "<tr><th>$string</th><td>".$execution_time."</td></tr>";
}

function generate_fake_data(){
    $data = [];
    for ($i = 1; $i <= 6; $i++) {
        $data[$i] = new stdClass();
        $data[$i]->name = generateRandomString(rand(4,15));
        $data[$i]->email = generateRandomString(7)."@".generateRandomString(7).".com";
        $story = "";
        for ($l = 1; $l <= rand(10,20); $l++) {
            $story .= generateRandomString(rand(7,15))." ";
        }
        $data[$i]->story = $story;
        $data[$i]->timecreated = time();
    }
    return $data;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}