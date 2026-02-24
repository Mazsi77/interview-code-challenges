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

    public static function findBookStockByBookId($bookId)
    {
        ///Question does bookStore and book always have the same id?
        ///if yes, this method can be simplified to return the bookStock from ID directly
        ///Quesstion 2: Can we assume that on book only has one bookStock?
        global $bookStocks;

        foreach ($bookStocks as $bookStock) {
            if($bookId === $bookStock->bookId) {
                return $bookStock;
            }
        }

        throw new Exception('Book stock not found.');
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