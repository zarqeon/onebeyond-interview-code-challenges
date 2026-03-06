<?php

class ReservationService {

	public function __construct(private MockStorage $storage)
	{}

	public function createReservation(BookStock $stock, Borrower $borrower): Reservation
	{
		$newId = count($this->storage->reservations);
		$reservation = new Reservation($newId, $stock->bookId, $borrower->id, (new \DateTime())->format('Y-m-d H:i:s'));

		// multiple borrowers reserving the same book is fine, 
		// we can sort them by the reservedAt field.
		$this->storage->reservations[] = $reservation;

		return $reservation;
	}

	public function getReservationsByBookId(int $bookId): array
	{
		$hits = [];
		foreach ($this->storage->reservations as $reservation) {
			if ($reservation->bookId == $bookId) {
				$hits[] = $reservation;
			}
		}

		return $hits;
	}
}
