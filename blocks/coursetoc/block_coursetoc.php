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

require_once($CFG->dirroot.'/blocks/coursetoc/lib.php');

class block_coursetoc extends block_base
{
    public function init()
    {

        $this->title = get_string('coursetoc', 'block_coursetoc');
    }

    public function get_content()
    {
        global $OUTPUT;

        $this->page->requires->js_call_amd('block_coursetoc/spoledtoc');



        // Ustawienie typu spisu treści
        if (!empty($this->config->course)) {
            $typetoc = $this->config->course;
        } else {
            $typetoc = 0;
        }

        if (!empty($this->config->title)) {

            ///////////////////////////////
            ////Generowanie spisu treści///
            //////////////////////////////

            //Wczytanie konfiguracji TOC, przerobienie na tablice i usunięcie ew. pustych wieszy
            $tocTextArea = $this->config->title;
            $ids = explode(PHP_EOL, $tocTextArea);
            $ids = array_values(array_filter($ids));

            //Czy Topic został otwarty
            $topicOpen = False;

            // Licznik topików i subtopików
            $i = 0;
            $topicNum = 0;
            $subTopicNum = 0;
            // tablica subtopików
            $subTopicRepo = array();
            $subTopicLine = array();
            $topicRepo = array();
            $topicLine = array();
            $tocRepo = array();

            while ($i != count($ids)) {
                $lineTOC = $ids[$i];
                $firstCharTOCline = $lineTOC[0];

                //Jeśli nie ma myślnika to Topic
                if ($firstCharTOCline != "-") {

                    //Domyślne wartości dwóch pozycji
                    $topicCollapse = '';
                    $topicIcon = 'fa-angle-down';
                    $topicAriaExp = 'false';

                    // Jeśli topic jest otwarty to zamknij.
                    if ($topicOpen == True) {

                        array_push($tocRepo, generate_topic($topicNum, $topicRepo, $topicCollapse, $topicIcon, $topicLine, $subTopicRepo, $topicAriaExp));

                        $subTopicLine = array();
                        $topicOpen = False;
                        $subTopicNum = 0;
                        $topicNum++;
                    }

                    // Wyodrębnienia nazwy i linka
                    $topicLine = explode("|", $lineTOC);
                    $topicRepo[$topicNum] = $topicLine[0];

                    //Zabezpieczenie przed brakime podanego linku
                    if (count($topicLine) == 1) {
                        $topicLine[1] = "#heading" . $topicNum;
                    };

                    $topicOpen = True;

                    //Jeśli ma myślnika to SubTopic
                } else {

                    // Wyodrębnienia nazwy i linka
                    $subtopiclineitem = explode("|", $lineTOC);
                    $subtopiclineitem[0] = ltrim($subtopiclineitem[0], '- ');

                    //Zabezpieczenie przed brakime podanego linku
                    if (count($subtopiclineitem) == 1) {
                        $subtopiclineitem[1] = "#heading" . $topicNum;
                    };

                    //Dodanie do arraya subTopicLine wartości nazwy i linku
                    array_push($subTopicLine, array('subTitle' => $subtopiclineitem[0], 'subHref' => $subtopiclineitem[1]));

                    $subTopicRepo[$topicNum] = $subTopicLine;

                    $subTopicNum++;
                }

                //Interacja lini TextArea +1
                $i++;
            }
            array_push($tocRepo, generate_topic($topicNum, $topicRepo, $topicCollapse, $topicIcon, $topicLine, $subTopicRepo, $topicAriaExp));

        }

        $this->content =  new stdClass;

        // Generowanie rozwiniętego menu w lekcji
        $context1 = $this->page->context;

        $this->content->text = $OUTPUT->render_from_template('block_coursetoc/spoledtoc', new stdClass());


        // Przy pierwszym dodaniu jeśli menu puste dodaj treść przydkładową
        if (empty($tocRepo)) {

            // Przykładowa treść bloku odrazu po dodaniu
            $tocRepo = [
                [
                    "topicTitle" => "1. Rozdział a",
                    "topicID" => 0,
                    "topicCollapse" => "",
                    "topicIcon" => "fa-angle-down",
                    "subTopicRepo" => [
                        [
                            "subTitle" => "Podrozdział 1",
                            "subHref" => "/?redirect=0"
                        ]
                    ]
                ],
                [
                    "topicTitle" => "2. Rozdział b",
                    "topicID" => 1,
                    "topicCollapse" => "",
                    "topicIcon" => "fa-angle-down",
                    "subTopicRepo" => [
                        [
                            "subTitle" => "Podrozdział 1",
                            "subHref" => "/?redirect=0"
                        ],
                        [
                            "subTitle" => "Podrozdział 2",
                            "subHref" => "/?redirect=0"
                        ]
                    ]
                ]
            ];
        };


        $this->title = get_string('coursetoc', 'block_coursetoc');


        $context = [
            'tocRepo' => $tocRepo,

        ];

        $this->content->text = $OUTPUT->render_from_template('block_coursetoc/spoledtoc', $context);

        return $this->content;
    }
}
