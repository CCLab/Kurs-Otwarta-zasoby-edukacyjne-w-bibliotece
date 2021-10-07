<?php

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_accessibility';  // To check on upgrade, that module sits in correct place
$plugin->version   = 2018052100;        // The current module version (Date: YYYYMMDDXX)
$plugin->requires  = 2013040500;        // Requires Moodle version 2.5.
$plugin->release   = '1.2.1 (2018052100)';
$plugin->maturity  = MATURITY_STABLE;
$plugin->cron      = 0;
