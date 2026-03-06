<?php

require __DIR__ . '/../models/Author.php';
require __DIR__ . '/../models/Book.php';
require __DIR__ . '/../models/Borrower.php';
require __DIR__ . '/../models/BookStock.php';

// In-memory storage of data

class MockStorage {
	// Sample authors
	public array $authors;
	public array $books;
	public array $borrowers;
	public array $bookStocks;
	public array $fines;
	public array $reservations;
       
	public function __construct() {
		$this->authors = [
			new Author(1, 'Jane Austen'),
			new Author(2, 'Mark Twain')
		];

		// Sample books
		$this->books = [
			new Book(1, 'Pride and Prejudice', 1, 'Hardcover', '1111111111111'),
			new Book(2, 'Adventures of Huckleberry Finn', 2, 'Paperback', '2222222222222')
		];

		// Sample borrowers
		$this->borrowers = [
			new Borrower(1, 'Alice', 'alice@example.com'),
			new Borrower(2, 'Bob', 'bob@example.com')
		];

		// Sample book stocks (assume book 1 is on loan)
		$this->bookStocks = [
			new BookStock(1, 1, true, '2025-04-10', 1),
			new BookStock(2, 2, false)
		];

		// Fines and reservations (empty arrays to start)
		$this->fines = [];
		$this->reservations = [
			new Reservation(1, 1, 2, '2025-04-12') 
		];

	}
}
