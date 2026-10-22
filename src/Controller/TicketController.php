<?php

namespace GlpiPlugin\Ticketmail\Controller;

use Glpi\Controller\AbstractController;
use Glpi\Http\RedirectResponse;
use GlpiPlugin\Ticketmail\Ticket;
use Html;
use NotificationEvent;
use NotificationMailing;
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
        $ticket->check($request->request->getInt('ticket_id'), READ);

        $users_id = $request->request->getInt('users_id');
        $email = trim($request->request->getString('ticket_mail_custom_mail'));

        if ($users_id <= 0 && $email === '') {
            $message = sprintf(
                __('Mandatory fields are not filled. Please correct: %s'),
                __('Recipient', 'ticketmail')
            );
            Session::addMessageAfterRedirect(htmlescape($message), false, ERROR);

            return new RedirectResponse(Html::getBackUrl());
        }

        if ($email !== '' && !NotificationMailing::isUserAddressValid($email)) {
            Session::addMessageAfterRedirect(__s('Invalid email address'), false, ERROR);

            return new RedirectResponse(Html::getBackUrl());
        }

        NotificationEvent::raiseEvent('plugin_ticketmail', $ticket, [
            'users_id' => $users_id > 0 ? $users_id : null,
            'email' => $email === '' ? null : $email,
            'ticket_mail_note' => $request->request->getString('ticket_mail_note'),
        ]);

        Session::addMessageAfterRedirect(
            __s('Ticket has been sent!', 'ticketmail'),
            true,
            INFO
        );

        return new RedirectResponse(Html::getBackUrl());
    }
}
