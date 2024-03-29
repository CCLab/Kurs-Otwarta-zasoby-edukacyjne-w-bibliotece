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
namespace theme_spoled\output\core_user\myprofile;
use \core_user\output\myprofile\node;
use \core_user\output\myprofile\category;

defined('MOODLE_INTERNAL') || die();
class renderer extends \core_user\output\myprofile\renderer {

    /**
     * Render a category.
     *
     * @param category $category
     *
     * @return string
     */
    public function render_category(category $category) {
        $classes = $category->classes;
        //array do spr. ilości elementów w kategorii miscellaneous
        $nodeItem = array();

        if (empty($classes)) {
            $return = \html_writer::start_tag('section', array('class' => 'node_category'));
        } else {
            $return = \html_writer::start_tag('section', array('class' => 'node_category ' . $classes));
        }
        $return .= \html_writer::tag('h3', $category->title);
        $nodes = $category->nodes;
        if (empty($nodes)) {
            // No nodes, nothing to render.
            return '';
        }
        $return .= \html_writer::start_tag('ul');
        foreach ($nodes as $node) {
            $return .= $this->render($node);
            //Dodawanie niepustych elementów do arraya $nodeItem kategorii miscellaneous
            if ($category->name == 'miscellaneous' && !empty($this->render($node)) ) {array_push($nodeItem,$this->render($node));}
        }

        $return .= \html_writer::end_tag('ul');
        $return .= \html_writer::end_tag('section');

        // Jeśli arraya $nodeItem kategorii miscellaneous jest pusty to go nie renderuj
        if ($category->name == 'miscellaneous' && count($nodeItem)==0) {return '';}
        return $return;
    }

    /**
     * Render a node.
     *
     * @param node $node
     *
     * @return string
     */
    public function render_node(node $node) {
        $return = '';

        if ($node->name =="forumposts"){return '';}
        if ($node->name =="forumdiscussions"){return '';}


        if (is_object($node->url)) {
            $header = \html_writer::link($node->url, $node->title);
        } else {
            $header = $node->title;
        }
        $icon = $node->icon;
        if (!empty($icon)) {
            $header .= $this->render($icon);
        }
        $content = $node->content;
        $classes = $node->classes;
        if (!empty($content)) {
            if ($header) {
                // There is some content to display below this make this a header.
                $return = \html_writer::tag('dt', $header);
                $return .= \html_writer::tag('dd', $content);

                $return = \html_writer::tag('dl', $return);
            } else {
                $return = \html_writer::span($content);
            }
            if ($classes) {
                $return = \html_writer::tag('li', $return, array('class' => 'contentnode ' . $classes));
            } else {
                $return = \html_writer::tag('li', $return, array('class' => 'contentnode'));
            }
        } else {
            $return = \html_writer::span($header);
            $return = \html_writer::tag('li', $return, array('class' => $classes));
        }

    return $return;

    }
}