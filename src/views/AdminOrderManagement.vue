<template>
  <!-- Trang quản lý đơn hàng cho admin -->
  <div class="min-h-screen bg-gray-50 pt-8">
    <!-- Tiêu đề trang -->
    <div class="text-center text-3xl font-bold mb-8 text-[#5a3a1b]">Danh sách đơn hàng</div>
    <div class="container-fluid px-4">
      <!-- Thanh tìm kiếm với input và icon search -->
      <div class="mb-6">
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm kiếm theo mã đơn hàng, tên khách hàng..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
            @input="applyFilters"
          />
          <!-- Icon search -->
          <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <i class="fas fa-search text-gray-400"></i>
          </div>
        </div>
      </div>

      <!-- Hiển thị trạng thái loading với animation xoay -->
      <div v-if="loading" class="text-center py-10">
        <div
          class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-orange-500 border-t-transparent"
        ></div>
        <p class="mt-2 text-gray-600">Đang tải dữ liệu...</p>
      </div>

      <!-- Hiển thị thông báo lỗi với nút thử lại -->
      <div v-else-if="error" class="bg-red-50 p-4 rounded-lg text-center text-red-600">
        {{ error }}
        <button @click="fetchOrders" class="ml-2 underline">Thử lại</button>
      </div>

      <!-- Hiển thị khi không có đơn hàng nào phù hợp -->
      <div
        v-else-if="filteredOrders.length === 0"
        class="bg-white rounded-lg shadow p-10 text-center"
      >
        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">Không tìm thấy đơn hàng nào phù hợp với bộ lọc</p>
      </div>

      <!-- Bảng danh sách đơn hàng với các cột có thể sắp xếp -->
      <div v-else class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full border-collapse">
          <!-- Header của bảng với các cột có thể click để sắp xếp -->
          <thead>
            <tr>
              <th class="bg-gray-100 p-4 text-left cursor-pointer" @click="handleSort('order_id')">
                ID đơn hàng
              </th>
              <th
                class="bg-gray-100 p-4 text-left cursor-pointer"
                @click="handleSort('order_date')"
              >
                Ngày đặt
              </th>
              <th
                class="bg-gray-100 p-4 text-left cursor-pointer"
                @click="handleSort('customer_name')"
              >
                Khách hàng
              </th>
              <th class="bg-gray-100 p-4 text-left cursor-pointer" @click="handleSort('status')">
                Trạng thái đơn hàng
              </th>
              <th
                class="bg-gray-100 p-4 text-left cursor-pointer"
                @click="handleSort('total_amount')"
              >
                Tổng giá trị
              </th>
              <th
                class="bg-gray-100 p-4 text-left cursor-pointer"
                @click="handleSort('payment_method')"
              >
                Phương thức thanh toán
              </th>
              <th
                class="bg-gray-100 p-4 text-left cursor-pointer"
                @click="handleSort('payment_status')"
              >
                Trạng thái thanh toán
              </th>
              <th class="bg-gray-100 p-4 text-left cursor-pointer">Thao tác</th>
            </tr>
          </thead>
          <!-- Body của bảng hiển thị danh sách đơn hàng -->
          <tbody>
            <tr v-for="order in paginatedOrders" :key="order.order_id" class="even:bg-gray-50">
              <td class="p-4">{{ order.order_id }}</td>
              <td class="p-4">{{ formatDate(order.order_date) }}</td>
              <td class="p-4">{{ order.customer_name || 'Không có thông tin' }}</td>
              <td class="p-4">{{ getStatusText(order.status) }}</td>
              <td class="p-4">{{ formatPrice(order.total_amount) }}</td>
              <td class="p-4">{{ order.payment_method }}</td>
              <td class="p-4">{{ getPaymentStatusText(order.payment_status) }}</td>
              <!-- Các nút thao tác với đơn hàng -->
              <td class="p-4">
                <button
                  v-if="order.status === 'pending'"
                  @click="confirmOrder(order)"
                  class="px-3 py-1 bg-orange-500 text-white rounded hover:bg-orange-600 text-xs font-semibold mr-2"
                >
                  Xác nhận
                </button>
                <button
                  @click="viewOrderDetail(order)"
                  class="p-1 text-gray-600 hover:opacity-70"
                  title="Xem chi tiết"
                >
                  <i class="fas fa-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Phân trang với component BasePagination -->
      <div v-if="filteredOrders.length > 0" class="flex justify-center items-center gap-4 mt-4">
        <BasePagination
          :current-page="currentPage"
          :total-pages="totalPages"
          @page="handlePageChange"
          @prev="currentPage > 1 && currentPage--"
          @next="currentPage < totalPages && currentPage++"
        />
      </div>
    </div>

    <!-- Modal chi tiết đơn hàng -->
    <div
      v-if="selectedOrder && showDetailModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
    >
      <div class="bg-white rounded-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <!-- Header của modal -->
        <div class="flex justify-between items-center border-b p-4">
          <h2 class="text-lg font-bold">Chi tiết đơn hàng #{{ selectedOrder.order_id }}</h2>
          <button class="text-2xl text-gray-500 hover:text-gray-700" @click="closeDetailModal">
            &times;
          </button>
        </div>

        <!-- Body của modal -->
        <div class="p-4">
          <!-- Thông tin cơ bản của đơn hàng -->
          <div class="mb-6">
            <h3 class="text-gray-700 font-semibold mb-3">Thông tin đơn hàng</h3>
            <div class="bg-gray-50 p-4 rounded grid grid-cols-2 gap-x-4 gap-y-2">
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Mã đơn hàng:</span>
                <span>{{ selectedOrder.order_id }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Ngày đặt:</span>
                <span>{{ formatDate(selectedOrder.order_date) }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Trạng thái:</span>
                <span
                  :class="[
                    'px-2 py-1 rounded text-xs font-semibold',
                    selectedOrder.status === 'pending' && 'bg-red-100 text-red-800',
                    selectedOrder.status === 'processing' && 'bg-yellow-100 text-yellow-800',
                    selectedOrder.status === 'shipped' && 'bg-blue-100 text-blue-800',
                    selectedOrder.status === 'delivered' && 'bg-green-100 text-green-800'
                  ]"
                >
                  {{ getStatusText(selectedOrder.status) }}
                </span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Thanh toán:</span>
                <span
                  :class="[
                    'px-2 py-1 rounded text-xs font-semibold',
                    selectedOrder.payment_status === 'unpaid' && 'bg-yellow-100 text-yellow-800',
                    selectedOrder.payment_status === 'paid' && 'bg-green-100 text-green-800'
                  ]"
                >
                  {{ getPaymentStatusText(selectedOrder.payment_status) }}
                </span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Phương thức thanh toán:</span>
                <span>{{ selectedOrder.payment_method || 'Không có thông tin' }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Tổng giá trị:</span>
                <span class="font-bold text-orange-500">{{
                  formatPrice(selectedOrder.total_amount)
                }}</span>
              </div>
            </div>
          </div>

          <!-- Thông tin khách hàng -->
          <div class="mb-6">
            <h3 class="text-gray-700 font-semibold mb-3">Thông tin khách hàng</h3>
            <div class="bg-gray-50 p-4 rounded">
              <div class="mb-2">
                <span class="font-medium text-gray-600 mr-2">Tên khách hàng:</span>
                <span>{{ selectedOrder.customer_name || 'Không có thông tin' }}</span>
              </div>
              <div class="mb-2">
                <span class="font-medium text-gray-600 mr-2">Số điện thoại:</span>
                <span>{{ selectedOrder.phone || 'Không có thông tin' }}</span>
              </div>
              <div class="mb-2">
                <span class="font-medium text-gray-600 mr-2">Địa chỉ:</span>
                <span>{{ selectedOrder.address_line || 'Không có thông tin' }}</span>
              </div>
            </div>
          </div>

          <!-- Danh sách sản phẩm -->
          <div class="mb-6">
            <h3 class="text-gray-700 font-semibold mb-3">Sản phẩm trong đơn</h3>

            <!-- Loading state cho chi tiết sản phẩm -->
            <div v-if="loading" class="py-4 text-center text-gray-500">
              <div
                class="inline-block animate-spin rounded-full h-5 w-5 border-2 border-orange-500 border-t-transparent mr-2"
              ></div>
              Đang tải chi tiết sản phẩm...
            </div>

            <!-- Hiển thị chi tiết sản phẩm từ API -->
            <div
              v-else-if="selectedOrder.productDetails && selectedOrder.productDetails.length > 0"
              class="bg-white border rounded divide-y"
            >
              <div
                v-for="(item, index) in selectedOrder.productDetails"
                :key="index"
                class="p-3 flex items-center"
              >
                <div class="w-16 h-16 mr-3 flex-shrink-0">
                  <img
                    :src="item.image"
                    :alt="item.name"
                    class="w-full h-full object-cover rounded"
                  />
                </div>
                <div class="flex-1">
                  <div class="font-medium">{{ item.name }}</div>
                  <div class="text-sm text-gray-500">{{ item.category }}</div>
                  <div class="text-sm">Số lượng: {{ item.count }}</div>
                </div>
                <div class="text-right">
                  <div class="font-medium">{{ formatPrice(item.price) }}</div>
                  <div class="text-sm text-gray-500">x {{ item.count }}</div>
                  <div class="font-semibold text-orange-500">
                    {{ formatPrice(item.price * item.count) }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Fallback hiển thị items từ list view -->
            <div
              v-else-if="selectedOrder.items && selectedOrder.items.length > 0"
              class="bg-white border rounded divide-y"
            >
              <div
                v-for="(item, index) in selectedOrder.items"
                :key="index"
                class="p-3 flex items-center"
              >
                <div class="w-16 h-16 mr-3 flex-shrink-0">
                  <img
                    :src="item.image"
                    :alt="item.name"
                    class="w-full h-full object-cover rounded"
                  />
                </div>
                <div class="flex-1">
                  <div class="font-medium">{{ item.name }}</div>
                  <div class="text-sm text-gray-500">{{ item.category }}</div>
                  <div class="text-sm">Số lượng: {{ item.count }}</div>
                </div>
                <div class="text-right">
                  <div class="font-medium">{{ formatPrice(item.price) }}</div>
                  <div class="text-sm text-gray-500">x {{ item.count }}</div>
                  <div class="font-semibold text-orange-500">
                    {{ formatPrice(item.price * item.count) }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Trạng thái không có sản phẩm -->
            <div v-else class="bg-gray-50 p-4 text-center text-gray-500 rounded">
              Không có sản phẩm nào trong đơn hàng này
            </div>
          </div>
        </div>

        <!-- Footer của modal -->
        <div class="flex justify-end gap-4 border-t p-4">
          <button
            class="px-4 py-2 rounded border border-gray-300 font-semibold text-gray-700 hover:bg-gray-100"
            @click="closeDetailModal"
          >
            Đóng
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import BasePagination from '@/components/BasePagination.vue'
import axios from 'axios'

// =====================
// Biến trạng thái reactive
// =====================
const orders = ref([]) // Danh sách đơn hàng gốc từ API
const filteredOrders = ref([]) // Danh sách đơn hàng sau khi lọc và sắp xếp
const loading = ref(false) // Trạng thái đang tải dữ liệu
const error = ref(null) // Lưu thông báo lỗi nếu có
const searchQuery = ref('') // Từ khóa tìm kiếm
const currentPage = ref(1) // Trang hiện tại của phân trang
const itemsPerPage = ref(10) // Số item hiển thị trên mỗi trang
const selectedOrder = ref(null) // Đơn hàng được chọn để xem chi tiết
const showDetailModal = ref(false) // Trạng thái hiển thị modal chi tiết
const sortField = ref('order_date') // Trường đang sắp xếp (mặc định là ngày đặt)
const sortDirection = ref('desc') // Hướng sắp xếp (mặc định là giảm dần)

const api = axios.create({
  baseURL: 'http://localhost:8000/api/v1',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json'
  }
})

// =====================
// Các computed property
// =====================
// Tính tổng số trang dựa trên số lượng đơn hàng đã lọc
const totalPages = computed(() => Math.ceil(filteredOrders.value.length / itemsPerPage.value))

// Lấy danh sách đơn hàng cho trang hiện tại
const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredOrders.value.slice(start, end)
})

// =====================
// Fetch orders from API
// =====================
const fetchOrders = async () => {
  loading.value = true
  error.value = null

  try {
    // Gọi API lấy danh sách đơn hàng
    const response = await api.get('/orders/filter')

    if (response.data.success) {
      orders.value = response.data.orders || []
      applyFilters() // Áp dụng bộ lọc và sắp xếp
    } else {
      error.value = response.data.message || 'Không thể tải danh sách đơn hàng'
    }
  } catch (err) {
    console.error('Error fetching orders:', err)
    error.value = 'Lỗi kết nối đến máy chủ. Vui lòng thử lại sau.'
  } finally {
    loading.value = false
  }
}

// =====================
// Hàm lọc, tìm kiếm, sắp xếp
// =====================
const applyFilters = () => {
  let filtered = [...orders.value]

  // Áp dụng tìm kiếm theo mã đơn hàng, tên khách hàng, số điện thoại
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase().trim()
    filtered = filtered.filter(order => {
      return (
        (order.order_id?.toString() || '').toLowerCase().includes(query) ||
        (order.customer_name || '').toLowerCase().includes(query) ||
        (order.phone || '').toLowerCase().includes(query)
      )
    })
  }

  // Sắp xếp theo trường và hướng được chọn
  filtered.sort((a, b) => {
    let aVal = a[sortField.value]
    let bVal = b[sortField.value]

    // Xử lý đặc biệt cho trường total_amount (chuyển sang số)
    if (sortField.value === 'total_amount') {
      aVal = parseFloat(aVal)
      bVal = parseFloat(bVal)
    }

    // Sắp xếp tăng dần hoặc giảm dần
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
// Định dạng ngày theo chuẩn Việt Nam
const formatDate = date => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('vi-VN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Định dạng giá tiền VND
const formatPrice = price => {
  if (!price) return '0 đ'
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(price)
}

// =====================
// Hàm mapping trạng thái sang tiếng Việt
// =====================
// Chuyển trạng thái đơn hàng sang tiếng Việt
const getStatusText = status => {
  const statusMap = {
    pending: 'Chờ xác nhận',
    processing: 'Đang xử lý',
    shipped: 'Đang giao hàng',
    delivered: 'Đã nhận hàng',
    cancelled: 'Đã hủy'
  }
  return statusMap[status] || 'Không xác định'
}

// Chuyển trạng thái thanh toán sang tiếng Việt
const getPaymentStatusText = status => {
  const statusMap = {
    unpaid: 'Chờ thanh toán',
    paid: 'Đã thanh toán'
  }
  return statusMap[status] || 'Không xác định'
}

// =====================
// Xử lý modal chi tiết đơn hàng
// =====================
// Xem chi tiết đơn hàng trong popup
const viewOrderDetail = async order => {
  try {
    loading.value = true
    showDetailModal.value = true

    // Set thông tin cơ bản từ list trước
    selectedOrder.value = order

    // Fetch chi tiết đầy đủ từ API
    const response = await api.get(`/orders/${order.order_id}`)

    const data = response.data

    if (data) {
      // Xử lý chi tiết đơn hàng
      const orderDetails = data

      // Tạo object đơn hàng có cấu trúc với chi tiết
      const detailedOrder = {
        ...order,
        orderDetails: orderDetails,
        productDetails: processOrderDetails(orderDetails)
      }

      // Cập nhật đơn hàng được chọn với thông tin chi tiết
      selectedOrder.value = detailedOrder
    }
  } catch (err) {
    console.error('Error fetching order details:', err)
    error.value = 'Không thể tải chi tiết đơn hàng'
  } finally {
    loading.value = false
  }
}

// Xử lý chi tiết đơn hàng để nhóm sản phẩm
const processOrderDetails = details => {
  if (!Array.isArray(details) || details.length === 0) return []

  // Nhóm theo product_id để tránh trùng lặp
  const productMap = {}

  details.forEach(item => {
    if (!productMap[item.product_id]) {
      productMap[item.product_id] = {
        product_id: item.product_id,
        name: item.product_name,
        count: parseInt(item.quantity) || 0,
        price: parseFloat(item.unit_price) || 0,
        image: item.image || 'https://via.placeholder.com/100',
        category: item.category || 'Sản phẩm'
      }
    } else {
      // Nếu sản phẩm đã tồn tại, cộng thêm số lượng
      productMap[item.product_id].count += parseInt(item.quantity) || 0
    }
  })

  return Object.values(productMap)
}

// Đóng modal chi tiết đơn hàng
const closeDetailModal = () => {
  showDetailModal.value = false
  selectedOrder.value = null
}

// =====================
// Load dữ liệu ban đầu
// =====================
onMounted(() => {
  fetchOrders()
})

// =====================
// Xử lý xác nhận đơn hàng
// =====================
const confirmOrder = async order => {
  if (!order || order.status !== 'pending') return
  try {
    // Gọi API cập nhật trạng thái
    const response = await api.put(`/orders/${order.order_id}/status`)
    console.log('Response:', response)
    if (response.status !== 200) {
      throw new Error('Không thể xác nhận đơn hàng. Vui lòng thử lại sau.')
    }
    // Cập nhật lại danh sách đơn hàng
    await fetchOrders()
  } catch (err) {
    alert(err.message || 'Có lỗi xảy ra khi xác nhận đơn hàng')
  }
}

// Hàm xử lý chuyển trang
const handlePageChange = page => {
  currentPage.value = page
}

// Hàm xử lý sắp xếp khi click vào header
const handleSort = field => {
  if (sortField.value === field) {
    // Nếu đang sắp xếp theo trường này, đảo chiều sắp xếp
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    // Nếu chọn trường mới, mặc định sắp xếp giảm dần
    sortField.value = field
    sortDirection.value = 'desc'
  }
  applyFilters()
}
</script>
