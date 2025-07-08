<?php

namespace App\Contexts\Blog\Post\Application\Create;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Post\Domain\Post;
use App\Contexts\Blog\Post\Domain\PostAuthorName;
use App\Contexts\Blog\Post\Domain\PostContent;
use App\Contexts\Blog\Post\Domain\PostDescription;
use App\Contexts\Blog\Post\Domain\PostId;
use App\Contexts\Blog\Post\Domain\PostRepository;
use App\Contexts\Blog\Post\Domain\PostTitle;

class PostCreator
{
    public function __construct(private PostRepository $postRepository) { }

    public function __invoke(
        PostId $id,
        AuthorId $authorId,
        PostTitle $title,
        PostContent $content,
        PostDescription $description,
        PostAuthorName $authorName
    )
    {
        $post = Post::create(
            id: $id,
            authorId: $authorId,
            title: $title,
            content: $content,
            description: $description,
            authorName: $authorName
        );

        $this->postRepository->save($post);
    }
}
