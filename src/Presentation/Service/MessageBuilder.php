<?php

declare(strict_types=1);

namespace App\Presentation\Service;

use App\Entity\Message;
use App\Presentation\Model\MessageCollection;
use App\Presentation\Model\MessageModel;

class MessageBuilder
{
    /**
     * @param Message[] $messages
     */
    public function buildCollection(array $messages): MessageCollection
    {
        $collection = new MessageCollection();
        foreach ($messages as $message) {
            $collection->addMessage(
                new MessageModel(
                    $message->getUuid(),
                    $message->getText(),
                    $message->getStatus()
                )
            );
        }

        return $collection;
    }
}
