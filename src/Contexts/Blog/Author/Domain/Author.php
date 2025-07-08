<?php

namespace App\Contexts\Blog\Author\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;

class Author extends AggregateRoot
{
    public function __construct(
        public private(set) AuthorId $id,
        public private(set) AuthorName $name,
        public private(set) AuthorSurname $surname
    ) {
    }

    public static function create(
        AuthorId $id,
        AuthorName $name,
        AuthorSurname $surname
    ): Author {
        $author = new self($id, $name, $surname);
        $author->record(new AuthorCreatedDomainEvent($id, $name, $surname));

        return $author;
    }

    public function updateName(AuthorName $name): void
    {
        if ($this->name->equals($name)) {
            return;
        }

        $this->record(new AuthorNameUpdatedDomainEvent($this->id->value(), $name->value(), $this->name->value()));
        $this->name = $name;
    }

    public function updateSurname(AuthorSurname $surname): void
    {
        if ($this->surname->equals($surname)) {
            return;
        }

        $this->record(new AuthorSurnameUpdatedDomainEvent($this->id->value(), $surname->value(), $this->surname->value()));
        $this->surname = $surname;
    }

}
