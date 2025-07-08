<?php

namespace App\Contexts\Blog\Post\Application\Update;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Author\Domain\AuthorNameUpdatedDomainEvent;
use App\Contexts\Blog\Post\Application\Find\PostsFinderByAuthorId;
use App\Contexts\Blog\Post\Domain\Post;
use App\Contexts\Blog\Post\Domain\PostRepository;
use App\Shared\Domain\Bus\Event\DomainEventSubscriber;

use function Lambdish\Phunctional\each;

class UpdatePostAuthorNameOnAuthorNameChange implements DomainEventSubscriber
{

    public function __construct(
        private PostsFinderByAuthorId $postsFinder,
        private PostRepository $postRepository
    ) {
    }

    public static function subscribedTo(): array
    {
        return [AuthorNameUpdatedDomainEvent::class];
    }

    public function __invoke(AuthorNameUpdatedDomainEvent $event): void
    {
        $posts = $this->postsFinder->__invoke(AuthorId::create($event->aggregateId()));

        each(
            function (Post $post) use ($event) {
                $post->updateAuthorName(
                    $post->authorName->replaceNeedle(
                        $event->name,
                        $event->previousName
                    )
                );
                $this->postRepository->save($post);
            },
            $posts
        );
    }

}
