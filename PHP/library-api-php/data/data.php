<?php

require_once __DIR__ . '/../models/Author.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Borrower.php';
require_once __DIR__ . '/../models/BookStock.php';

// In-memory storage of data

// Sample authors
$authors = [
    new Author(1, 'Jane Austen'),
    new Author(2, 'Mark Twain')
];

// Sample books
$books = [
    new Book(1, 'Pride and Prejudice', 1, 'Hardcover', '1111111111111'),
    new Book(2, 'Adventures of Huckleberry Finn', 2, 'Paperback', '2222222222222'),
    new Book(3, 'Returning books in Time is the way to go', 1, 'Hardcover', '3333333333333'),
    new Book(4, 'Returning earlier books is better', 2, 'Paperback', '4444444444444')
];

// Sample borrowers
$borrowers = [
    new Borrower(1, 'Alice', 'alice@example.com'),
    new Borrower(2, 'Bob', 'bob@example.com'),
    new Borrower(3, 'Tim', 'tim@example.com')
];

// Sample book stocks (assume book 1 is on loan)
$bookStocks = [
    new BookStock(1, 1, true, '2025-04-10', 1),
    new BookStock(2, 2, false),
    new BookStock(3, 3, true, date('Y-m-d') , 2),
    new BookStock(4, 4, true, '2030-03-03' , 3)
];

// Fines and reservations (empty arrays to start)
$fines = [];
$reservations = [];
