<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Enum\MessageStatusEnum;
use App\Message\Command\SendMessage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class MessageControllerTest extends WebTestCase
{
    use InteractsWithMessenger;

    public function test_list_without_status_returns_all_messages(): void
    {
        $client = static::createClient();
        $client->request('GET', '/messages');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $messagesCollection = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($messagesCollection, 'Response should be a JSON array');

        $this->assertArrayHasKey('messages', $messagesCollection);
        $this->assertIsArray($messagesCollection['messages']);

        foreach ($messagesCollection['messages'] as $message) {
            $this->assertArrayHasKey('uuid', $message);
            $this->assertArrayHasKey('text', $message);
            $this->assertArrayHasKey('status', $message);
        }
    }

    public function test_list_with_valid_enum_status(): void
    {
        $client = static::createClient();
        $client->request('GET', '/messages?status=' . MessageStatusEnum::SENT->value);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $messagesCollection = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($messagesCollection, 'Response should be a JSON array');

        $this->assertArrayHasKey('messages', $messagesCollection);
        $this->assertIsArray($messagesCollection['messages']);

        foreach ($messagesCollection['messages'] as $message) {
            $this->assertSame(MessageStatusEnum::SENT->value, $message['status']);
        }
    }

    public function test_list_with_invalid_enum_status_returns_bad_request(): void
    {
        $client = static::createClient();
        $client->request('GET', '/messages?status=does-not-exist');

        $this->assertResponseStatusCodeSame(400);
    }
    
    function test_that_it_sends_a_message(): void
    {
        $client = static::createClient();
        $client->request('GET', '/messages/send', [
            'text' => 'Hello World',
        ]);

        $this->assertResponseIsSuccessful();
        // This is using https://packagist.org/packages/zenstruck/messenger-test
        $this->transport('sync')
            ->queue()
            ->assertContains(SendMessage::class, 1);
    }
}
