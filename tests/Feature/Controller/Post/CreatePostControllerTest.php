<?php

namespace App\Tests\Feature\Controller\Post;

use App\Controller\Post\CreatePostController;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorMother;
use App\Tests\Unit\Contexts\Blog\Post\Domain\PostMother;
use App\Tests\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use PHPUnit\Framework\TestCase;

class CreatePostControllerTest extends WebTestCase
{
    use RecreateDatabaseTrait;

    protected const string ENDPOINT = '/post';
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

    public function testShouldCreateAPost()
    {
        $author = AuthorMother::create();
        $post = PostMother::create(
            authorId: $author->id,
            authorName: sprintf('%s %s', $author->name, $author->surname),
        );
        $this->em->persist($author);
        $this->em->flush();

        self::$client->request(
            method: 'POST',
            uri: self::ENDPOINT,
            content: json_encode([
                'title' => $post->title->value(),
                'authorId' => $post->authorId->value(),
                'content' => $post->content->value(),
                'description' => $post->description->value(),
            ])
        );

        $response = self::$client->getResponse();
        self::assertEquals(201, $response->getStatusCode());

        $createdPost = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $createdPost);
        self::assertEquals($post->title, $createdPost['title']);
        self::assertEquals($post->content, $createdPost['content']);
        self::assertEquals($post->description, $createdPost['description']);
        self::assertEquals($post->authorId, $createdPost['authorId']);
        self::assertEquals($post->authorName, $createdPost['authorName']);
    }
}
