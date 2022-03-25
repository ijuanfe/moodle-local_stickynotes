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
 * This file contains functions used by the sticky notes plugin.
 *
 * @package    local_stickynotes
 * @copyright  2022 Juan Felipe Orozco Escobar
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Add link into navigation drawer (Boost and Classic theme).
 *
 * @package  local_stickynotes
 * @param    global_navigation $root Node representing the global navigation tree.
 */
function local_stickynotes_extend_navigation(global_navigation $root) {

    if (get_config('local_stickynotes', 'showinnavigationdrawer')) {
        $node = navigation_node::create(
                get_string('pluginname', 'local_stickynotes'),
                new moodle_url('/local/stickynotes/index.php'),
                navigation_node::TYPE_CUSTOM,
                null,
                null,
                new pix_icon('t/message', '')
        );
        $node->showinflatnavigation = true;

        $root->add_node($node);
    }
}

/**
 * Insert a link on the navigation block menu on the site home page (Classic theme).
 * Administration block in site home page..
 * Insert an item on the setting dropdown menu icon on the site home page (Boost theme).
 *
 * @param navigation_node $frontpage Node representing the front page in the navigation tree.
 */
function local_stickynotes_extend_navigation_frontpage(navigation_node $frontpage) {
    $frontpage->add(
            get_string('pluginname', 'local_stickynotes'),
            new moodle_url('/local/stickynotes/index.php'),
            navigation_node::TYPE_CUSTOM,
            null,
            null,
            new pix_icon('t/message', '')
    );
}

/**
 * Return all the sticky notes stored in the database.
 *
 * @return array of sticky notes as objects sorted in ascending order by creation time
 */
function get_stickynotes() {
    global $DB;

    $sql = "SELECT sn.id, sn.note, sn.timecreated, u.username
                FROM {local_stickynotes_notes} sn
                LEFT JOIN {user} u ON sn.userid = u.id
                ORDER BY sn.timecreated ASC";

    $allstickynotes = $DB->get_records_sql($sql);
    $allstickynotes = array_values($allstickynotes);

    foreach ($allstickynotes as $stickynote) {
        $stickynote->timecreated = format_time($stickynote->timecreated - time());
    }
    return $allstickynotes;
}
