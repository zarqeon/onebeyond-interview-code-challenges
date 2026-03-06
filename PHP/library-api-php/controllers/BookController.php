<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../data/data.php';

class BookController {
	public function __construct(
		private BookService $bookService
	)
	{}

    public function index() {
        // Returns a list of books
        header('Content-Type: application/json');
        echo json_encode($this->bookService->getBooks());
    }

}
