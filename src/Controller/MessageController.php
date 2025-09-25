<?php
declare(strict_types=1);

namespace App\Controller;

use App\Enum\MessageStatusEnum;
use App\Message\Command\SendMessage;
use App\Message\Query\GetAllMessagesQuery;
use App\Message\Query\GetAllMessagesQueryHandler;
use App\Presentation\Model\UuidModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

/**
 * @see MessageControllerTest
 *
 * Previous solution was very bad and there multiple reasons:
 * - REST api wasn't good, it was GET /messages/send for send() method. GET can't be used for creating and URI should be as same as for GET
 * - GET was missing HTTP method setup, so after fixing creating method send() and switching it to POST, conflict would be created
 * - method list() used directly repositories and builders in controllers as well as setting JSON response with config in Response class
 * - message repository method name by() is very bad, it's not self-explanatory and could cause miss understanding
 * - method send() does writing to DB, and it's a command in CQRS so it should be async but should return uuid property in order for FE to catch the last message for it UI/UX
 * - for the POST HTTP method, it's bad to send data by Query params, it should be done in payload
 *
 * All changes here are update in openapi.yml file
 */
class MessageController extends AbstractController
{
    #[Route('/messages', methods: ['GET'])]
    public function list(
        GetAllMessagesQueryHandler $queryHandler,
        #[MapQueryParameter] ?MessageStatusEnum $status = null,
    ): Response
    {
        $response = $queryHandler->handle(
            new GetAllMessagesQuery($status)
        );

        return $this->json($response);
    }

    #[Route('/messages', methods: ['POST'])]
    public function send(
        MessageBusInterface $messageBus,
        #[MapRequestPayload] SendMessage $sendMessage,
    ): Response
    {
        $messageBus->dispatch($sendMessage);
        
        return $this->json(
            new UuidModel(
                $sendMessage->getUuid(),
            )
        );
    }
}
