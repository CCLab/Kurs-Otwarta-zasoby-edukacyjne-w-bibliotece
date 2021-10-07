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

defined('MOODLE_INTERNAL') || die();

    /**
     * Generowanie topicku
     * @return array
     */
    function generate_topic($topicNum, $topicRepo, $topicCollapse, $topicIcon, $topicLine, $subTopicRepo, $topicAriaExp)
    {
        global $PAGE;

        //Dodanie otwarcia topicku jeśli jesteś na stronie topicu lekcji
        if ($PAGE->pagetype == 'mod-lesson-view') {


            $lessonID = 'id=' . $PAGE->url->get_param('id');
            $pattern = '/\bid=\d*/';

            if (preg_match($pattern, $topicLine[1], $match) ) {
                $tocLessionID = $match[0];
            } else {
                $tocLessionID = 'id=null';
            }

            if ($tocLessionID == $lessonID) {
                $topicCollapse = 'show';
                $topicIcon = 'fa-angle-up';
                $topicAriaExp = 'true';
            } else {
                $topicCollapse = '';
                $topicIcon = 'fa-angle-down';
                $topicAriaExp = 'false';
            }
        };

        // Zabezpieczenie przed pustym subtopickiem
        if (empty($subTopicRepo[$topicNum])) {
            $subTopicRepo[$topicNum] = array();
        };

        //Ostatnie dodanie do TOC
        return  array('topicTitle' => $topicRepo[$topicNum], 'topicID' => $topicNum, 'topicCollapse' => $topicCollapse, 'topicIcon' => $topicIcon, 'subTopicRepo' => $subTopicRepo[$topicNum], 'topicAriaExp' => $topicAriaExp);

    }