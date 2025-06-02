<?php
require_once __DIR__ . '/../controllers/user.controller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'register':
                UserController::register($_POST);
                break;
            case 'login':
                UserController::login($_POST);
                break;
            case 'change_password':
                UserController::changePassword($_POST);
                break;
            default:
                echo json_encode(['success'=>false, 'message'=>'Hành động không hợp lệ!']);
        }
    } else {
        echo json_encode(['success'=>false, 'message'=>'Thiếu thông tin action!']);
    }
} else {
    echo json_encode(['success'=>false, 'message'=>'Chỉ hỗ trợ phương thức POST!']);
}
