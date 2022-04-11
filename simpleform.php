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
 * Testing a simple form.
 *
 * @package    local_stickynotes
 * @copyright  2022 Juan Felipe Orozco Escobar
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_login();

// Page configuration.
$PAGE->set_url(new moodle_url('/local/stickynotes/simpleform.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Page title');
$username = 'Juan';
$PAGE->set_heading(get_string('pluginname', 'local_stickynotes', $username));
$PAGE->set_pagelayout('standard');

// Rendering page.
echo $OUTPUT->header();
$userinput = optional_param('name', 'World', PARAM_TEXT);

// Page logic.
echo '<h1>Hello '. $userinput . '!</h1>';

if ($userinput != 'World') {
    $settingspage = new moodle_url('/admin/settings.php?section=managelocalstickynotes');
    $boardpage = new moodle_url('/local/stickynotes/freespeech.php');
    echo '<ul>
  <li><a href=' . $settingspage . '>' . get_string('pluginsettings', 'local_stickynotes') . '</a></li>
  <li><a href=' . $boardpage . '>' . get_string('freespeech', 'local_stickynotes') . '</a></li>';

} else {
    $now = time();
    echo userdate($now);
    echo '<br>';
    echo userdate(time(), get_string('strftimedaydate', 'core_langconfig'));
    echo '<br>';
    $date = new DateTime("yesterday", core_date::get_user_timezone_object());
    $date->setTime(0, 0, 0);
    echo userdate($date->getTimestamp(), get_string('strftimedatefullshort', 'core_langconfig'));
    echo '<br>';
    $grade = 20.00 / 3;
    echo $grade;
    echo '<br>';
    echo format_float($grade, 2);
    echo '<br>';
    echo $PAGE->url;
    echo '<br>';
    echo '<form action="" method="get">
        <input type="text" name="name" placeholder="Type your name">
        <input type="submit" value="Submit">
        </form>';
    echo html_writer::tag('input', '', [
            'type' => 'text',
            'name' => 'username',
            'placeholder' => get_string('pluginname', 'local_stickynotes'),
    ]);
    echo '<br>';
}

echo $OUTPUT->footer();
