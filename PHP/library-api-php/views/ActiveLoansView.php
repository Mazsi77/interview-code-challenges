<?php

class ActiveLoansView extends Borrower
{
    public $booksOnLoan;

    public function __construct($borrower, $booksOnLoan = [])
    {
        parent::__construct($borrower->id, $borrower->name, $borrower->email);

        $this->booksOnLoan = $booksOnLoan;
    }
}