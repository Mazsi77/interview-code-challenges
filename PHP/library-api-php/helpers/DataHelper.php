<?php

require_once __DIR__ . '/../data/data.php';

class DataHelper
{
    public static function findBorrowerById($borrowerId)
    {
        global $borrowers;

        return self::filterById($borrowers, $borrowerId);
    }

    public static function findBookById($bookId)
    {
        global $books;

        return self::filterById($books, $bookId);
    }

    public static function findAuthorById($authorId)
    {
        global $authors;

        return self::filterById($authors, $authorId);
    }

    private static function filterById($array, $id)
    {
        foreach ($array as $item) {
            if($item->id === $id) {
                return $item;
            }
        }

        return null;
    }
}