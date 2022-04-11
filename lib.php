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

    if (isloggedin() and !isguestuser()) {
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
 * Insert a sticky note in the database: all features.
 *
 * @param object $userinput Submitted note content from the form
 * @return void
 */
function local_stickynotes_insert_stickynote($userinput): void {
    global $DB, $USER;

    $params = array(
            'content'       => $userinput->notecontent,
            'timecreated'   => time(),
            'userid'        => $USER->id
    );
    $DB->insert_record('local_stickynotes_note', $params);
}

/**
 * Return all the sticky notes stored in the database: Free Speech feature.
 *
 * @return array of sticky notes as objects sorted in ascending order by creation time
 */
function local_stickynotes_get_stickynotes_freespeech() {
    global $DB;

    $sql = "SELECT snn.id, snn.content, snn.timecreated, u.username
            FROM {local_stickynotes_note} snn
            LEFT JOIN {user} u ON snn.userid = u.id
            ORDER BY snn.timecreated ASC";

    $allstickynotes = $DB->get_records_sql($sql);
    $allstickynotes = array_values($allstickynotes);

    foreach ($allstickynotes as $stickynote) {
        $stickynote->timecreated = format_time($stickynote->timecreated - time());
    }
    return $allstickynotes;
}

/**
 * Return all the sticky notes stored in the database: Brainstorm feature.
 *
 * @return array of sticky notes as objects sorted in ascending order by most liked
 */
function local_stickynotes_get_stickynotes_brainstorm() {
    global $DB;

    $sql = "SELECT snn.id, snn.content, COUNT(snl.id) AS likes
            FROM {local_stickynotes_note} snn
            LEFT JOIN {local_stickynotes_like} snl
            ON snn.id = snl.noteid
            LEFT JOIN {user} u
            ON snl.userid = u.id
            GROUP BY snn.id
            ORDER BY likes DESC";

    $allstickynotes = $DB->get_records_sql($sql);
    $allstickynotes = array_values($allstickynotes);

    return $allstickynotes;
}

/**
 * Insert a liked sticky note in the database: Brainstorm feature.
 *
 * @param int $stickynoteid Sticky Note ID that is going to be liked
 * @return void
 */
function local_stickynotes_like($stickynoteid): void {
    global $DB, $USER;

    $sqlparams = array(
            'stickynoteid' => $stickynoteid,
            'stickynoteid2' => $stickynoteid,
            'stickynoteid3' => $stickynoteid,
            'usersessionid' => $USER->id,
            'usersessionid2' => $USER->id
    );

    $sql = "INSERT INTO {local_stickynotes_like} (userid, noteid)
                SELECT :usersessionid , :stickynoteid
                FROM {local_stickynotes_note}
                WHERE EXISTS (
                    SELECT id
                    FROM {local_stickynotes_note}
                    WHERE id = :stickynoteid2
                )
                AND NOT EXISTS (
                    SELECT id
                    FROM {local_stickynotes_like}
                    WHERE userid = :usersessionid2 AND noteid = :stickynoteid3
                )
                LIMIT 1";

    // Insert the liked sticky note in the DB.
    $DB->execute($sql, $sqlparams);
}
