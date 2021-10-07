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

/**
 * spoled.
 *
 * @package    theme_spoled
 * @copyright  2019 INTERSIEĆ
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/libs/admin_confightmleditor.php');
require_once(__DIR__.'/lib.php');

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new theme_boost_admin_settingspage_tabs('themesettingspoled', get_string('configtitle', 'theme_spoled'));
    $page = new admin_settingpage('theme_spoled_general', get_string('generalsettings', 'theme_spoled'));

    // Preset.
    $name = 'theme_spoled/preset';
    $title = get_string('preset', 'theme_spoled');
    $description = get_string('preset_desc', 'theme_spoled');
    $default = 'spoled.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_spoled', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets.
    $choices['spoled.scss'] = 'spoled.scss';

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footnote.
    $name = 'theme_spoled/footnote';
    $title = get_string('footnote', 'theme_spoled');
    $description = get_string('footnotedesc', 'theme_spoled');
    $default = '';
    $setting = new spoled_setting_confightmleditor($name, $title, $description, $default);
    $page->add($setting);

    // Variable $body-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_spoled/brandcolor';
    $title = get_string('brandcolor', 'theme_spoled');
    $description = get_string('brandcolor_desc', 'theme_spoled');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    // Slider settings.
    $page = new admin_settingpage('theme_spoled_slider', get_string('slidersettings', 'theme_spoled'));


    $name = 'theme_spoled/sliderbtn';
    $title = get_string('sliderbtn', 'theme_spoled');
    $description = get_string('sliderbtn_desc', 'theme_spoled');
    $setting = new admin_setting_configtext($name, $title, $description, '/mod/lesson/view.php?id=5');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_spoled/sliderbtneng';
    $title = get_string('sliderbtneng', 'theme_spoled');
    $description = get_string('sliderbtneng_desc', 'theme_spoled');
    $setting = new admin_setting_configtext($name, $title, $description, '/mod/lesson/view.php?id=5');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_spoled/sliderbgvid';
    $title = get_string('sliderbgvid', 'theme_spoled');
    $description = get_string('sliderbgvid_desc', 'theme_spoled');
    $options = array('accepted_types' => array('.mp4'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'sliderbgvid', 0, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // Must add the page after definiting all the settings!
    $settings->add($page);

    // Advanced settings.
    $page = new admin_settingpage('theme_spoled_advanced', get_string('advancedsettings', 'theme_spoled'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_spoled/scsspre',
        get_string('rawscsspre', 'theme_spoled'), get_string('rawscsspre_desc', 'theme_spoled'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_spoled/scss', get_string('rawscss', 'theme_spoled'),
        get_string('rawscss_desc', 'theme_spoled'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}
