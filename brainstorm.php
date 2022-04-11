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
 * Sticky notes board: Brainstorm feature.
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
$PAGE->set_url(new moodle_url('/local/stickynotes/brainstorm.php'));
$PAGE->set_context(context_system::instance());
$PAGE->requires->css('/local/stickynotes/styles/styles.css');
$PAGE->set_title(get_string('brainstorm', 'local_stickynotes'));
$PAGE->set_heading(get_string('brainstorm', 'local_stickynotes'));
$PAGE->set_pagelayout('standard');
$PAGE->requires->js_call_amd('local_stickynotes/clean_form', 'init');

// Rendering page.
echo $OUTPUT->header();

echo html_writer::img($OUTPUT->image_url('brainstorm', 'local_stickynotes'), get_string('brainstorm_alt', 'local_stickynotes'),
        ['style' => 'display: block; margin: 0 auto; width:600px ;height:250px;']);
echo html_writer::tag('p', get_string('brainstorm_img_src', 'local_stickynotes'), ['style' => 'text-align: center']);
echo html_writer::start_tag('br');

$mform = new local_stickynotes\form\note_form();
$mform->display();

if ($userinput = $mform->get_data()) {
    local_stickynotes_insert_stickynote($userinput);
}

$allstickynotes = local_stickynotes_get_stickynotes_brainstorm();
$likeurl = 'like.php?stickynoteid=';
$templatecontext = array(
        'stickynotes'   => $allstickynotes,
        'likeurl'       => $likeurl
);

echo $OUTPUT->render_from_template('local_stickynotes/brainstorm_board', $templatecontext);

echo $OUTPUT->footer();
