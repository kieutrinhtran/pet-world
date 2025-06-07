<?php
require_once __DIR__ . '/../services/auth.service.php';

class AuthController
{
    private $service;

    public function __construct($db)
    {
        $this->service = new AuthService($db);
    }

    public function login($data)
    {
        $user_name = $data['user_name'] ?? '';
        $password = $data['password'] ?? '';
        $user = $this->service->login($user_name, $password);

        if ($user) {
            return [
                'status' => 200,
                'data' => [
                    'user' => $user
                ]
            ];
        } else {
            return [
                'status' => 401,
                'data' => [
                    'message' => 'Login failed'
                ]
            ];
        }
    }
    public function getCustomer($data)
    {
        $user_id = $data['user_id'] ?? '';
        $customer = $this->service->getCustomer($user_id);

        if ($customer) {
            return [
                'status' => 200,
                'data' => [
                    'customer' => $customer
                ]
            ];
        } else {
            return [
                'status' => 401,
                'data' => [
                    'message' => 'Get customer failed'
                ]
            ];
        }
    }
}