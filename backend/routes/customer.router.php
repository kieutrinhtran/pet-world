<?php
require_once __DIR__ . '/../controllers/CustomerController.php';

class CustomerRouter
{
    private $db;
    private $controller;

    public function __construct($db)
    {
        $this->db = $db;
        $this->controller = new CustomerController($db);
    }

    public function addRoutes($router)
    {
        $router->addRoute('GET', '/customer', 'CustomerController', 'getCustomer');
        $router->addRoute('GET', '/customers', 'CustomerController', 'getAllCustomers');
         $router->addRoute('POST', '/password/change', 'CustomerController', 'changePassword');
                $router->addRoute('PUT', '/customer', 'CustomerController', 'updateProfile');

        }
}