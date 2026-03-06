<?php

require_once __DIR__ . '/../data/data.php';
require_once __DIR__ . '/../models/Reservation.php';

class ReservationController {

	public function __construct(
		private BookService $bookService,
		private BookStockService $bookStockService,
		private BorrowerService $borrowerService,
		private ReservationService $reservationService
	)
	{}

	// POST /reservations
	public function reserve(int $bookId, int $borrowerId): void
	{
		header('Content-Type: application/json');

		$stock = $this->bookStockService->getStockByBookId($bookId);
		$borrower = $this->borrowerService->getBorrowerById($borrowerId);

		$message = $this->bookStockService->getAvailabilityMessage($stock, $borrower);
		if ($message !== null) {
			echo json_encode(['message' => $message]);
			return;
		}

		$reservation = $this->reservationService->createReservation($stock, $borrower);
		$book = $this->bookService->getBookById($bookId);
		$message = "Reservation set for %s for %s";
		echo json_encode(['message' => sprintf($message, $borrower->name, $book->title)]);
	}

	// GET /reservations
	public function status(int $bookId): void
	{
		header('Content-Type: application/json');
		
		$book = $this->bookService->getBookById($bookId);
		$stock = $this->bookStockService->getStockByBookId($bookId);
		
		$reservations = $this->reservationService->getReservationsByBookId($bookId);

		if ($stock->isOnLoan === true) {
			$message = sprintf('%s is currently on loan, due date is %s. There are furthermore %s reservations for this book', $book->title, $stock->loanEndDate, count($reservations));
		} else {
			$message = sprintf('%s is currently available', $book->title);
		}
		echo json_encode(['message' => $message]);
	}
}
