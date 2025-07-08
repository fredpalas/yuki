<?php

namespace App\Tests\Feature\Controller\Post;

use App\Controller\Post\GetPostController;
use App\Tests\Shared\Domain\UuidMother;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorMother;
use App\Tests\Unit\Contexts\Blog\Post\Domain\PostMother;
use App\Tests\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class GetPostControllerTest extends WebTestCase
{
    protected const string ENDPOINT = '/post/%s';
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        self::$client = static::createClient();
        self::$client->setServerParameters([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testShouldGetAPost(): void
    {
        $author = AuthorMother::create();
        $post = PostMother::create(
            authorId: $author->id,
            authorName: sprintf('%s %s', $author->name, $author->surname),
        );
        $this->em->persist($author);
        $this->em->persist($post);
        $this->em->flush();

        self::$client->request(
            method: 'GET',
            uri: sprintf(self::ENDPOINT, $post->id->value())
        );

        $response = self::$client->getResponse();
        self::assertEquals(200, $response->getStatusCode());

        $fetchedPost = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $fetchedPost);
        self::assertEquals($post->title->value(), $fetchedPost['title']);
        self::assertEquals($post->authorId->value(), $fetchedPost['authorId']);
        self::assertEquals($post->content->value(), $fetchedPost['content']);
        self::assertEquals($post->description->value(), $fetchedPost['description']);
        self::assertEquals(
            sprintf('%s %s', $author->name->value(), $author->surname->value()),
            $fetchedPost['authorName']
        );
    }

    public function testShouldThrowNotFoundExceptionWhenPostDoesNotExist(): void
    {
        self::$client->request(
            method: 'GET',
            uri: sprintf(self::ENDPOINT, UuidMother::create())
        );

        $response = self::$client->getResponse();
        self::assertEquals(404, $response->getStatusCode());
    }
}
