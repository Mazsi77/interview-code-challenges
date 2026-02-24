<?php

require_once __DIR__ . '/../data/data.php';
require_once __DIR__ . '/../models/Reservation.php';

class ReservationController {
    // POST /reservations
    ///Question: Is there multiple book stock per book?
    /// Question: Can be reserved by multiple borrowers after each other? With expitration date
    public function reserve() {
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

            return;
        }

        try {
            //check book exists
            $book = DataHelper::findBookById($bookId);

            //check if already reserved
            $reservation = DataHelper::searchReservationByBookId($bookId);

            if($reservation) {
                if( $reservation->borrowerId != $borrowerId) {
                    throw new Exception("Book $bookId is already reserved by another borrower.");
                }

                throw new Exception("Book $bookId is already reserved by $borrowerId.");
            }

            //check if book is on loan
            $bookStock = DataHelper::findBookStockById($book->id);

            if(!$bookStock->isOnLoan) {
                throw new Exception("Book $bookId is not on loan, no need to reserve it.");
            }

            if($bookStock->borrowerId == $borrowerId) {
                throw new Exception("Book $bookId is already on Loan to $borrowerId.");
            }

            global $reservations;
            $reservedAt = date('Y-m-d');
            $reservationId = count($reservations) + 1;
            $reservation = new Reservation($reservationId, $bookId, $borrowerId, $reservedAt);

            $reservations[] = $reservation;

            echo json_encode(['message' => 'Book reserved successfully.']);

        }catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);

            return;
        }
    }
    
    // GET /reservations
    public function status() {
        header('Content-Type: application/json');

        try{
            $bookId = $_GET['bookId'];
        } catch (Exception $e) {
            echo json_encode(['message' => 'Book ID is required.']);

            return;
        }

        try{
            $borrowerId = $_GET['borrowerId'];
        } catch (Exception $e) {
            echo json_encode(['message' => 'Borrower ID is required.']);

            return;
        }

        try {
            //check reservation exists
            $reservation = DataHelper::searchReservationByBookId($bookId);

            //check if loaned
            $bookStock = DataHelper::findBookStockById($bookId);

            if($bookStock->isOnLoan && $borrowerId == $bookStock->borrowerId) {
                echo json_encode(['message' => "Book is already on loan to $borrowerId."]);

                return;
            }

            if(!$reservation && !$bookStock->isOnLoan) {
                echo json_encode(['message' => "Book is not reserved, but can be loaned"]);

                return;
            }

            if(!$reservation && $bookStock->isOnLoan) {
                echo json_encode(['message' => "Book is not reserved, but can be reserved to $borrowerId. Loan expiration date: {$bookStock->loanEndDate}"]);

                return;
            }

            if($reservation && $reservation->borrowerId != $borrowerId) {
                echo json_encode(['message' => "Book is reserved to another borrower."]);

                return;
            }

            if($reservation && $reservation->borrowerId == $borrowerId && $bookStock->isOnLoan) {
                echo json_encode(['message' => "Book is reserved to $borrowerId. Book loan expiration date: {$bookStock->loanEndDate}"]);

                return;
            }

            if($reservation && $reservation->borrowerId == $borrowerId && !$bookStock->isOnLoan) {
                echo json_encode(['message' => "Book is reserved to $borrowerId. Can be loaned again."]);
            }


        }catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);

            return;
        }
    }
}
