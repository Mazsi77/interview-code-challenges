<?php

class Fine {
    const LATE_FEE = 10;
    public $id;
    public $borrowerId;
    public $amount;
    public $details;

    public function __construct($id, $borrowerId, $daysLate) {
        $this->id = $id;
        $this->borrowerId = $borrowerId;
        $this->amount = self::calculateFineAmount($daysLate);
        $this->details = "Fine: $this->amount for returning $daysLate days late.";
    }

    private function calculateFineAmount($daysLate) {
        if($daysLate <= 0) {
            return 0;
        }

        return self::LATE_FEE * $daysLate;
    }
}
