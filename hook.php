<?php

use GlpiPlugin\Ticketmail\Ticket;

/**
 * ---------------------------------------------------------------------
 *  ticketmail is a plugin to allows users to send ticket information by email
 *  ---------------------------------------------------------------------
 *  LICENSE
 *
 *  This file is part of ticketmail.
 *
 *  ticketmail is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  ticketmail is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with ticketmail. If not, see <http://www.gnu.org/licenses/>.
 *  ---------------------------------------------------------------------
 *  @copyright Copyright © 2022-2023 probeSys'
 *  @license   http://www.gnu.org/licenses/agpl.txt AGPLv3+
 *  @link      https://github.com/Probesys/glpi-plugins-ticketmail
 *  @link      https://plugins.glpi-project.org/#/plugin/ticketmail
 *  ---------------------------------------------------------------------
 */

function plugin_ticketmail_install()
{
    global $DB;

    $currentVersion = '0.0.0';

    $current = Config::getConfigurationValues('plugin:ticketmail');

    if (isset($current['version'])) {
        $currentVersion = $current['version'];
    }

    $migration = new Migration(PLUGIN_TICKETMAIL_VERSION);
    $migration->addRight(Ticket::$rightname, Ticket::RIGHT_ALLOW, [Config::$rightname => UPDATE]);

    if (version_compare($currentVersion, '4.0.0', '<')) {
        $DB->dropTable('glpi_plugin_ticketmail_profiles', true);
    }

    $migration->executeMigration();

    $current['version'] = PLUGIN_TICKETMAIL_VERSION;
    Config::setConfigurationValues('plugin:ticketmail', $current);

    return true;
}

function plugin_ticketmail_uninstall()
{

    ProfileRight::deleteProfileRights(Ticket::$rightname);

    return true;
}

function plugin_ticketmail_item_action_targets(NotificationTargetTicket $target)
{
    $target->addToRecipientsList($target->options);
}

function plugin_ticketmail_item_add_targets(NotificationTargetTicket $target)
{
    $target->addTarget(Notification::ITEM_USER, __('Send ticket as email', 'ticketmail'));
}

function plugin_ticketmail_get_events(NotificationTargetTicket $target)
{
    $target->events['plugin_ticketmail'] = __s('Send ticket as mail', 'ticketmail');
}


function plugin_ticketmail_get_datas(NotificationTargetTicket $target)
{
    $target->data['##ticket.ticket_mail_note##'] = $target->options['ticket_mail_note'] ?? '';
}
