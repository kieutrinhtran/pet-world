<?php
require_once __DIR__ . '/../model/order.model.php';
require_once __DIR__ . '/../model/promotion.model.php';
require_once __DIR__ . '/../model/product.model.php';
require_once __DIR__ . '/../model/cart.model.php'; // <--- Thêm dòng này

class OrderService
{
    private $orderModel;
    private $promotionModel;
    private $productModel;
    private $cartModel; // <--- Thêm thuộc tính

    public function __construct($db)
    {
        $this->orderModel = new Order($db);
        $this->promotionModel = new Promotion($db);
        $this->productModel = new Product($db);
        $this->cartModel = new Cart($db); // <--- Khởi tạo model cart
    }

    private function applyPromotion($promotion_id, $total_amount)
    {
        if (!$promotion_id)
            return ['valid' => true, 'final_total' => $total_amount];

        $promo = $this->promotionModel->findOne($promotion_id);
        if (!$promo)
            return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại'];
        if (!$promo['is_active'])
            return ['valid' => false, 'message' => 'Mã giảm giá không còn hiệu lực'];
        if ($promo['used_voucher'] >= $promo['total_voucher'])
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'];

        $now = date('Y-m-d');
        if ($now < $promo['start_date'] || $now > $promo['end_date']) {
            return ['valid' => false, 'message' => 'Mã giảm giá hết hạn sử dụng'];
        }

        $discount_percent = floatval($promo['discount_percent']);
        $discount_amount = $total_amount * ($discount_percent / 100);
        $final_total = $total_amount - $discount_amount;

        return [
            'valid' => true,
            'final_total' => round($final_total, 2),
            'promotion_id' => $promotion_id
        ];
    }

    public function createOrderFromCart($data, $cart_items)
    {
        if (empty($data['payment_method'])) {
            return ['success' => false, 'message' => 'Vui lòng chọn hình thức thanh toán'];
        }

        foreach ($cart_items as $item) {
            $product = $this->productModel->findOne($item['product_id']);
            if (!$product) {
                return ['success' => false, 'message' => 'Sản phẩm không tồn tại'];
            }
            if ($item['quantity'] > $product['stock']) {
                return [
                    'success' => false,
                    'message' => "Sản phẩm {$product['product_name']} chỉ còn {$product['stock']} sản phẩm trong kho"
                ];
            }
        }

        if (empty($data['status'])) {
            $data['status'] = 'pending';
        }

        $promotionResult = $this->applyPromotion(
            !empty($data['promotion_id']) ? $data['promotion_id'] : null,
            $data['total_amount']
        );
        if (!$promotionResult['valid']) {
            return ['success' => false, 'message' => $promotionResult['message']];
        }
        $data['total_amount'] = $promotionResult['final_total'];

        $order_id = $this->orderModel->createOrderFromCart($data, $cart_items);

        if ($order_id) {
            if (isset($promotionResult['promotion_id'])) {
                $this->promotionModel->incrementUsedVoucher($promotionResult['promotion_id']);
            }

            // 🧹 Xóa giỏ hàng sau khi tạo đơn hàng thành công
            if (!empty($data['customer_id'])) {
                $this->cartModel->clearCartByCustomer($data['customer_id']);
            }
        }

        return $order_id ? ['success' => true, 'order_id' => $order_id] :
            ['success' => false, 'message' => 'Tạo đơn hàng thất bại'];
    }

    public function buyNow($data, $product)
    {
        if (empty($data['payment_method'])) {
            return ['success' => false, 'message' => 'Vui lòng chọn hình thức thanh toán'];
        }

        $productInfo = $this->productModel->findOne($product['product_id']);
        if (!$productInfo) {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại'];
        }
        if ($product['quantity'] > $productInfo['stock']) {
            return [
                'success' => false,
                'message' => "Sản phẩm {$productInfo['product_name']} chỉ còn {$productInfo['stock']} sản phẩm trong kho"
            ];
        }

        if (empty($data['status'])) {
            $data['status'] = 'pending';
        }

        $promotionResult = $this->applyPromotion(
            !empty($data['promotion_id']) ? $data['promotion_id'] : null,
            $data['total_amount']
        );
        if (!$promotionResult['valid']) {
            return ['success' => false, 'message' => $promotionResult['message']];
        }
        $data['total_amount'] = $promotionResult['final_total'];

        $order_id = $this->orderModel->buyNow($data, $product);

        if ($order_id && isset($promotionResult['promotion_id'])) {
            $this->promotionModel->incrementUsedVoucher($promotionResult['promotion_id']);
        }

        return $order_id ? ['success' => true, 'order_id' => $order_id] :
            ['success' => false, 'message' => 'Tạo đơn hàng thất bại'];
    }

    public function getAllOrdersByCustomer($customer_id)
    {
        return $this->orderModel->getAllOrdersByCustomer($customer_id);
    }

    public function getOrderDetail($order_id)
    {
        return $this->orderModel->getOrderDetail($order_id);
    }

    public function countOrders()
    {
        return $this->orderModel->countOrders();
    }

    public function countOrdersByStatus()
    {
        return $this->orderModel->countOrdersByStatus();
    }

    public function revenueThisMonth()
    {
        return $this->orderModel->revenueThisMonth();
    }

    public function countActivePromotions()
    {
        return $this->orderModel->countActivePromotions();
    }

    public function countProducts()
    {
        return $this->orderModel->countProducts();
    }

    public function countCustomers()
    {
        return $this->orderModel->countCustomers();
    }
}
