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
        header('Content-Type: application/json');

        try {
            $bookId = $_POST['bookId'];
        } catch (Exception $e) {
            echo json_encode(['message' => 'Book ID is required.']);

            return;
        }

        try {
            $borrowerId = $_POST['borrowerId'];
        } catch (Exception $e) {
            echo json_encode(['message' => 'Borrower ID is required.']);
        }

        try {
            //Check for exsistence of book and borrower
            $book = DataHelper::findBookById($bookId);
            $borrower = DataHelper::findBorrowerById($borrowerId);

            //Check if book is borrowed by borrower
            $bookStock = DataHelper::findBookStockByBookIdAndBorrowerId($book->id, $borrower->id);

            $response = [];

            $daysLate = floor((strtotime('now') - strtotime($bookStock->loanEndDate)) / 86400);

            $bookStock->isOnLoan = false;
            $bookStock->loanEndDate = null;
            $bookStock->borrowerId = null;

            global $bookStocks;
            $bookstocks[$bookStock->id] = $bookStock;

            if($daysLate > 0) {
                global $fines;

                $id = count($fines) + 1;
                $fine = new Fine($id, $bookStock->borrowerId, $daysLate);

                $fines[] = $fine;
                $response['fine'] = $fine->details;
            }

            $response['message'] = 'Book returned successfully.';

            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);

            return;
        }
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
