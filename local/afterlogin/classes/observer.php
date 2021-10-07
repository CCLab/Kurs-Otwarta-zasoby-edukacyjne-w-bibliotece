<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/group/lib.php');

/**
 * Event observer for local_timer.
 */
class local_afterlogin_observer
{
    public static function after_loggedin(\core\event\user_loggedin $event) {
        global $DB;

        $userid = $event->userid;

        $sql = 'SELECT g.id, g.courseid 
                FROM {groups_members} gm 
                LEFT JOIN {groups} g ON gm.groupid = g.id 
                WHERE gm.userid = :user';

        $groups = $DB->get_records_sql($sql, ['user' => $userid ]);
        foreach ($groups as $group) {
            $roles = groups_get_members_by_role($group->id, $group->courseid);
            foreach ($roles as $role){

                if(!isset($role->shortname)){
                    continue;
                }

                if($role->shortname == 'teacher'){
                    foreach ($role->users as $mentor) {
                        if($userid == $mentor->id){
                            continue;
                        }

                        $contact = $DB->get_record('message_contacts', ['userid' => $userid, 'contactid' => $mentor->id]);

                        if(!$contact) {
                            $messagecontact = new \stdClass();
                            $messagecontact->userid = $userid;
                            $messagecontact->contactid = $mentor->id;
                            $messagecontact->timecreated = time();
                            $messagecontact->id = $DB->insert_record('message_contacts', $messagecontact);

                            $eventparams = [
                                'objectid' => $messagecontact->id,
                                'userid' => $userid,
                                'relateduserid' => $mentor->id,
                                'context' => \context_user::instance($userid)
                            ];
                            $event = \core\event\message_contact_added::create($eventparams);
                            $event->add_record_snapshot('message_contacts', $messagecontact);
                            $event->trigger();
                        }
                    }
                }
            }
        }
    }
}




global $CFG, $PAGE, $USER;
require_once($CFG->dirroot.'/user/profile/lib.php');
$pagetypes = ['site-index', 'my-index', 'course', 'course-view', 'course-view-topics', 'mod-lesson-view'];
$systemcontext = context_system::instance();
profile_load_data($USER);
if (!has_capability('moodle/site:config', $systemcontext) && isloggedin() && !isguestuser() &&
    in_array($PAGE->pagetype, $pagetypes) && !$PAGE->user_is_editing()) {
    if ($USER && empty($USER->idnumber) && !empty($USER->profile_field_requiredIdNumber)) {
        // Przekieruj użytkownika do edycji profilu.
        $url = new moodle_url('/local/pesel/index.php');
        redirect($url);
    }
}
return false;


if (isloggedin() and !isguestuser()) {
    if($USER && empty($USER->idnumber)){
        if (!is_siteadmin() && !empty($USER->profile) && !empty($USER->profile['requiredIdNumber'])){
            $url = new moodle_url('/local/pesel/index.php');
            redirect($url);
        }
    }
}
