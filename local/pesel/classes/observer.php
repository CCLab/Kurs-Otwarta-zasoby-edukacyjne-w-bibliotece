<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Event observer for local_pesel.
 */
class local_pesel_observer
{
    public static function check_pesel(\core\event\base $event) {
        global $USER;
        if (isloggedin() && !isguestuser() && $USER && empty($USER->idnumber) && !is_siteadmin() &&
                !empty($USER->profile_field_requiredIdNumber)) {
            $url = new moodle_url('/local/pesel/index.php');
            redirect($url);
        }
    }
}
