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

namespace local_stickynotes\form;

use moodleform;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Form to post sticky notes.
 *
 * @package    local_stickynotes
 * @copyright  2022 Juan Felipe Orozco Escobar
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class note_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        $mform = & $this->_form;

        $mform->addElement('textarea', 'ta_note', get_string('note', 'local_stickynotes'), 'rows="10" cols="200"');
        $mform->setType('ta_note', PARAM_TEXT);
        $mform->addHelpButton('ta_note', 'notehelp', 'local_stickynotes');

        $this->add_action_buttons(false, get_string('submit'));
    }
}
