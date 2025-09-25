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
 * TODO: review both methods and also the `openapi.yaml` specification
 *       Add Comments for your Code-Review, so that the developer can understand why changes are needed.
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
