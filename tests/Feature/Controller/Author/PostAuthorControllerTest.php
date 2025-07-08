<?php

namespace App\Tests\Feature\Controller\Author;

use App\Controller\Author\PostAuthorController;
use App\Tests\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use PHPUnit\Framework\TestCase;

class PostAuthorControllerTest extends WebTestCase
{
    use RecreateDatabaseTrait;

    protected const string ENDPOINT = '/author';

    protected function setUp(): void
    {
        parent::setUp();

        self::$client = static::createClient();
        self::$client->setServerParameters([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ]);
    }

    public function testShouldCreateAnAuthor(): void
    {
        $authorData = [
            'name' => 'John',
            'surname' => 'Doe',
        ];

        self::$client->request(
            method: 'POST',
            uri: self::ENDPOINT,
            content: json_encode($authorData)
        );

        $response = self::$client->getResponse();
        self::assertEquals(201, $response->getStatusCode());

        $createdAuthor = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $createdAuthor);
        self::assertEquals($authorData['name'], $createdAuthor['name']);
        self::assertEquals($authorData['surname'], $createdAuthor['surname']);
    }
}
