<?php

namespace App\Tests\Feature\Controller\Author;

use App\Contexts\Blog\Author\Domain\Author;
use App\Contexts\Blog\Post\Domain\Post;
use App\Controller\Author\PutAuthorController;
use App\Tests\Shared\Domain\MotherCreator;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorMother;
use App\Tests\Unit\Contexts\Blog\Post\Domain\PostMother;
use App\Tests\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PutAuthorControllerTest extends WebTestCase
{
    use RecreateDatabaseTrait;

    protected const string ENDPOINT = '/author/%s';
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

    public function testShouldUpdateAnAuthor(): void
    {
        // Create a test author
        $author = AuthorMother::create();
        $this->em->persist($author);

        // Prepare updated author data
        $updatedAuthorData = [
            'name' => MotherCreator::random()->name(),
            'surname' => MotherCreator::random()->lastName(),
        ];

        // Send PUT request to update the author
        self::$client->request(
            method: 'PUT',
            uri: sprintf(self::ENDPOINT, $author->id),
            content: json_encode($updatedAuthorData)
        );

        // Assert successful response
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $this->em->clear();
        $authorFind = $this->em->getRepository(Author::class)->find($author->id);
        $this->assertEquals($updatedAuthorData['name'], $authorFind->name);
        $this->assertEquals($updatedAuthorData['surname'], $authorFind->surname);
    }

    public function testShouldUpdateNameOnAuthor()
    {
        // Create a test author
        $author = AuthorMother::create();
        $this->em->persist($author);

        // Prepare updated author data with only name
        $updatedAuthorData = [
            'name' => MotherCreator::random()->name(),
        ];

        // Send PUT request to update the author's name
        self::$client->request(
            method: 'PUT',
            uri: sprintf(self::ENDPOINT, $author->id),
            content: json_encode($updatedAuthorData)
        );

        // Assert successful response
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $this->em->clear();
        $authorFind = $this->em->getRepository(Author::class)->find($author->id);
        $this->assertEquals($updatedAuthorData['name'], $authorFind->name);
        $this->assertEquals($author->surname, $authorFind->surname);
    }

    public function testShouldUpdateSurnameOnAuthor()
    {
        // Create a test author
        $author = AuthorMother::create();
        $author->pullDomainEvents();
        $this->em->persist($author);

        // Prepare updated author data with only surname
        $updatedAuthorData = [
            'surname' => MotherCreator::random()->lastName(),
        ];

        // Send PUT request to update the author's surname
        self::$client->request(
            method: 'PUT',
            uri: sprintf(self::ENDPOINT, $author->id),
            content: json_encode($updatedAuthorData)
        );

        // Assert successful response
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $this->em->clear();
        $authorFind = $this->em->getRepository(Author::class)->find($author->id);
        $this->assertEquals($updatedAuthorData['surname'], $authorFind->surname);
        $this->assertEquals($author->name, $authorFind->name);
    }

    public function testShouldUpdateAuthorNameAndUpdateExistingPostOfTheAuthor()
    {
        // Create a test author with a post
        $author = AuthorMother::create();
        $post = PostMother::create(
            authorId: $author->id,
            authorName: sprintf('%s %s', $author->name, $author->surname)
        );

        $this->em->persist($author);
        $this->em->persist($post);
        $this->em->flush();

        // Prepare updated author data
        $updatedAuthorData = [
            'name' => MotherCreator::random()->name(),
            'surname' => MotherCreator::random()->lastName(),
        ];

        // Send PUT request to update the author
        self::$client->request(
            method: 'PUT',
            uri: sprintf(self::ENDPOINT, $author->id),
            content: json_encode($updatedAuthorData)
        );

        // Assert successful response
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $this->em->clear();
        $authorFind = $this->em->getRepository(Author::class)->find($author->id);
        $this->assertEquals($updatedAuthorData['name'], $authorFind->name);
        $this->assertEquals($updatedAuthorData['surname'], $authorFind->surname);

        $postFind = $this->em->getRepository(Post::class)->find($post->id);
        $this->assertEquals(
            sprintf('%s %s', $updatedAuthorData['name'], $updatedAuthorData['surname']),
            $postFind->authorName
        );
    }

    public function testShouldThrowNotFoundExceptionWhenAuthorDoesNotExist(): void
    {
        // Prepare updated author data
        $updatedAuthorData = [
            'name' => MotherCreator::random()->name(),
            'surname' => MotherCreator::random()->lastName(),
        ];

        // Send PUT request to update a non-existing author
        self::$client->request(
            method: 'PUT',
            uri: sprintf(self::ENDPOINT, 'non-existing-id'),
            content: json_encode($updatedAuthorData)
        );

        // Assert not found response
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
