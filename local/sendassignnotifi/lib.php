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

defined('MOODLE_INTERNAL') || die();


function local_sendassignnotifi() {
    global $DB, $CFG;

    $now = time() - 86400;
    $from = mktime(0, 0, 0, date('n', $now), date('j', $now), date('Y', $now));
    $to = mktime(23, 59, 59, date('n', $now), date('j', $now), date('Y', $now));
    $supportuser = core_user::get_support_user();

    $role = $DB->get_record('role', ['shortname' => 'teacher']);
    $module = $DB->get_record('modules', ['name' => 'assign']);
    $courses = $DB->get_records('course', ['visible' => true]);
    foreach ($courses as $course) {
        if($course->id === '1')
            continue;

        if($groups = $DB->get_records('groups', ['courseid' => $course->id])) {
            $groupids = [];
            foreach ($groups as $group) {
                $groupids[] = $group->id;
            }
        } else {
            return true;
        }


        $context = context_course::instance($course->id);
        $roleteachers = get_users_from_role_on_context($role, $context);
        foreach($roleteachers as $roleteacher) {
            if($teacher = $DB->get_record('user', ['id' => $roleteacher->userid, 'suspended' => 0, 'deleted' => 0])) {
                $updateassigns = [];

                $teachergroups =
                    $DB->get_records_sql("SELECT groupid FROM {groups_members} WHERE userid = ? AND groupid IN (".implode(',',$groupids).")", [$teacher->id]);
                $teachergroupids = [];
                foreach($teachergroups as $teachergroups)
                {
                    $teachergroupids[] = $teachergroups->groupid;
                }

                if($teachergroupids) {
                    $sql = "SELECT * FROM {assign_submission}
                        WHERE status = 'submitted'
                        AND assignment IN (SELECT id FROM {assign} WHERE course = ?)
                        AND userid IN (SELECT userid FROM {groups_members} WHERE groupid IN (".implode(',',$teachergroupids)."))
                        AND ((timecreated >= ? AND timecreated <= ?) OR (timemodified >= ? AND timemodified <= ?))";
                    $params = [$course->id, $from, $to, $from, $to];

                    $assigns = $DB->get_records_sql($sql, $params);
                    if($assigns){
                        foreach($assigns as $assign) {
                            $updateassigns[] = $assign;

                        }
                    }
                }

                if($updateassigns) {
                    $emailemessage = '';
                    $emailtitle = get_string('emailtitlesubmissionupdated', 'local_sendassignnotifi');

                    foreach ($updateassigns as $updateassign) {
                        $user = $DB->get_record('user', ['id' => $updateassign->userid]);
                        $assign = $DB->get_record('assign', ['id' => $updateassign->assignment]);
                        $coursemodule = $DB->get_record('course_modules',
                            ['course' => $course->id, 'module' => $module->id, 'instance' => $updateassign->assignment]);

                        $a = new stdClass();
                        $a->assignment = '<a href="'.$CFG->wwwroot.'/mod/assign/view.php?id='.$coursemodule->id.'&action=grader&userid='.$updateassign->userid.'">'.$assign->name.'</a>';
                        $a->username = fullname($user, true);

                        $emailemessage .= '<br>'.get_string('emailsubmissionupdated', 'local_sendassignnotifi', $a);
                    }
                    //echo $emailemessage .'<br>';

                    $emailemessagehtml = text_to_html($emailemessage, null, false, true);

                    email_to_user($teacher,  $supportuser, $emailtitle, $emailemessage, $emailemessagehtml);
                }
            }
        }
    }


    return true;
}


