<?php

require_once('classes/output/core_user/myprofile/renderer.php');

class theme_spoled_core_renderer extends core_renderer
{


    public function heading($text, $level = 2, $classes = null, $id = null)
    {
        global $PAGE;

            if ($PAGE->pagelayout == 'spoledpage') {
                $id = 'about-project';
                $content  = html_writer::start_tag('div', array('class' => 'about-project'));
                $content .= parent::heading($text, 1, $classes, $id);
                $content .= html_writer::end_tag('div');
                return $content;
            } else {
                $content = parent::heading($text, $level, $classes, $id);
                return $content;
            }
    }

    public function box($contents, $classes = 'generalbox', $id = null, $attributes = array())
    {
        global $PAGE;

            if ($PAGE->pagelayout == 'spoledpage') {
                $classes = 'generalbox-abp';
                $content  = html_writer::start_tag('div', array('class' => 'col-md-8 col-md-offset-2 aboutprojectpage'));
                $content .= parent::box($contents, $classes, $id, $attributes);
                $content .= html_writer::end_tag('div');
                return $content;
            } else {
                $content = parent::box($contents, $classes, $id, $attributes);
                return $content;
            }
    }

    /**
     * Output the place a skip link goes to.
     * Poprawka dla dostepności, aby na stronie głównej generował się inne id maincontent dla sekcji gdzie są dodawane aktywności
     * id maincontent zostaje przeniesione na slider, aby  osoby niewidome mogły od niego zacząć
     *
     * @param string $id The target name from the corresponding $PAGE->requires->skip_link_to($target) call.
     * @return string the HTML to output.
     */
    public function skip_link_target($id = null) {
        global $PAGE;
        if  ($PAGE->pagelayout == 'frontpage' && $PAGE->pagelayout == 'spoledpage'){
            return html_writer::span('', '', array('id' => 'activity_content'));
        }
        else
        {
            return html_writer::span('', '', array('id' => $id));
        }
    }

	 /**
	 *
	 * Nadpisanie breadcrumbs.
	 */
    public function navbar() {
        global $PAGE;
        $syscontext = context_system::instance();
        //if (!isloggedin() or isguestuser()) {
        if (!isloggedin() or !has_capability('moodle/site:config', $syscontext) and $PAGE->pagelayout != 'mydashboard') {
            $items = $this->page->navbar->get_items();
            foreach ($items as $item) {
                $item->hideicon = true;
                // Do not include the section item.
                if (in_array($item->type, array(global_navigation::TYPE_SECTION))) {
                    continue;
                } else if (in_array($item->type, array(global_navigation::TYPE_CATEGORY, global_navigation::TYPE_COURSE, global_navigation::TYPE_ACTIVITY, global_navigation::TYPE_RESOURCE)) and strlen($item->text) > 40) {
                    // If the text is longer than 40 characters then truncate.
                    $item->text = substr($item->text, 0, 40) . "...";
                }
                $breadcrumbs[] = $this->render($item);
            }

            if (isset($breadcrumbs) && !empty($breadcrumbs)) { // Poprawione KŁ.
                $first = reset($breadcrumbs);
                $last = end($breadcrumbs);
                $breadcrumbs_simple[] = $first;
                $breadcrumbs_simple[] .= $last;
            } else {
                $breadcrumbs_simple[] = '';
            }


            $divider = '<span style="margin: 0px 10px 0px 10px;" class="divider">/</span>';
            $list_items = '<li>'.join(" $divider</li><li>", $breadcrumbs_simple).'</li>';
            $title = '<span class="accesshide">'.get_string('pagepath').'</span>';
            return $title . "<ul class=\"breadcrumb\"><li>$list_items</li></ul>";
        }
        else{
            return $this->render_from_template('core/navbar', $this->page->navbar);
        }
    }

	 /**
	 * Wrapper for header elements.
	 *
	 * @return string HTML to display minimal header frontpage.
	 */
    public function minimal_header() {
        global $PAGE;

        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($PAGE->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        return $this->render_from_template('core/minimal_header', $header);
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = get_string('loggedinnot', 'moodle');
            if (!$loginpage) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );

        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {

                $returnstr .= " (<a href=\"$CFG->wwwroot/login/logout.php?sesskey=".sesskey()."\">".get_string('logout').'</a>)';
            }

            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }

        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext mr-1') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_action_label(get_string('usermenu'));
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, '', null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                    $value->imgsrc,
                                    $value->title,
                                    array('class' => 'iconsmall')
                                ) . $value->title;
                        }

                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::div(
            $this->render($am),
            $usermenuclasses
        );
    }



}
include_once($CFG->dirroot . "/mod/lesson/renderer.php");

class theme_spoled_mod_lesson_renderer extends mod_lesson_renderer {


        /**
     * Returns the HTML for displaying the end of lesson page.
     *
     * @param  lesson $lesson lesson instance
     * @param  stdclass $data lesson data to be rendered
     * @return string         HTML contents
     */
    public function display_eol_page(lesson $lesson, $data) {

        $output = '';
        $canmanage = $lesson->can_manage();
        $course = $lesson->courserecord;

        if ($lesson->custom && !$canmanage && (($data->gradeinfo->nquestions < $lesson->minquestions))) {
            $output .= $this->box_start('generalbox boxaligncenter');
        }

        if ($data->gradelesson) {
            // We are using level 3 header because the page title is a sub-heading of lesson title (MDL-30911).
            $output .= $this->heading(get_string("congratulations", "lesson"), 3);
            $output .= $this->box_start('generalbox boxaligncenter');
        }

        if ($data->notenoughtimespent !== false) {
            $output .= $this->paragraph(get_string("notenoughtimespent", "lesson", $data->notenoughtimespent), 'center');
        }

        if ($data->numberofpagesviewed !== false) {
            $output .= $this->paragraph(get_string("numberofpagesviewed", "lesson", $data->numberofpagesviewed), 'center');
        }
        if ($data->youshouldview !== false) {
            $output .= $this->paragraph(get_string("youshouldview", "lesson", $data->youshouldview), 'center');
        }
        if ($data->numberofcorrectanswers !== false) {
            $output .= $this->paragraph(get_string("numberofcorrectanswers", "lesson", $data->numberofcorrectanswers), 'center');
        }

        if ($data->displayscorewithessays !== false) {
            $output .= $this->box(get_string("displayscorewithessays", "lesson", $data->displayscorewithessays), 'center');
        } else if ($data->displayscorewithoutessays !== false) {
            $output .= $this->box(get_string("displayscorewithoutessays", "lesson", $data->displayscorewithoutessays), 'center');
        }

        if ($data->yourcurrentgradeisoutof !== false) {
            $output .= $this->paragraph(get_string("yourcurrentgradeisoutof", "lesson", $data->yourcurrentgradeisoutof), 'center');
        }
        if ($data->eolstudentoutoftimenoanswers !== false) {
            $output .= $this->paragraph(get_string("eolstudentoutoftimenoanswers", "lesson"));
        }
        if ($data->welldone !== false) {
            $output .= $this->paragraph(get_string("welldone", "lesson"));
        }

        if ($data->progresscompleted !== false) {
            $output .= $this->progress_bar($lesson, $data->progresscompleted);
        }

        if ($data->displayofgrade !== false) {
            $output .= $this->paragraph(get_string("displayofgrade", "lesson"), 'center');
        }

        $output .= $this->box_end(); // End of Lesson button to Continue.

        if ($data->reviewlesson !== false) {
            $output .= html_writer::link($data->reviewlesson, get_string('reviewlesson', 'lesson'), array('class' => 'centerpadded lessonbutton standardbutton p-r-1'));
        }
        if ($data->modattemptsnoteacher !== false) {
            $output .= $this->paragraph(get_string("modattemptsnoteacher", "lesson"), 'centerpadded');
        }

        if ($data->activitylink !== false) {
            $output .= $data->activitylink;
        }

        $output .= html_writer::start_tag('div', array('class' => 'd-flex justify-content-center'));


            // Get a list of all the activities in the course.
            $course = $this->page->cm->get_course();

            $modules = get_fast_modinfo($course->id)->get_cms();

            // Put the modules into an array in order by the position they are shown in the course.
            $mods = [];
            $activitylist = [];
            foreach ($modules as $module) {
                // Only add activities the user can access, aren't in stealth mode and have a url (eg. mod_label does not).
                if (!$module->uservisible || empty($module->url)) {
                    continue;
                }
                $mods[$module->id] = $module;

                // No need to add the current module to the list for the activity dropdown menu.
                if ($module->id == $this->page->cm->id) {
                    continue;
                }
                // Module name.
                $modname = $module->get_formatted_name();
                // Display the hidden text if necessary.
                if (!$module->visible) {
                    $modname .= ' ' . get_string('hiddenwithbrackets');
                }
                // Module URL.
                $linkurl = new moodle_url($module->url, array('forceview' => 1));
                // Add module URL (as key) and name (as value) to the activity list array.
                $activitylist[$linkurl->out(false)] = $modname;
            }


            $nummods = count($mods);

                if ($nummods > 0) {
                // Get an array of just the course module ids used to get the cmid value based on their position in the course.
                $modids = array_keys($mods);

                // Get the position in the array of the course module we are viewing.
                $position = array_search($this->page->cm->id, $modids);
                $nextmod = null;

                // Check if we have a next mod to show.
                if ($position < ($nummods - 1)) {
                    $nextmod = $mods[$modids[$position + 1]];
                }

                $activitynav = new \core_course\output\activity_navigation(null, $nextmod, null);

            if (!empty($activitynav->nextlink)){
                $url = new moodle_url($activitynav->nextlink->url);
                $output .= html_writer::link($url, format_string($activitynav->nextlink->text, true),
                        array('class' => 'btn btn-secondary mb-3'));
                }
            }
            $output .= html_writer::end_tag('div');

        $output .= html_writer::start_tag('div', array('class' => 'd-flex justify-content-center'));
        $url = new moodle_url('/course/view.php', array('id' => $course->id));
        $output .= html_writer::link($url, get_string('returnto', 'lesson', format_string($course->fullname, true)),
                array('class' => 'btn btn-secondary'));
                $output .= html_writer::end_tag('div');

        if (has_capability('gradereport/user:view', context_course::instance($course->id))
                && $course->showgrades && $lesson->grade != 0 && !$lesson->practice) {
            $url = new moodle_url('/grade/index.php', array('id' => $course->id));
            $output .= html_writer::link($url, get_string('viewgrades', 'lesson'),
                array('class' => 'centerpadded lessonbutton standardbutton p-r-1'));
        }
        return $output;
    }
}
