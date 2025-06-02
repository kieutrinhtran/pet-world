<?php
require_once __DIR__.'/../config/database.php';

class CustomerModel {
    public static function getById($customer_id) {
        global $conn;
        $sql = "SELECT * FROM customer WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $customer_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function update($customer_id, $data) {
        global $conn;
        $sql = "UPDATE customer SET customer_name=?, email=?, phone=?, date_of_birth=?, gender=? WHERE customer_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $data['customer_name'], $data['email'], $data['phone'], $data['date_of_birth'], $data['gender'], $customer_id);
        return $stmt->execute();
    }
}
?>
