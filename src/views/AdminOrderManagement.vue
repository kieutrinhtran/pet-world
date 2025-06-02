<template>
  <div class="min-h-screen bg-gray-50 pt-8">
    <!-- Tiêu đề -->
    <div class="text-center text-3xl font-bold mt-8 mb-8 text-[#5a3a1b] font-quicksand tracking-wide">
      Quản lý đơn hàng
    </div>
    <div class="max-w-5xl mx-auto px-4">
      <!-- Filters -->
      <div class="bg-white p-4 rounded-lg shadow mb-4">
        <div class="mb-4">
          <SearchBar
            v-model="searchQuery"
            :placeholder="`Tìm kiếm theo mã đơn hàng, ngày đặt, tên hoặc email...`"
            @input="applyFilters"
          />
        </div>
        <div class="flex gap-4">
          <select v-model="statusFilter" @change="applyFilters" class="flex-1 p-2 border border-gray-300 rounded">
            <option value="">Tất cả trạng thái đơn hàng</option>
            <option value="pending">Chờ xác nhận</option>
            <option value="processing">Đang xử lý</option>
            <option value="shipped">Đang giao hàng</option>
            <option value="delivered">Đã nhận hàng</option>
            <option value="cancelled">Đã hủy</option>
          </select>
          <select v-model="paymentStatusFilter" @change="applyFilters" class="flex-1 p-2 border border-gray-300 rounded">
            <option value="">Tất cả trạng thái thanh toán</option>
            <option value="pending">Chờ thanh toán</option>
            <option value="paid">Đã thanh toán</option>
            <option value="failed">Thanh toán thất bại</option>
          </select>
        </div>
      </div>
      <!-- Orders table -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full border-collapse">
          <thead>
            <tr>
              <th class="bg-gray-100 font-semibold p-4 text-left">ID đơn hàng</th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Ngày đặt</th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Trạng thái đơn hàng</th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Tổng giá trị</th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Phương thức thanh toán</th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Trạng thái thanh toán</th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in paginatedOrders" :key="order.id" class="even:bg-gray-50">
              <td class="p-4">{{ order.id }}</td>
              <td class="p-4">{{ formatDate(order.created_at) }}</td>
              <td class="p-4">
                <span :class="[
                  'px-2 py-1 rounded text-xs font-semibold',
                  order.status === 'pending' && 'bg-yellow-100 text-yellow-800',
                  order.status === 'processing' && 'bg-blue-100 text-blue-800',
                  order.status === 'shipped' && 'bg-green-100 text-green-800',
                  order.status === 'delivered' && 'bg-emerald-100 text-emerald-800',
                  order.status === 'cancelled' && 'bg-red-100 text-red-800'
                ]">
                  {{ getStatusText(order.status) }}
                </span>
              </td>
              <td class="p-4">{{ formatPrice(order.total_amount) }}</td>
              <td class="p-4">{{ getPaymentMethodText(order.payment_method) }}</td>
              <td class="p-4">
                <span :class="[
                  'px-2 py-1 rounded text-xs font-semibold',
                  order.payment_status === 'pending' && 'bg-yellow-100 text-yellow-800',
                  order.payment_status === 'paid' && 'bg-green-100 text-green-800',
                  order.payment_status === 'failed' && 'bg-red-100 text-red-800'
                ]">
                  {{ getPaymentStatusText(order.payment_status) }}
                </span>
              </td>
              <td class="p-4 flex gap-2">
                <button @click="editOrder(order.id)" class="p-1 text-blue-600 hover:opacity-70" title="Sửa đơn hàng">
                  <i class="fas fa-edit"></i>
                </button>
                <button
                  @click="deleteOrder(order.id)"
                  class="p-1 text-red-600 hover:opacity-70"
                  title="Xóa đơn hàng"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="flex justify-center items-center gap-4 mt-4">
        <BasePagination
          :current-page="currentPage"
          :total-pages="totalPages"
          @prev="currentPage > 1 && (currentPage--, applyFilters())"
          @next="currentPage < totalPages && (currentPage++, applyFilters())"
        />
      </div>
    </div>
    <!-- Order Confirmation Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-lg w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b p-4">
          <h2 class="text-lg font-bold">Xác nhận đơn hàng #{{ editingOrder.id }}</h2>
          <button class="text-2xl text-gray-500 hover:text-gray-700" @click="showEditModal = false">&times;</button>
        </div>
        <div class="p-4">
          <div class="mb-6">
            <div class="flex mb-3"><label class="w-40 font-semibold text-gray-600">Khách hàng:</label><span>{{ editingOrder.customer_name }}</span></div>
            <div class="flex mb-3"><label class="w-40 font-semibold text-gray-600">Email:</label><span>{{ editingOrder.customer_email }}</span></div>
            <div class="flex mb-3"><label class="w-40 font-semibold text-gray-600">Ngày đặt:</label><span>{{ formatDate(editingOrder.created_at) }}</span></div>
            <div class="flex mb-3"><label class="w-40 font-semibold text-gray-600">Tổng giá trị:</label><span>{{ formatPrice(editingOrder.total_amount) }}</span></div>
            <div class="flex mb-3"><label class="w-40 font-semibold text-gray-600">Phương thức thanh toán:</label><span>{{ getPaymentMethodText(editingOrder.payment_method) }}</span></div>
          </div>
          <div class="bg-gray-50 p-4 rounded mb-4">
            <div class="mb-4">
              <label class="block font-semibold text-gray-600 mb-2">Trạng thái đơn hàng:</label>
              <select v-model="editingOrder.status" class="w-full p-2 border border-gray-300 rounded">
                <option value="pending">Chờ xác nhận</option>
                <option value="processing">Đang xử lý</option>
                <option value="shipped">Đang giao hàng</option>
                <option value="delivered">Đã nhận hàng</option>
                <option value="cancelled">Đã hủy</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold text-gray-600 mb-2">Trạng thái thanh toán:</label>
              <select v-model="editingOrder.payment_status" class="w-full p-2 border border-gray-300 rounded">
                <option value="pending">Chờ thanh toán</option>
                <option value="paid">Đã thanh toán</option>
                <option value="failed">Thanh toán thất bại</option>
              </select>
            </div>
          </div>
        </div>
        <div class="flex justify-end gap-4 border-t p-4">
          <button class="px-4 py-2 rounded border font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200" @click="showEditModal = false">Hủy</button>
          <button class="px-4 py-2 rounded font-semibold text-white bg-blue-500 hover:bg-blue-700" @click="saveOrderChanges">Lưu thay đổi</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import các thư viện và component cần thiết
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import BasePagination from '@/components/BasePagination.vue'
import SearchBar from '@/components/AdminSearchBar.vue'
import { API_ENDPOINTS } from '@/api/endpoints'
import { orderService } from '@/services/api'

// =====================
// Biến trạng thái reactive
// =====================
const orders = ref([]) // Danh sách đơn hàng gốc
const filteredOrders = ref([]) // Danh sách đơn hàng sau khi lọc
const loading = ref(false)
const error = ref(null)
const searchQuery = ref('') // Giá trị tìm kiếm
const statusFilter = ref('') // Lọc theo trạng thái đơn hàng
const paymentStatusFilter = ref('') // Lọc theo trạng thái thanh toán
const sortField = ref('created_at') // Trường sắp xếp
const sortDirection = ref('desc') // Chiều sắp xếp

// Phân trang
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Modal chỉnh sửa đơn hàng
const showEditModal = ref(false)
const editingOrder = ref({})

// =====================
// Cấu hình API
// =====================
// const apiBaseUrl = process.env.VUE_APP_API_URL || 'http://localhost/api'

// =====================
// Các computed property
// =====================
// const totalOrders = computed(() => orders.value.length)
const totalPages = computed(() => Math.ceil(filteredOrders.value.length / itemsPerPage.value))
const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredOrders.value.slice(start, end)
})

// =====================
// Hàm lọc, tìm kiếm, sắp xếp
// =====================
const applyFilters = () => {
  let filtered = [...orders.value]

  // --- Tìm kiếm ---
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase().trim()
    filtered = filtered.filter(order => {
      // Gom các trường cần tìm kiếm vào mảng
      const fields = [
        order.id?.toString().toLowerCase(),
        (order.created_at || order.order_date)?.toLowerCase(),
        (order.customer_name || '').toLowerCase(),
        (order.customer_email || '').toLowerCase(),
        // Ngày đặt dạng vi-VN
        (order.created_at || order.order_date)
          ? new Date(order.created_at || order.order_date).toLocaleDateString('vi-VN').toLowerCase()
          : ''
      ]
      // Nếu query xuất hiện ở bất kỳ trường nào thì giữ lại
      return fields.some(field => field && field.includes(query))
    })
  }

  // --- Lọc theo trạng thái đơn hàng ---
  if (statusFilter.value) {
    filtered = filtered.filter(order => order.status === statusFilter.value)
  }

  // --- Lọc theo trạng thái thanh toán ---
  if (paymentStatusFilter.value) {
    filtered = filtered.filter(order => order.payment_status === paymentStatusFilter.value)
  }

  // --- Sắp xếp ---
  filtered.sort((a, b) => {
    let aVal = a[sortField.value]
    let bVal = b[sortField.value]
    if (sortField.value === 'total_amount') {
      aVal = parseFloat(aVal)
      bVal = parseFloat(bVal)
    }
    if (sortDirection.value === 'asc') {
      return aVal > bVal ? 1 : -1
    } else {
      return aVal < bVal ? 1 : -1
    }
  })

  filteredOrders.value = filtered
  currentPage.value = 1 // Reset về trang đầu khi lọc
}

// =====================
// Các hàm format dữ liệu hiển thị
// =====================
const formatDate = date => {
  // Định dạng ngày theo chuẩn Việt Nam
  return new Date(date).toLocaleDateString('vi-VN')
}

const formatPrice = price => {
  // Định dạng giá tiền VND
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(price)
}

// =====================
// Hàm mapping trạng thái sang tiếng Việt
// =====================
const getStatusText = status => {
  const statusMap = {
    pending: 'Chờ xác nhận',
    processing: 'Đang xử lý',
    shipped: 'Đang giao hàng',
    delivered: 'Đã nhận hàng',
    cancelled: 'Đã hủy',
    'Chờ xác nhận': 'Chờ xác nhận',
    'Đang xử lý': 'Đang xử lý',
    'Đang giao hàng': 'Đang giao hàng',
    'Đã nhận': 'Đã nhận hàng',
    'Đã hủy': 'Đã hủy'
  }
  return statusMap[status] || status
}

const getPaymentMethodText = method => {
  const methodMap = {
    cod: 'Thanh toán khi nhận hàng',
    bank_transfer: 'Chuyển khoản ngân hàng',
    credit_card: 'Thẻ tín dụng',
    'Thanh toán khi nhận hàng': 'Thanh toán khi nhận hàng',
    'Chuyển khoản ngân hàng': 'Chuyển khoản ngân hàng',
    'Thẻ tín dụng': 'Thẻ tín dụng'
  }
  return methodMap[method] || method
}

const getPaymentStatusText = status => {
  const statusMap = {
    pending: 'Chờ thanh toán',
    paid: 'Đã thanh toán',
    failed: 'Thanh toán thất bại',
    'Chờ thanh toán': 'Chờ thanh toán',
    'Đã thanh toán': 'Đã thanh toán',
    'Thanh toán thất bại': 'Thanh toán thất bại'
  }
  return statusMap[status] || status
}

// =====================
// CRUD: Sửa, xóa, lưu đơn hàng
// =====================
// Mở modal chỉnh sửa đơn hàng
const editOrder = async orderId => {
  try {
    loading.value = true
    // Gọi API lấy chi tiết đơn hàng (nếu cần)
    const order = orders.value.find(o => o.id === orderId)
    if (order) {
      editingOrder.value = { ...order }
      showEditModal.value = true
    }
  } catch (err) {
    error.value = 'Không thể tải thông tin đơn hàng'
    console.error(err)
  } finally {
    loading.value = false
  }
}

// Xóa đơn hàng
const deleteOrder = async orderId => {
  if (!confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) return
  try {
    loading.value = true
    await orderService.delete(orderId)
    orders.value = orders.value.filter(order => order.id !== orderId)
    applyFilters()
  } catch (err) {
    error.value = err.message
    console.error(err)
  } finally {
    loading.value = false
  }
}

// Lưu thay đổi đơn hàng
const saveOrderChanges = async () => {
  try {
    loading.value = true
    const updatedOrder = await orderService.update(editingOrder.value.id, {
      status: editingOrder.value.status,
      payment_status: editingOrder.value.payment_status
    })
    
    if (updatedOrder) {
      const index = orders.value.findIndex(order => order.id === editingOrder.value.id)
      if (index !== -1) {
        orders.value[index] = { ...editingOrder.value }
        applyFilters()
        showEditModal.value = false
      }
    }
  } catch (err) {
    error.value = err.message
    console.error(err)
  } finally {
    loading.value = false
  }
}

// =====================
// Load dữ liệu ban đầu
// =====================
onMounted(async () => {
  try {
    loading.value = true
    // Gọi API lấy danh sách đơn hàng
    const response = await axios.get(API_ENDPOINTS.ORDERS.GET_ALL)
    orders.value = response.data
    applyFilters()
  } catch (err) {
    error.value = 'Không thể tải danh sách đơn hàng'
    console.error(err)
  } finally {
    loading.value = false
  }
})
</script>
