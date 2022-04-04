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
 * Sticky notes board.
 *
 * @package    local_stickynotes
 * @copyright  2022 Juan Felipe Orozco Escobar
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

require_login();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}

// Page configuration.
$PAGE->set_url(new moodle_url('/local/stickynotes/board.php'));
$PAGE->set_context(context_system::instance());
$PAGE->requires->css('/local/stickynotes/styles/styles.css');
$PAGE->set_title(get_string('stickynotesboard', 'local_stickynotes'));
$PAGE->set_heading(get_string('stickynotesboard', 'local_stickynotes', 'Juan'));
$PAGE->set_pagelayout('standard');
$PAGE->requires->js_call_amd('local_stickynotes/clean_form', 'init');

// Outputting page.
echo $OUTPUT->header();

echo html_writer::img($OUTPUT->image_url('free_speech', 'local_stickynotes'), get_string('free_speech_alt', 'local_stickynotes'),
        ['style' => 'display: block; margin: 0 auto; width:600px ;height:250px;']);
echo html_writer::tag('p', get_string('free_speech_img_src', 'local_stickynotes'), ['style' => 'text-align: center']);
echo html_writer::start_tag('br');

$mform = new local_stickynotes\form\note_form();
$mform->display();

if ($fromform = $mform->get_data()) {
    $params = array(
            'note'          => $fromform->ta_note,
            'timecreated'   => time(),
            'userid'        => $USER->id
    );
    $DB->insert_record('local_stickynotes_notes', $params);
}

// Display sticky notes on the board.
$allstickynotes = get_stickynotes();
$templatecontext = array(
        'stickynotes' => $allstickynotes
);

echo $OUTPUT->render_from_template('local_stickynotes/stickynotes_board', $templatecontext);

echo $OUTPUT->footer();
