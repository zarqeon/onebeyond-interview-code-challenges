<?php

require 'data/data.php';
require 'controllers/BookController.php';
require 'controllers/LoanController.php';
require 'controllers/ReservationController.php';
require_once 'services/AuthorService.php';
require_once 'services/BookService.php';
require_once 'services/BookStockService.php';
require_once 'services/BorrowerService.php';
require_once 'services/ReservationService.php';

//$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$queryString = $_SERVER['QUERY_STRING'];
parse_str($queryString, $params);
$uri = "/".$params['api'];

// setup storage
$storage = new MockStorage();
//setup services, no autoloader :(
$authorService = new AuthorService($storage);
$bookService = new BookService($storage);
$borrowerService = new BorrowerService($storage);
$bookStockService = new BookStockService($storage, $borrowerService, $bookService);
$reservationService = new ReservationService($storage);

if ($uri === '/books' && $method === 'GET') {
    $controller = new BookController($bookService);
    $controller->index();
} elseif ($uri === '/loans' && $method === 'GET') {
    $controller = new LoanController($bookStockService, $authorService);
    $controller->index();
} elseif ($uri === '/loans/return' && $method === 'POST') {
    $controller = new LoanController($bookStockService, $authorService);
    $controller->returnBook($_POST['book_stock_id']);
} elseif ($uri === '/reservations' && $method === 'POST') {
    $controller = new ReservationController($bookService, $bookStockService, $borrowerService, $reservationService);
    $controller->reserve($_POST['book_id'], $_POST['borrower_id']);
} elseif ($uri === '/reservations' && $method === 'GET') {
    $controller = new ReservationController($bookService, $bookStockService, $borrowerService, $reservationService);
    $controller->status($_GET['book_id']);
} else {
//    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
