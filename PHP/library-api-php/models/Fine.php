<?php

class Fine {
	public const DEFAULT_FINE_AMOUNT = 10;
	public const FINE_REASON_OVERUE = "%s returned %s after due time (%s). A fine of %s EUR was issued.";
    public $id;
    public $borrowerId;
    public $amount;
    public $details;

    public function __construct($id, $borrowerId, $amount, $details = '') {
        $this->id = $id;
        $this->borrowerId = $borrowerId;
        $this->amount = $amount;
        $this->details = $details;
    }
}
