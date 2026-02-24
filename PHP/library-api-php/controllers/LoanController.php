<?php

require_once __DIR__ . '/../data/data.php';
require_once __DIR__ . '/../models/BookStock.php';
require_once __DIR__ . '/../models/Fine.php';
require_once __DIR__ . '/../views/ActiveLoansView.php';
require_once __DIR__ . '/../helpers/DataHelper.php';

class LoanController {
    // GET /loans
    public function index() {
        $activeLoans = $this->listActiveLoans();

        header('Content-Type: application/json');
        echo json_encode($activeLoans);
    }
    
    // POST /loans/return
    public function returnBook() {
        // TODO: Implement logic to process the return of a book and calculate fines if overdue.
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Return book functionality to be implemented.']);
    }

    private function listActiveLoans() {
        global $bookStocks;

        $borrowedBooks = array_filter($bookStocks, function ($bookStock) {
            return $bookStock->isOnLoan;
        });

        $activeLoans = [];

        foreach ($borrowedBooks as $bookStock) {
            if(array_key_exists($bookStock->borrowerId, $activeLoans)) {
                $activeLoans[$bookStock->borrowerId]->booksOnLoan[] = $bookStock->title;

                continue;
            }

            $borrower = DataHelper::findBorrowerById($bookStock->borrowerId);
            $book = DataHelper::findBookById($bookStock->bookId);
            $author = DataHelper::findAuthorById($book->authorId);

            $bookTitle = $book->title . ' - ' . $author->name;

            $activeLoans[$bookStock->borrowerId] = new ActiveLoansView($borrower, [$bookTitle]);
        }


        return $activeLoans;
    }
}
