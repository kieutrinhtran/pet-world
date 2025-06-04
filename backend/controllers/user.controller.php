<?php
require_once __DIR__.'/../services/user.services.php';
class UserController {
    public static function register($data) {
        $result = UserService::register(
            trim($data['user_name']),
            $data['password'],
            $data['re_password']
        );
        echo json_encode($result);
    }

    public static function login($data) {
        $result = UserService::login(
            trim($data['user_name']),
            $data['password']
        );
        echo json_encode($result);
    }

    public static function changePassword($data) {
        $result = UserService::changePassword(
            trim($data['user_name']),
            $data['current_password'],
            $data['new_password'],
            $data['re_password']
        );
        echo json_encode($result);
    }
}
