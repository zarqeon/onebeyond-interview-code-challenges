<?php

class BorrowerService {


	public function __construct(private MockStorage $storage)
	{}


    public function getBorrowerById(int $borrowerId): Borrower
    {
	    foreach ($this->storage->borrowers as $borrower) {
		    if ($borrower->id == $borrowerId) {
			    return $borrower;
		    }
	    }

	    // exception handling is probably not the scope of this excercise, i believe
	    throw new \Exception('borrower not found: '.$borrowerId);
    }

}
