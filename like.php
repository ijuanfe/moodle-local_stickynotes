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
 * Sticky notes like feature.
 *
 * @package    local_stickynotes
 * @copyright  2022 Juan Felipe Orozco Escobar
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require(__DIR__ . '/lib.php');

require_login();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}

// Page configuration.
$PAGE->set_url(new moodle_url('/local/stickynotes/like.php'));
$PAGE->set_context(context_system::instance());

$stickynoteid = required_param('stickynoteid', PARAM_INT);

if ($stickynoteid) {
    local_stickynotes_like($stickynoteid);
}
redirect(new moodle_url('/local/stickynotes/brainstorm.php'));
