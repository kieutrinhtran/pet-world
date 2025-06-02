<template>
  <div class="admin-order-detail">
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
    </div>
    <div v-else-if="error" class="error">
      {{ error }}
    </div>
    <div v-else class="order-detail">
      <div class="header">
        <h1>Chi tiết đơn hàng #{{ order.id }}</h1>
        <div class="actions">
          <button @click="goBack" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại
          </button>
          <button @click="showEditModal = true" class="btn-edit">
            <i class="fas fa-edit"></i> Chỉnh sửa
          </button>
        </div>
      </div>

      <div class="order-info">
        <div class="info-section">
          <h2>Thông tin đơn hàng</h2>
          <div class="info-grid">
            <div class="info-item">
              <label>Trạng thái đơn hàng:</label>
              <span :class="['status', order.status]">{{ getStatusText(order.status) }}</span>
            </div>
            <div class="info-item">
              <label>Ngày đặt:</label>
              <span>{{ formatDate(order.created_at) }}</span>
            </div>
            <div class="info-item">
              <label>Tổng tiền:</label>
              <span class="price">{{ formatPrice(order.total_amount) }}</span>
            </div>
            <div class="info-item">
              <label>Phương thức thanh toán:</label>
              <span>{{ getPaymentMethodText(order.payment_method) }}</span>
            </div>
            <div class="info-item">
              <label>Trạng thái thanh toán:</label>
              <span :class="['status', order.payment_status]">{{ getPaymentStatusText(order.payment_status) }}</span>
            </div>
          </div>
        </div>

        <div class="info-section">
          <h2>Thông tin khách hàng</h2>
          <div class="info-grid">
            <div class="info-item">
              <label>Tên khách hàng:</label>
              <span>{{ order.customer_name }}</span>
            </div>
            <div class="info-item">
              <label>Email:</label>
              <span>{{ order.customer_email }}</span>
            </div>
            <div class="info-item">
              <label>Số điện thoại:</label>
              <span>{{ order.customer_phone }}</span>
            </div>
          </div>
        </div>

        <div class="info-section">
          <h2>Thông tin vận chuyển</h2>
          <div class="info-grid">
            <div class="info-item">
              <label>Địa chỉ:</label>
              <span>{{ order.shipping_address }}</span>
            </div>
            <div class="info-item">
              <label>Phương thức vận chuyển:</label>
              <span>{{ getShippingMethodText(order.shipping_method) }}</span>
            </div>
            <div class="info-item">
              <label>Phí vận chuyển:</label>
              <span class="price">{{ formatPrice(order.shipping_cost) }}</span>
            </div>
          </div>
        </div>

        <div class="info-section">
          <h2>Chi tiết sản phẩm</h2>
          <div class="products-table">
            <table>
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>Đơn giá</th>
                  <th>Số lượng</th>
                  <th>Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in order.items" :key="item.id">
                  <td>
                    <div class="product-info">
                      <img :src="item.image" :alt="item.name" class="product-image" />
                      <span>{{ item.name }}</span>
                    </div>
                  </td>
                  <td>{{ formatPrice(item.unit_price) }}</td>
                  <td>{{ item.quantity }}</td>
                  <td class="price">{{ formatPrice(item.unit_price * item.quantity) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Chỉnh sửa đơn hàng</h2>
          <button @click="showEditModal = false" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Trạng thái đơn hàng:</label>
            <select v-model="order.status" class="form-control">
              <option value="pending">Chờ xác nhận</option>
              <option value="processing">Đang xử lý</option>
              <option value="shipped">Đang giao hàng</option>
              <option value="delivered">Đã nhận hàng</option>
              <option value="cancelled">Đã hủy</option>
            </select>
          </div>
          <div class="form-group">
            <label>Trạng thái thanh toán:</label>
            <select v-model="order.payment_status" class="form-control">
              <option value="pending">Chờ thanh toán</option>
              <option value="paid">Đã thanh toán</option>
              <option value="failed">Thanh toán thất bại</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="showEditModal = false" class="btn-cancel">Hủy</button>
          <button @click="saveChanges" class="btn-save">Lưu thay đổi</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { API_ENDPOINTS } from '@/api/endpoints'
import { orderService } from '@/services/api'

const route = useRoute()
const router = useRouter()
const orderId = route.params.id

const order = ref({})
const loading = ref(false)
const error = ref(null)
const showEditModal = ref(false)

const goBack = () => {
  router.back()
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN')
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(price)
}

const getStatusText = (status) => {
  const statusMap = {
    pending: 'Chờ xác nhận',
    processing: 'Đang xử lý',
    shipped: 'Đang giao hàng',
    delivered: 'Đã nhận hàng',
    cancelled: 'Đã hủy'
  }
  return statusMap[status] || status
}

const getPaymentMethodText = (method) => {
  const methodMap = {
    cod: 'Thanh toán khi nhận hàng',
    bank_transfer: 'Chuyển khoản ngân hàng',
    credit_card: 'Thẻ tín dụng'
  }
  return methodMap[method] || method
}

const getPaymentStatusText = (status) => {
  const statusMap = {
    pending: 'Chờ thanh toán',
    paid: 'Đã thanh toán',
    failed: 'Thanh toán thất bại'
  }
  return statusMap[status] || status
}

const getShippingMethodText = (method) => {
  const methodMap = {
    standard: 'Giao hàng tiêu chuẩn',
    express: 'Giao hàng nhanh'
  }
  return methodMap[method] || method
}

const saveChanges = async () => {
  try {
    loading.value = true
    const updatedOrder = await orderService.update(orderId, {
      status: order.value.status,
      payment_status: order.value.payment_status
    })
    
    if (updatedOrder) {
      order.value = { ...updatedOrder }
      showEditModal.value = false
    }
  } catch (err) {
    error.value = err.message
    console.error(err)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  try {
    loading.value = true
    const response = await axios.get(API_ENDPOINTS.ORDERS.GET_DETAIL(orderId))
    order.value = response.data
  } catch (err) {
    error.value = 'Không thể tải thông tin đơn hàng'
    console.error(err)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.admin-order-detail {
  padding: 24px;
  max-width: 1200px;
  margin: 0 auto;
}

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 400px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #ff813f;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error {
  color: #dc2626;
  text-align: center;
  padding: 24px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.actions {
  display: flex;
  gap: 12px;
}

.btn-back,
.btn-edit {
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.btn-back {
  background: #f3f4f6;
  color: #374151;
}

.btn-back:hover {
  background: #e5e7eb;
}

.btn-edit {
  background: #ff813f;
  color: white;
}

.btn-edit:hover {
  background: #f97316;
}

.info-section {
  background: white;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-section h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 16px;
  color: #111827;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 16px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.info-item label {
  color: #6b7280;
  font-size: 0.875rem;
}

.info-item span {
  font-weight: 500;
  color: #111827;
}

.status {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.status.pending {
  background: #fef3c7;
  color: #92400e;
}

.status.processing {
  background: #dbeafe;
  color: #1e40af;
}

.status.shipped {
  background: #dcfce7;
  color: #166534;
}

.status.delivered {
  background: #f3f4f6;
  color: #374151;
}

.status.cancelled {
  background: #fee2e2;
  color: #991b1b;
}

.price {
  color: #ff813f;
  font-weight: 600;
}

.products-table {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

th {
  background: #f9fafb;
  font-weight: 500;
  color: #6b7280;
}

.product-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.product-image {
  width: 48px;
  height: 48px;
  object-fit: cover;
  border-radius: 4px;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 50;
}

.modal-content {
  background: white;
  border-radius: 12px;
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
}

.close-btn {
  font-size: 1.5rem;
  color: #6b7280;
  cursor: pointer;
}

.modal-body {
  padding: 24px;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #374151;
  font-weight: 500;
}

.form-control {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.875rem;
}

.modal-footer {
  padding: 16px 24px;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn-cancel,
.btn-save {
  padding: 8px 16px;
  border-radius: 6px;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-cancel {
  background: #f3f4f6;
  color: #374151;
}

.btn-cancel:hover {
  background: #e5e7eb;
}

.btn-save {
  background: #ff813f;
  color: white;
}

.btn-save:hover {
  background: #f97316;
}
</style> 