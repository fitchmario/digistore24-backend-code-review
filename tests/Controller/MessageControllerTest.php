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

    public function test_it_sends_a_message(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/messages',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['text' => 'Hello World'])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('uuid', $data, 'Response must contain uuid');

        // Ensure message was dispatched
        $this->transport('sync')
            ->queue()
            ->assertContains(SendMessage::class, 1);
    }

    public function test_it_fails_without_text(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/messages',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([]) // no text field
        );

        $this->assertResponseStatusCodeSame(400);
    }
}
