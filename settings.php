<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Add page to admin menu.
 *
 * @package    local_stickynotes
 * @copyright  2022 Juan Felipe Orozco Escobar
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settingspage = new admin_settingpage('managelocalstickynotes', new lang_string('pluginname', 'local_stickynotes'));
    if ($ADMIN->fulltree) {
        $settingspage->add(new admin_setting_configcheckbox(
                'local_stickynotes/showinnavigationdrawer',
                new lang_string('showinnavigation', 'local_stickynotes'),
                new lang_string('showinnavigation_desc', 'local_stickynotes'),
                1
        ));
    }
    $ADMIN->add('localplugins', $settingspage);
}
