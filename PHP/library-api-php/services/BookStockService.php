<?php

require_once __DIR__ . '/../models/Fine.php';

class BookStockService {

	public const STOCK = 'stock';
	public const BOOK = 'book';
	public const BORROWER = 'borrower';


	public function __construct(
		private MockStorage $storage,
		private BorrowerService $borrowerService,
		private BookService $bookService
	)
	{}

	public function getStockByBookId(int $bookId): BookStock
	{
		foreach ($this->storage->bookStocks as $stock) {
			if ($stock->bookId == $bookId) {
				return $stock;
			}
		}

		throw new \Exception('stock not found: '.$bookStockId);
	}

	public function getStockById(int $bookStockId): BookStock
	{
		foreach ($this->storage->bookStocks as $stock) {
			if ($stock->id == $bookStockId) {
				return $stock;
			}
		}

		throw new \Exception('stock not found: '.$bookStockId);
	}

	public function freeUpBookStock(BookStock $stock): string
	{
		if ($stock->isOnLoan === false) {
			return "This book cannot be returned as it is not on loan right now";
		}

		$loanEndDate = new \DateTimeImmutable($stock->loanEndDate);
		$now = new \DateTimeImmutable();
		if ($loanEndDate < $now) {
			$fine = $this->fineBorrower($stock);
			return $fine->details;
		}

		// mimicing setting the stock, as it will not persist in this exercise
		$stock->isOnLoan = false;
		$stock->loanEndDate = null;
		$stock->borrowerId = null;
		return "Book is returned";
	}

	public function getBorrowedBookData(): array
	{
		$borrowed = [];
		foreach ($this->storage->bookStocks as $stock) {
			if ($stock->isOnLoan === true) {
				$borrowed[$stock->bookId] = [
					self::STOCK => $stock,
					self::BOOK => $this->bookService->getBookById($stock->bookId), // I would very much like to use a service here, but i don't believe it is in scope for this excercise
					self::BORROWER => $this->borrowerService->getBorrowerById($stock->borrowerId) // I would very much like to use a service here, but i don't believe it is in scope for this excercise
				];
			}
		}

		return $borrowed;
	}

    private function fineBorrower(BookStock $stock): Fine
    {
	// this database does not know about auto increment ids
	$newId = count($this->storage->fines) + 1;
	$borrower = $this->borrowerService->getBorrowerById($stock->borrowerId);
	$book = $this->bookService->getBookById($stock->bookId);
	$fineDetails = sprintf(Fine::FINE_REASON_OVERUE, $borrower->name, $book->title, $stock->loanEndDate, Fine::DEFAULT_FINE_AMOUNT);
	$fine = new Fine($newId, $stock->borrowerId, Fine::DEFAULT_FINE_AMOUNT, $fineDetails);
	$this->storage->fines[] = $fine;

	return $fine;
    }

	public function getAvailabilityMessage(BookStock $stock, Borrower $borrower): ?string
	{
		$book = $this->bookService->getBookById($stock->bookId);
		if ($stock->isOnLoan === false) {
			return sprintf('%s is available to loan', $book->title);
		}


		if ($stock->borrowerId == $borrower->id) {
			return sprintf('%s is already borrowing %s, a new reservation for this title is not allower for %s', $borrower->name, $book->title, $borrower->name);
		}

		return null;
	}
}
