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

namespace GlpiPlugin\Ticketmail;

use CommonDBTM;
use CommonGLPI;
use Glpi\Application\View\TemplateRenderer;
use Profile as GlpiProfile;

class Profile extends CommonDBTM
{
    public static function getIcon(): string
    {
        return 'ti ti-mail-share';
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string
    {
        if (
            $item instanceof GlpiProfile
            && $item->getField('id')
        ) {
            return self::createTabEntry(__s('Ticket mail', 'ticketmail'));
        }

        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0): bool
    {

        if ($item instanceof GlpiProfile) {
            return self::showForProfile($item->getID());
        }

        return true;
    }

    //profiles modification
    public static function showForProfile($profiles_id): bool
    {

        $profile = new GlpiProfile();
        $profile->getFromDB($profiles_id);

        TemplateRenderer::getInstance()->display('@ticketmail/profile.html.twig', [
            'item' => $profile,
            // "rights" => $profile::getRightsFor(Ticket::class),
            'rights' => [
                [
                    'itemtype' => Ticket::class,
                    'label' => Ticket::getTypeName(),
                    'field' => Ticket::$rightname,
                ],
            ],
        ]);

        return true;
    }
}
