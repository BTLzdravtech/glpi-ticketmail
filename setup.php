<?php

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

use Glpi\Plugin\Hooks;
use GlpiPlugin\Ticketmail\Profile as TicketMailProfile;
use GlpiPlugin\Ticketmail\Ticket as TicketMailTicket;

define('PLUGIN_TICKETMAIL_VERSION', '4.0.0');
define('PLUGIN_TICKETMAIL_MIN_GLPI', '11.0.0');
define('PLUGIN_TICKETMAIL_MAX_GLPI', '11.0.99');

function plugin_init_ticketmail()
{
    global $PLUGIN_HOOKS;

    Plugin::registerClass(TicketMailProfile::class, ['addtabon' => Profile::class]);
    Plugin::registerClass(TicketMailTicket::class, ['addtabon' => Ticket::class]);

    $PLUGIN_HOOKS[Hooks::ITEM_ACTION_TARGETS]['ticketmail']
        = ['NotificationTargetTicket' => 'plugin_ticketmail_item_action_targets'];

    $PLUGIN_HOOKS[Hooks::ITEM_ADD_TARGETS]['ticketmail']
        = ['NotificationTargetTicket' => 'plugin_ticketmail_item_add_targets'];

    $PLUGIN_HOOKS[Hooks::ITEM_GET_EVENTS]['ticketmail']
        = ['NotificationTargetTicket' => 'plugin_ticketmail_get_events'];

    $PLUGIN_HOOKS[Hooks::ITEM_GET_DATA]['ticketmail']
        = ['NotificationTargetTicket' => 'plugin_ticketmail_get_datas'];
}

function plugin_version_ticketmail()
{
    return [
        'name'           => 'Ticket Mail',
        'version'        => PLUGIN_TICKETMAIL_VERSION,
        'author'         => '<a href="http://www.probesys.com">Probesys</a>',
        'license'        => 'GPLv3+',
        'homepage'       => 'https://github.com/Probesys/glpi-plugins-ticketmail',
        'requirements'   => [
            'glpi' => [
                'min' => PLUGIN_TICKETMAIL_MIN_GLPI,
                'max' => PLUGIN_TICKETMAIL_MAX_GLPI,
            ],
        ],
    ];
}

function plugin_ticketmail_check_prerequisites()
{
    return true;
}

function plugin_ticketmail_check_config($verbose = false)
{
    if ($verbose) {
        echo __s('Installed / not configured', 'ticketmail');
    }
    return true;
}
