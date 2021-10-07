<?php

defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        'classname' => 'local_sendassignnotifi\task\cron_sendassignnotifi',
        'blocking' => 0,
        'minute' => '55',
        'hour' => '23',
        'day' => '*/1',
        'dayofweek' => '*',
        'month' => '*',
        'disabled' => 0
    )
);
