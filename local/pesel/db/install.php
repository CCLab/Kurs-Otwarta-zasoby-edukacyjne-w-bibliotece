<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_pesel_install() {
    global $DB;

    $f1 = 'requiredIdNumber';
    if (!$field = $DB->get_record('user_info_field', array('shortname' => $f1))) {
        $field = new stdClass();
        $field->shortname = $f1;
        $field->name = 'Wymagany PESEL';
        $field->datatype = 'checkbox';
        $field->description = '';
        $field->descriptionformat = FORMAT_HTML;
        $field->defaultdata = '1';
        $field->defaultdataformat = 0;
        $field->categoryid = 1;
        $field->required = 0;
        $field->locked = 0;
        $field->visible = 0;
        $field->forceunique = 0;
        $field->signup = 0;
        $field->param1 = 30;
        $field->param2 = 2048;

        $DB->insert_record('user_info_field', $field);
    }



}
