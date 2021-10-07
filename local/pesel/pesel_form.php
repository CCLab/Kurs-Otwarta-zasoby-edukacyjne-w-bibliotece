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

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    //  It must be included from a Moodle page.
}

require_once($CFG->dirroot.'/lib/formslib.php');
require 'pesel_check.php';


class pesel_form extends moodleform {

    /**
     * Define the form.
     */
    public function definition () {
        $mform = $this->_form;
        $mform->updateAttributes(['novalidate' => null]);

        $mform->addElement('text', 'pesel', null,
            [
                'autofocus' => null,
                'placeholder' => get_string('pesel_your_pesel', 'local_pesel'),
                'maxlength' => 11,
                'minlength' => 11,
                'size' => 25]);
        $mform->setType('pesel', PARAM_ALPHANUM);

        $mform->addRule('pesel', null, 'required', null, 'client');
        $mform->addRule('pesel', get_string('pesel_only_numbers', 'local_pesel'),
            'numeric', null, 'client');
        $mform->addRule('pesel', get_string('minimumchars', 'local_pesel', 11), 'minlength', 11, 'client');
        $this->add_action_buttons(false, get_string('save'));
    }

    /**
     * Validate incoming form data.
     * @param array $usernew
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = [];

        if(empty($data['pesel'])){
            $errors[] = get_string('pesel_required', 'local_pesel');
        }
        if(!PeselChecker::verify($data['pesel'])){
            $errors[] = get_string('pesel_invalid_format', 'local_pesel');
        }
        if(!empty($errors)){
            $errors['pesel'] = implode('<br>', $errors);
        }

        return $errors;
    }
}


