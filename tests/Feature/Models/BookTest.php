<?php

namespace Tests\Feature\Models;

use App\Enums\BookStatusEnum;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    private function makeBook(array $overrides = []): Book
    {
        return Book::create(array_merge([
            'accession_number' => 'LIB-TEST-' . uniqid(),
            'title'            => 'Test Book',
            'author'           => 'Test Author',
            'total_copies'     => 3,
            'available_copies' => 3,
            'status'           => BookStatusEnum::Available->value,
            'is_active'        => true,
            'language'         => 'English',
        ], $overrides));
    }

    public function test_book_can_be_created(): void
    {
        $book = $this->makeBook(['title' => 'Introduction to Laravel']);

        $this->assertDatabaseHas('books', ['title' => 'Introduction to Laravel']);
        $this->assertSame(BookStatusEnum::Available, $book->status);
    }

    public function test_active_scope_excludes_inactive_books(): void
    {
        $this->makeBook(['is_active' => true, 'title' => 'Active Book']);
        $this->makeBook(['is_active' => false, 'title' => 'Inactive Book']);

        $active = Book::active()->get();

        $this->assertCount(1, $active);
        $this->assertSame('Active Book', $active->first()->title);
    }

    public function test_available_scope_excludes_books_with_zero_copies(): void
    {
        $this->makeBook(['available_copies' => 5, 'title' => 'Available Book']);
        $this->makeBook(['available_copies' => 0, 'title' => 'Issued Book']);

        $available = Book::available()->get();

        $this->assertCount(1, $available);
        $this->assertSame('Available Book', $available->first()->title);
    }

    public function test_available_scope_includes_books_with_partial_copies(): void
    {
        $this->makeBook(['total_copies' => 5, 'available_copies' => 2]);

        $this->assertCount(1, Book::available()->get());
    }

    public function test_book_status_enum_is_cast_correctly(): void
    {
        $book = $this->makeBook(['status' => BookStatusEnum::Issued->value]);

        $this->assertSame(BookStatusEnum::Issued, $book->status);
    }

    public function test_soft_delete_hides_book_from_queries(): void
    {
        $book = $this->makeBook();
        $book->delete();

        $this->assertSoftDeleted('books', ['id' => $book->id]);
        $this->assertCount(0, Book::all());
    }

    public function test_soft_deleted_book_is_recoverable(): void
    {
        $book = $this->makeBook();
        $book->delete();

        $book->restore();

        $this->assertNotSoftDeleted('books', ['id' => $book->id]);
    }

    public function test_price_is_stored_as_decimal(): void
    {
        $book = $this->makeBook(['price' => 4500.50]);

        $this->assertEquals('4500.50', $book->price);
    }
}
