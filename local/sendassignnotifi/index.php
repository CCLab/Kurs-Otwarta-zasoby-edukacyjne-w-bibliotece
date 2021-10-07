<?php

require(__DIR__.'/../../config.php');
require_once($CFG->dirroot . '/local/sendassignnotifi/lib.php');


$PAGE->set_url('/local/sendassignnotifi');
$context = context_course::instance(SITEID);
$PAGE->set_context($context);

require_login();
require_capability('moodle/site:config', $context);


echo $OUTPUT->header();
local_sendassignnotifi();
echo $OUTPUT->footer();
