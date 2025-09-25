<?php
declare(strict_types=1);

namespace App\Controller;

use App\Enum\MessageStatusEnum;
use App\Message\Command\SendMessage;
use App\Message\Query\GetAllMessagesQuery;
use App\Presentation\Model\UuidModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

/**
 * @see MessageControllerTest
 * TODO: review both methods and also the `openapi.yaml` specification
 *       Add Comments for your Code-Review, so that the developer can understand why changes are needed.
 */
class MessageController extends BaseController
{
    #[Route('/messages', methods: ['GET'])]
    public function list(
        #[MapQueryParameter] ?MessageStatusEnum $status = null,
    ): Response
    {
        $response = $this->ask(
            new GetAllMessagesQuery($status)
        );

        return $this->json($response);
    }

    #[Route('/messages', methods: ['POST'])]
    public function send(
        #[MapRequestPayload] SendMessage $sendMessage,
    ): Response
    {
        $this->messageBus->dispatch($sendMessage);
        
        return $this->json(
            new UuidModel(
                $sendMessage->getUuid(),
            )
        );
    }
}
