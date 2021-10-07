<?php

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\core\event\user_loggedin',
        'callback' => 'local_afterlogin_observer::after_loggedin',
    ],
];
