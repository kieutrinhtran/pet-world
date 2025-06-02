<?php
require_once __DIR__ . '/../config/database.php';
class UserModel {
    // Đăng ký tài khoản mới
    public static function register($user_name, $password_hash, $role='user', $status='active') {
        global $conn;
        $sql = "INSERT INTO user_account (user_name, password, role, status, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $user_name, $password_hash, $role, $status);
        return $stmt->execute();
    }

    // Lấy thông tin user theo tên đăng nhập
    public static function getByUsername($user_name) {
        global $conn;
        $sql = "SELECT * FROM user_account WHERE user_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Cập nhật mật khẩu
    public static function updatePassword($user_name, $new_password_hash) {
        global $conn;
        $sql = "UPDATE user_account SET password = ? WHERE user_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_password_hash, $user_name);
        return $stmt->execute();
    }
}
