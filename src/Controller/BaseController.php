<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected MessageBusInterface $messageBus
    ){}

    public function ask(object $query): object
    {
        $envelope = $this->messageBus->dispatch($query);
        $handled = $envelope->last(HandledStamp::class);

        $result = $handled?->getResult();

        if (!\is_object($result)) {
            throw new \RuntimeException('Error handling query');
        }

        return $result;
    }
}
