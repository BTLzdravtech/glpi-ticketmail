<?php

namespace GlpiPlugin\Ticketmail;

use CommonDBTM;
use CommonGLPI;
use Glpi\Application\View\TemplateRenderer;
use Session;
use Ticket as GlpiTicket;

class Ticket extends CommonDBTM
{
    public const RIGHT_ALLOW = 1024;
    public static $rightname = 'plugin_ticketmail';

    public static function getIcon(): string
    {
        return 'ti ti-mail-share';
    }

    public static function getTypeName($nb = 0)
    {
        return __("Send ticket as mail", "ticketmail");
    }

    public function getRights($interface = 'central')
    {
        $rights[self::RIGHT_ALLOW] = __s("Allow", "ticketmail");

        return $rights;
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string
    {
        if (
            $item instanceof GlpiTicket
            && $item->getField('id')
            && Session::haveRight(self::$rightname, self::RIGHT_ALLOW)
        ) {
            return self::createTabEntry(__s('Ticket mail', 'ticketmail'));
        }

        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0): bool
    {
        if ($item instanceof GlpiTicket && Session::haveRight(self::$rightname, self::RIGHT_ALLOW)) {
            self::showForTicket($item->getID());
        }

        return true;
    }

    public static function showForTicket($ticket_id)
    {
        $ticket = new GlpiTicket();
        $ticket->getFromDB($ticket_id);

        TemplateRenderer::getInstance()->display('@ticketmail/ticket.html.twig', [
            'item' => $ticket,
            'form_path' => self::getFormURL(),
            'canedit' => true,
        ]);

        return true;
    }
}
