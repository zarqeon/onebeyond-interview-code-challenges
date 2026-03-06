<?php

class BookService {

	public function __construct(private MockStorage $storage)
	{}


    public function getBookById(int $bookId): Book
    {
	    foreach ($this->storage->books as $book) {
		    if ($book->id == $bookId) {
			    return $book;
		    }
	    }

	    // exception handling is probably not the scope of this excercise, i believe
	    throw new \Exception('book not found: '.$bookId);
    }

	public function getBooks(): array
	{
		return $this->storage->books;
	}	

}
