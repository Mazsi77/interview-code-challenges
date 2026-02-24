<?php

require_once __DIR__ . '/../data/data.php';

class DataHelper
{
    public static function findBorrowerById($borrowerId)
    {
        global $borrowers;

        return self::filterById($borrowers, $borrowerId, 'Borrower');
    }

    public static function findBookById($bookId)
    {
        global $books;

        return self::filterById($books, $bookId, 'Book');
    }

    public static function findAuthorById($authorId)
    {
        global $authors;

        return self::filterById($authors, $authorId, 'Author');
    }

    public static function findBookStockByBookIdAndBorrowerId($bookId, $borrowerId)
    {
        global $bookStocks;

        foreach ($bookStocks as $bookStock) {
            if($bookId === $bookStock->bookId && $borrowerId === $bookStock->borrowerId) {
                return $bookStock;
            }
        }

        throw new Exception("Book $bookId is not on loan by borrower $borrowerId.");
    }

    private static function filterById($array, $id, $itemName = 'Item')
    {
        foreach ($array as $item) {
            if($id == $item->id) {
                return $item;
            }
        }

        throw new Exception("$itemName not found.");
    }
}