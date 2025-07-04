<template>
  <!-- Trang giỏ hàng: Hiển thị các sản phẩm khách đã chọn, địa chỉ nhận hàng, tổng tiền và các thao tác -->
  <div class="bg-white rounded-2xl p-8 max-w-7xl mx-auto my-8">
    <!-- Breadcrumb điều hướng -->
    <div class="mb-4">
      <router-link to="/" class="text-gray-600 hover:text-orange-500">Trang chủ</router-link>
      &gt;
      <span class="text-gray-800">Giỏ hàng</span>
    </div>
    <h1 class="text-2xl font-bold mb-6">Giỏ hàng</h1>

    <!-- Thanh tiến trình các bước mua hàng -->
    <div class="flex items-center justify-center my-8">
      <!-- Bước 1: Vận chuyển -->
      <div class="flex flex-col items-center text-orange-500">
        <div
          class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center mb-2 font-bold"
        >
          1
        </div>
        <div>Vận chuyển</div>
      </div>
      <div class="w-24 h-0.5 bg-gray-200 mx-4"></div>
      <!-- Bước 2: Thanh toán -->
      <div class="flex flex-col items-center text-gray-400">
        <div
          class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mb-2 font-bold"
        >
          2
        </div>
        <div>Kiểm tra & Thanh toán</div>
      </div>
      <div class="w-24 h-0.5 bg-gray-200 mx-4"></div>
      <!-- Bước 3: Thành công -->
      <div class="flex flex-col items-center text-gray-400">
        <div
          class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mb-2 font-bold"
        >
          3
        </div>
        <div>Thành công</div>
      </div>
    </div>

    <!-- Địa chỉ nhận hàng -->
    <div class="flex flex-col items-center my-4">
      <h3 class="flex items-center gap-2 text-orange-500 font-semibold text-lg mb-2">
        <i class="fas fa-map-marker-alt"></i> Địa chỉ nhận hàng
      </h3>

      <div v-if="Object.keys(shippingAddress).length > 0" class="flex items-center gap-4">
        <div>
          <span class="font-semibold">{{ shippingAddress.name || 'Người nhận' }}</span>
          <span class="mx-2">|</span>
          <span>{{ shippingAddress.phone || 'Chưa có số điện thoại' }}</span>
          <span class="mx-2">|</span>
          <span>{{ shippingAddress.address_line || '' }}</span>
          <span
            v-if="shippingAddress.is_default"
            class="border border-orange-500 text-orange-500 rounded px-2 py-1 ml-2 text-xs"
            >Mặc định</span
          >
        </div>
      </div>

      <div v-else class="text-center py-4">
        <p class="text-gray-500 mb-2">Chưa có địa chỉ giao hàng</p>
        <button
          @click="goToUserAccount"
          class="bg-orange-500 text-white px-4 py-2 rounded"
        >
          Thêm địa chỉ mới
        </button>
      </div>
    </div>

    <!-- Hiển thị khi giỏ hàng trống -->
    <div v-if="cartItems.length === 0" class="text-center py-16">
      <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
      <p class="text-gray-500 text-lg mb-4">Giỏ hàng của bạn đang trống.</p>
      <router-link
        to="/products"
        class="inline-block bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors"
      >
        Tiếp tục mua sắm
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
      <!-- Danh sách sản phẩm -->
      <div class="bg-white rounded-xl overflow-hidden">
        <div
          v-for="item in cartItems"
          :key="item.cart_item_id"
          class="grid grid-cols-1 md:grid-cols-[2fr_1fr_1fr_1fr_auto] items-center p-4 border-b border-gray-200 transition-all duration-300"
        >
          <div class="flex items-center gap-4">
            <img
              :src="item.product.image_url || 'https://via.placeholder.com/100'"
              :alt="item.product.image_url"
              class="w-20 h-20 object-cover rounded-lg"
            />
            <div class="flex-1">
              <h3 class="font-semibold mb-1 text-lg">
                {{ item.product.product_name || 'Sản phẩm không xác định' }}
              </h3>
            </div>
          </div>
          <div class="text-gray-800">{{ formatPrice(item.product.base_price) }}</div>
          <div class="flex items-center gap-2">
            <!-- Nút giảm số lượng -->
            <button
              @click="decrease(item)"
              class="w-8 h-8 border border-gray-300 rounded bg-white hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="item.quantity <= 1"
            >
              -
            </button>
            <span class="min-w-10 text-center">{{ item.quantity || 1 }}</span>
            <!-- Nút tăng số lượng -->
            <button
              @click="increase(item)"
              class="w-8 h-8 border border-gray-300 rounded bg-white hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-colors"
            >
              +
            </button>
          </div>
          <div class="text-gray-800">{{ formatPrice(getItemTotal(item)) }}</div>
          <!-- Nút xóa sản phẩm -->
          <button
            @click="remove(item)"
            class="text-red-500 hover:text-red-700 p-2 transition-transform hover:scale-110"
            title="Xóa sản phẩm"
          >
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
      </div>

      <!-- Add this button inside the "Bảng tổng kết đơn hàng" div, after the total amount -->
      <div class="bg-orange-50 rounded-xl p-6 h-fit">
        <h2 class="text-xl font-bold mb-4">Tổng đơn hàng</h2>
        <div class="flex justify-between text-gray-600 mb-3">
          <span>Tạm tính:</span>
          <span class="font-semibold">{{ formatPrice(subtotal) }}</span>
        </div>
        <div class="flex justify-between text-gray-600 mb-3">
          <span>Vận chuyển:</span>
          <span class="font-semibold">{{ formatPrice(shipping) }}</span>
        </div>
        <div class="flex justify-between mt-4 pt-4 border-t border-gray-300 mb-6">
          <span>Tổng cộng:</span>
          <span class="font-bold text-xl text-orange-500">{{ formatPrice(total) }}</span>
        </div>

        <!-- Add checkout button -->
        <button
          @click="checkout"
          class="w-full bg-orange-500 text-white py-3 rounded-md font-semibold hover:bg-orange-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="isLoading || cartItems.length === 0 || !shippingAddress.address_id"
        >
          <span v-if="isLoading"> <i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý... </span>
          <span v-else>Thanh toán</span>
        </button>

        <!-- Payment method selection -->
        <div class="mt-4 border-t border-gray-200 pt-4">
          <h3 class="font-semibold mb-3">Phương thức thanh toán</h3>
          <div class="flex items-center gap-2 mb-2">
            <input
              type="radio"
              id="cod"
              name="payment"
              value="COD"
              v-model="paymentMethod"
              class="accent-orange-500"
            />
            <label for="cod" class="cursor-pointer">Thanh toán khi nhận hàng (COD)</label>
          </div>
          <div class="flex items-center gap-2 opacity-50 cursor-not-allowed">
            <input
              type="radio"
              disabled
              id="banking"
              name="payment"
              value="Banking"
              class="accent-orange-500"
            />
            <label for="banking">Chuyển khoản ngân hàng (Sắp ra mắt)</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import các thư viện cần thiết từ Vue
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCart } from '@/store/cart'
import axios from 'axios'

// =====================
// Router & Store Setup
// =====================
const router = useRouter()
// Sử dụng store giỏ hàng đã được cập nhật với xác thực token
const { cartItems, fetchCart, updateQuantity, removeItem } = useCart()

// =====================
// State Management
// =====================
// State cho địa chỉ giao hàng
const shippingAddress = ref({})
// Phí vận chuyển cố định
const shipping = ref(30000)
// Theo dõi trạng thái loading khi thực hiện API calls
const isLoading = ref(false)
// Theo dõi các item đang được xử lý để tránh double click
const processingItems = ref({})
// Phương thức thanh toán mặc định là COD
const paymentMethod = ref('COD')

// =====================
// Helper Functions
// =====================
/**
 * Format giá tiền theo định dạng tiền tệ Việt Nam
 */
const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(price)
}

/**
 * Tính tổng tiền cho một sản phẩm (giá * số lượng)
 */
const getItemTotal = item => {
  if (!item || !item.product) return 0
  const price = Number(item.product.base_price) || 0
  const quantity = Number(item.quantity) || 1
  return price * quantity
}

// =====================
// Lifecycle Hooks
// =====================
// Fetch cart items và địa chỉ mặc định khi component mounted
onMounted(async () => {
  await fetchCart()
  await fetchDefaultAddress()
})

// =====================
// API Calls
// =====================
/**
 * Lấy địa chỉ mặc định của user từ API
 * Lưu vào state và localStorage để sử dụng sau này
 */
const fetchDefaultAddress = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/v1/address', {
      withCredentials: true
    })

    if (response.data && response.data.success) {
      const addresses = response.data.addresses
      // Tìm địa chỉ mặc định trong danh sách
      const defaultAddress = addresses.find(addr => addr.is_default === 1)
      if (defaultAddress) {
        // Format địa chỉ để hiển thị
        shippingAddress.value = {
          address_id: defaultAddress.address_id,
          name: defaultAddress.customer_name || 'Người nhận',
          phone: defaultAddress.phone || 'Chưa có số điện thoại',
          address_line: defaultAddress.address_line,
          is_default: true
        }
        // Lưu vào localStorage để sử dụng ở các trang khác
        localStorage.setItem('shipping_address', JSON.stringify(shippingAddress.value))
      }
    }
  } catch (error) {
    console.error('Lỗi khi tải địa chỉ:', error)
  }
}

// =====================
// Computed Properties
// =====================
/**
 * Tính tổng tiền hàng (chưa tính phí ship)
 */
const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => {
    const price = Number(item.product?.base_price) || 0
    const quantity = Number(item.quantity) || 1
    return total + price * quantity
  }, 0)
})

/**
 * Tính tổng tiền phải trả (bao gồm phí ship)
 */
const total = computed(() => {
  return subtotal.value + shipping.value
})

// =====================
// Cart Item Actions
// =====================
/**
 * Tăng số lượng sản phẩm trong giỏ hàng
 */
async function increase(item) {
  try {
    // Kiểm tra item đang được xử lý
    if (processingItems.value[item.cart_item_id]) return
    processingItems.value[item.cart_item_id] = true

    // Tạo quantity mới
    const newQuantity = item.quantity + 1

    // Gọi API cập nhật số lượng
    await updateQuantity(item, newQuantity)
  } catch (error) {
    console.error('Error increasing item quantity:', error)
  } finally {
    // Reset trạng thái xử lý
    processingItems.value[item.cart_item_id] = false
  }
}

/**
 * Giảm số lượng sản phẩm trong giỏ hàng
 * Chỉ giảm khi số lượng > 1
 */
async function decrease(item) {
  if (item.quantity <= 1 || processingItems.value[item.cart_item_id]) return

  try {
    processingItems.value[item.cart_item_id] = true

    // Tạo quantity mới
    const newQuantity = item.quantity - 1

    // Gọi API cập nhật số lượng
    await updateQuantity(item, newQuantity)
  } catch (error) {
    console.error('Error decreasing item quantity:', error)
  } finally {
    // Reset trạng thái xử lý
    processingItems.value[item.cart_item_id] = false
  }
}

/**
 * Xóa sản phẩm khỏi giỏ hàng
 */
async function remove(item) {
  if (processingItems.value[item.cart_item_id]) return

  try {
    // Xác nhận trước khi xóa
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
      processingItems.value[item.cart_item_id] = true
      await removeItem(item)
    }
  } catch (error) {
    console.error('Error removing item from cart:', error)
    alert('Không thể xóa sản phẩm khỏi giỏ hàng')
  } finally {
    // Reset trạng thái xử lý
    processingItems.value[item.cart_item_id] = false
  }
}

// =====================
// Checkout Process
// =====================
/**
 * Xử lý quá trình thanh toán
 * Kiểm tra các điều kiện cần thiết trước khi chuyển sang trang checkout
 */
async function checkout() {
  // Kiểm tra địa chỉ giao hàng
  if (!shippingAddress.value || !shippingAddress.value.address_id) {
    alert('Vui lòng chọn địa chỉ giao hàng trước khi thanh toán')
    return
  }

  // Kiểm tra giỏ hàng
  if (!cartItems.value || cartItems.value.length === 0) {
    alert('Giỏ hàng của bạn đang trống')
    return
  }

  try {
    isLoading.value = true

    // Lưu dữ liệu đơn hàng vào localStorage để sử dụng ở trang checkout
    const checkoutData = {
      cartItems: cartItems.value,
      shippingAddress: shippingAddress.value,
      subtotal: subtotal.value,
      shipping: shipping.value,
      total: total.value,
      paymentMethod: paymentMethod.value
    }

    // Lưu vào localStorage để có thể truy cập ở trang checkout
    localStorage.setItem('checkout_data', JSON.stringify(checkoutData))

    // Chuyển hướng sang trang checkout
    router.push('/checkout')
  } catch (error) {
    console.error('Error preparing checkout:', error)
    alert('Không thể tiếp tục thanh toán: ' + error.message || 'Lỗi không xác định')
  } finally {
    isLoading.value = false
  }
}

// Chuyển hướng đến trang tài khoản để thêm địa chỉ
function goToUserAccount() {
  router.push('/account')
}
</script>

<style scoped>
/* Style cho hiệu ứng xóa sản phẩm */
.cart-item.removing {
  transform: translateX(100%);
  opacity: 0;
}

/* Style cho các nút thao tác trên mobile */
@media (hover: none) {
  .quantity-controls button,
  .remove-btn,
  .checkout-btn {
    min-height: 44px;
    min-width: 44px;
  }
}
</style>
