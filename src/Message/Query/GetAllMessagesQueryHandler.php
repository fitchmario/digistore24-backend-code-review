<?php

declare(strict_types=1);

namespace App\Message\Query;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAllMessagesQueryHandler
{
    public function __construct(

    )
    {

    }
}
