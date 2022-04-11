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
 * local_stickynotes plugin upgrade code
 *
 * @package    local_stickynotes
 * @copyright  2022 Juan Felipe Orozco Escobar
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function to upgrade local_stickynotes plugin.
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_local_stickynotes_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022032400) {

        $table = new xmldb_table('local_stickynotes_note');

        // Define field userid to add to the local_stickynotes_note table.
        $useridfield = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '2', 'timecreated');

        // Define foreign key fkuserid to add to the local_stickynotes_note table.
        $fkuserid = new xmldb_key('fk_userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Add field userid and foreign key fkuserid.
        if (!$dbman->field_exists($table, $useridfield)) {
            $dbman->add_field($table, $useridfield);
            $dbman->add_key($table, $fkuserid);
        }

        // Stickynotes savepoint reached.
        upgrade_plugin_savepoint(true, 2022032400, 'local', 'stickynotes');
    }

    if ($oldversion < 2022040401) {

        $table = new xmldb_table('local_stickynotes_like');

        $table->add_field('id', XMLDB_TYPE_INTEGER, 10, null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '2', 'id');
        $table->add_field('noteid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '2', 'userid');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('fk_userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $table->add_key('fk_noteid', XMLDB_KEY_FOREIGN, ['noteid'], 'local_stickynotes_note', ['id']);

        $dbman->create_table($table);

        // Stickynotes savepoint reached.
        upgrade_plugin_savepoint(true, 2022040401, 'local', 'stickynotes');
    }

    return true;
}

