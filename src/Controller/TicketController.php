<?php

namespace GlpiPlugin\Ticketmail\Controller;

use Glpi\Controller\AbstractController;
use Glpi\Http\RedirectResponse;
use GlpiPlugin\Ticketmail\Ticket;
use Html;
use NotificationEvent;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Ticket as GlpiTicket;

final class TicketController extends AbstractController
{
    #[Route("/front/ticket.form.php", name: "ticketmail_ticket")]
    public function __invoke(Request $request): Response
    {
        Session::checkRight(Ticket::$rightname, Ticket::RIGHT_ALLOW);

        $ticket = new GlpiTicket();
        $ticket->getFromDB($_POST["ticket_id"]);

        if (empty($_POST['users_id']) && empty($_POST['ticket_mail_custom_mail'])) {
            $message = sprintf(
                __('Mandatory fields are not filled. Please correct: %s'),
                __('Recipient')
            );
            Session::addMessageAfterRedirect(htmlescape($message), false, ERROR);
            Html::back();
        }

        NotificationEvent::raiseEvent('plugin_ticketmail', $ticket, [
            'users_id' => $_POST['users_id'],
            'email' => empty($_POST['ticket_mail_custom_mail']) ? null : $_POST['ticket_mail_custom_mail'],
            'ticket_mail_note' => $_POST['ticket_mail_note'],
        ]);

        Session::addMessageAfterRedirect(
            __s('Ticket has been sent!'),
            true,
            INFO
        );

        return new RedirectResponse(Html::getBackUrl());
    }
}
