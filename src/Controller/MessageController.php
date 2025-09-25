<?php
declare(strict_types=1);

namespace App\Controller;

use App\Enum\MessageStatusEnum;
use App\Message\Command\SendMessage;
use App\Message\Query\GetAllMessagesQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

/**
 * @see MessageControllerTest
 * TODO: review both methods and also the `openapi.yaml` specification
 *       Add Comments for your Code-Review, so that the developer can understand why changes are needed.
 */
class MessageController extends BaseController
{
    /**
     * TODO: cover this method with tests, and refactor the code (including other files that need to be refactored)
     */
    #[Route('/messages')]
    public function list(
        #[MapQueryParameter] ?MessageStatusEnum $status = null,
    ): Response
    {
        $response = $this->ask(
            new GetAllMessagesQuery($status)
        );

        return $this->json($response);
    }

    #[Route('/messages/send', methods: ['GET'])]
    public function send(Request $request, MessageBusInterface $bus): Response
    {
        $text = $request->query->get('text');
        
        if (!$text) {
            return new Response('Text is required', 400);
        }

        $bus->dispatch(new SendMessage($text));
        
        return new Response('Successfully sent', 204);
    }
}
