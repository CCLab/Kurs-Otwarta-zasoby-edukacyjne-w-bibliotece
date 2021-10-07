<?php

namespace local_sendassignnotifi\task;

class cron_sendassignnotifi extends \core\task\scheduled_task
{

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name()
    {
        return get_string('sendassignnotifi', 'local_sendassignnotifi');
    }

    /**
     * Run forum cron.
     */
    public function execute()
    {
        global $CFG;
        require_once($CFG->dirroot . '/local/sendassignnotifi/lib.php');
        local_sendassignnotifi();
    }
}
