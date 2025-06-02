<?php
require_once __DIR__.'/../controllers/customer.router.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['customer_id'])) {
    CustomerController::getInfo($_GET['customer_id']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_id'])) {
    $data = [
        'customer_name'  => $_POST['customer_name'],
        'email'          => $_POST['email'],
        'phone'          => $_POST['phone'],
        'date_of_birth'  => $_POST['date_of_birth'],
        'gender'         => $_POST['gender'],
    ];
    CustomerController::updateInfo($_POST['customer_id'], $data);
}
?>
