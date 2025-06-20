<?php
require_once __DIR__ . '/../model/customer.model.php';

class CustomerService
{
    private $customerModel;

    public function __construct($db)
    {
        $this->customerModel = new Customer($db);
    }

    public function getCustomerById($id)
    {
        return $this->customerModel->findOne($id);
    }

    public function getAllCustomers()
    {
        return $this->customerModel->getAll();
    }

    public function getCustomerByCustomerId($userId)
    {
        return $this->customerModel->getCustomerByUserId($userId);
    }
    public function changePassword($user_id, $current_password, $new_password, $confirm_password)
    {
        // Kiểm tra đầu vào
        if (empty($user_id) || empty($current_password) || empty($new_password) || empty($confirm_password)) {
            return [
                'success' => false,
                'message' => 'Vui lòng cung cấp đầy đủ thông tin'
            ];
        }
        
        // Kiểm tra mật khẩu mới và xác nhận mật khẩu
        if ($new_password !== $confirm_password) {
            return [
                'success' => false,
                'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp'
            ];
        }
        
        // Kiểm tra độ mạnh của mật khẩu
        if (strlen($new_password) < 8) {
            return [
                'success' => false,
                'message' => 'Mật khẩu mới phải có ít nhất 8 ký tự'
            ];
        }
        
        // Kiểm tra mật khẩu mới khác mật khẩu hiện tại
        if ($current_password === $new_password) {
            return [
                'success' => false,
                'message' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại'
            ];
        }
        
        // Gọi model để thực hiện đổi mật khẩu
        return $this->customerModel->changePassword($user_id, $current_password, $new_password);
    }
        public function updateCustomer($customerId, $data)
    {
        // Kiểm tra xem khách hàng có tồn tại không
        $customer = $this->customerModel->findOne($customerId);
        if (!$customer) {
            return [
                'status' => 404,
                'data' => [
                    'success' => false,
                    'message' => 'Không tìm thấy khách hàng'
                ]
            ];
        }

        // Thực hiện cập nhật
        $result = $this->customerModel->update($customerId, $data);
        
        if ($result) {
            return [
                'status' => 200,
                'data' => [
                    'success' => true,
                    'message' => 'Cập nhật thành công',
                    'customer' => $result
                ]
            ];
        }
        
        return [
            'status' => 500,
            'data' => [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin'
            ]
        ];
    }
}