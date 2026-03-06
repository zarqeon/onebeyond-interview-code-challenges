<?php

require_once __DIR__ . '/../services/BookStockService.php';

class LoanController {

	public function __construct(
		private BookStockService $bookStockService,
		private AuthorService $authorService
	)
	{}

    // GET /loans
	public function index(): void
       	{
        header('Content-Type: application/json');

	$borrowed = $this->bookStockService->getBorrowedBookData();

	$message = $this->formatMessage($borrowed);
	echo json_encode (['message' => $message]);
    }
    
    // POST /loans/return
	public function returnBook(int $stockId): void
       	{
        header('Content-Type: application/json');

	$stock = $this->bookStockService->getStockById($stockId);

	$message = $this->bookStockService->freeUpBookStock($stock);

        echo json_encode(['message' => $message]);
    }

    // Ideally this function would live in a message formatter service
    private function formatMessage(array $borrowed): array
    {
	    $message = [];
	    foreach ($borrowed as $row) {
		    $message[] = [
			    Borrower::NAME => $row[BookStockService::BORROWER]->name,
			    Borrower::EMAIL => $row[BookStockService::BORROWER]->email,
			    Book::AUTHOR => $this->authorService->getAuthorNameByAuthorId($row[BookStockService::BOOK]->authorId),
			    Book::TITLE => $row[BookStockService::BOOK]->title,
			    Book::ISBN => $row[BookStockService::BOOK]->isbn,
			    Book::FORMAT => $row[BookStockService::BOOK]->format,
			    BookStock::LOAN_END => $row[BookStockService::STOCK]->loanEndDate
		    ];
	    }

	    return $message;
    }
}
