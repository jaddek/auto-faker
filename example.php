<?php

declare(strict_types=1);

require "vendor/autoload.php";

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class BookFaker implements \Jaddek\AutoFaker\IReferenceAttribute, \Jaddek\AutoFaker\IFakerAttribute
{
    public function __invoke(): string
    {
        return '';
    }
}

class Author
{
    #[\Jaddek\AutoFaker\Attribute\UniqidFaker]
    public string $id;

    #[\Jaddek\AutoFaker\Attribute\NameFaker]
    public string $name;

    #[\BookFaker]
    public Book $book;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(Book $book): void
    {
        $this->book = $book;
    }
}

class Book
{
    #[\Jaddek\AutoFaker\Attribute\IntFaker(min:0, max:100)]
    public int $id;

    #[\Jaddek\AutoFaker\Attribute\DateTimeImmutableFaker]
    public DateTimeImmutable $publishedAt;

    #[\Jaddek\AutoFaker\Attribute\TitleFaker]
    public string $title;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}


$faker = new \Jaddek\AutoFaker\AutoFaker(
    new \Jaddek\AutoFaker\Report\ColumnSetterBuilder(
        new \Jaddek\AutoFaker\Report\ColumnSetterReflectionHandler()
    )
);

$books = $authors = [];

$faker->setReferenceMiddlewares([BookFaker::class => function () use (&$books): Book {
    return $books[array_rand($books)];
}]);

$entries = $faker->getGenerator(Book::class, 2);

foreach ($entries as $book) {
    $books[] = $book;
}

$entries = $faker->getGenerator(Author::class, 10);

foreach ($entries as $author) {
    $authors[] = $author;
}

print_r(json_encode($author));