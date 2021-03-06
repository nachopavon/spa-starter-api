<?php

namespace Tests\Controllers\Books;

use App\Book;
use Tests\Helpers\WithoutMiddleware;
use Laravel\Lumen\Testing\DatabaseMigrations;

class ShowTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    public function testCanFetchBook()
    {
        $book = factory(Book::class)->create();

        $this->json('GET', '/api/books/1');

        $this->assertResponseOk();
        $this->seeJson([
            'id' => $book->id,
            'title' => $book->title,
        ]);
        $this->seeJsonStructure([
            'data' => ['id', 'title'],
        ]);
    }

    public function testCanFetchBookAlongWithAuthor()
    {
        $book = factory(Book::class)->create();

        $this->json('GET', '/api/books/1?include=author');

        $this->assertResponseOk();
        $this->seeJson([
            'id' => $book->author->id,
            'name' => $book->author->name,
        ]);
        $this->seeJsonStructure([
            'data' => [
                'id', 'title',
                'author' => [
                    'data' => ['id', 'name'],
                ],
            ],
        ]);
    }

    public function testGet404IfNoBookIsFound()
    {
        $this->json('GET', '/api/books/1');

        $this->assertResponseStatus(404);
        $this->seeJsonStructure([
            'errors' => [[]],
        ]);
    }
}
