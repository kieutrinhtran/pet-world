<template>
  <!-- Container chính của trang giỏ hàng -->
  <div class="bg-white rounded-2xl p-8 max-w-7xl mx-auto my-8">
    <div class="mb-4">
      <router-link to="/" class="text-gray-600 hover:text-orange-500">Trang chủ</router-link> &gt;
      <span class="text-gray-800">Giỏ hàng</span>
    </div>
    <h1 class="text-2xl font-bold mb-6">Giỏ hàng</h1>

    <div class="flex items-center justify-center my-8">
      <div class="flex flex-col items-center text-orange-500">
        <div class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center mb-2 font-bold">1</div>
        <div>Vận chuyển</div>
      </div>
      <div class="w-24 h-0.5 bg-gray-200 mx-4"></div>
      <div class="flex flex-col items-center text-gray-400">
        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mb-2 font-bold">2</div>
        <div>Kiểm tra & Thanh toán</div>
      </div>
      <div class="w-24 h-0.5 bg-gray-200 mx-4"></div>
      <div class="flex flex-col items-center text-gray-400">
        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mb-2 font-bold">3</div>
        <div>Thành công</div>
      </div>
    </div>

    <!-- Địa chỉ nhận hàng (đặt ở đây) -->
    <div class="flex flex-col items-center my-4">
      <h3 class="flex items-center gap-2 text-orange-500 font-semibold text-lg mb-2">
        <i class="fas fa-map-marker-alt"></i> Địa chỉ nhận hàng
      </h3>
      <div class="flex items-center gap-4">
        <div>
          <span class="font-semibold">{{ shippingAddress.name }}</span>
          <span class="mx-2">|</span>
          <span>{{ shippingAddress.phone }}</span>
          <span class="mx-2">|</span>
          <span>{{ shippingAddress.address_line }}</span>
          <span v-if="shippingAddress.is_default" class="border border-orange-500 text-orange-500 rounded px-2 py-1 ml-2 text-xs">Mặc định</span>
        </div>
        <button @click="showAddressSelector = true" class="text-orange-500 font-semibold ml-4">Thay đổi</button>
      </div>
      <AddressSelector
        v-if="showAddressSelector"
        :visible="showAddressSelector"
        @close="showAddressSelector = false"
        @address-selected="onAddressSelected"
      />
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

    <!-- Nội dung chính của giỏ hàng khi có sản phẩm -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
      <div class="bg-white rounded-xl overflow-hidden">
        <div v-for="item in cartItems" :key="item.id" class="grid grid-cols-1 md:grid-cols-[2fr_1fr_1fr_1fr_auto] items-center p-4 border-b border-gray-200 transition-all duration-300">
          <div class="flex items-center gap-4">
            <img :src="item.image" :alt="item.name" class="w-20 h-20 object-cover rounded-lg" />
            <div class="flex-1">
              <h3 class="font-semibold mb-1 text-lg">{{ item.name }}</h3>
              <p class="text-gray-600 text-sm">{{ item.category }}</p>
            </div>
          </div>
          <div class="text-gray-800">{{ item.price.toLocaleString() }}đ</div>
          <div class="flex items-center gap-2">
            <button 
              @click="decrease(item)" 
              class="w-8 h-8 border border-gray-300 rounded bg-white hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="item.quantity <= 1"
            >
              -
            </button>
            <span class="min-w-10 text-center">{{ item.quantity }}</span>
            <button 
              @click="increase(item)" 
              class="w-8 h-8 border border-gray-300 rounded bg-white hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-colors"
            >
              +
            </button>
          </div>
          <div class="text-gray-800">{{ (item.price * item.quantity).toLocaleString() }}đ</div>
          <button 
            @click="remove(item)" 
            class="text-red-500 hover:text-red-700 p-2 transition-transform hover:scale-110"
            title="Xóa sản phẩm"
          >
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
      </div>

      <div class="bg-orange-50 rounded-xl p-6 h-fit">
        <h2 class="text-xl font-bold mb-4">Tổng đơn hàng</h2>
        <div class="flex justify-between text-gray-600 mb-3">
          <span>Tạm tính:</span>
          <span class="font-semibold">{{ subtotal.toLocaleString() }}đ</span>
        </div>
        <div class="flex justify-between text-gray-600 mb-3">
          <span>Vận chuyển:</span>
          <span class="font-semibold">{{ shipping.toLocaleString() }}đ</span>
        </div>
        <div class="flex justify-between mt-4 pt-4 border-t border-gray-300">
          <span>Tổng cộng:</span>
          <span class="font-bold text-xl text-orange-500">{{ total.toLocaleString() }}đ</span>
        </div>
        <button 
          @click="goCheckout" 
          class="w-full bg-orange-500 text-white rounded-lg py-3 text-lg mt-4 flex items-center justify-center hover:bg-orange-600 transition-all hover:-translate-y-0.5"
        >
          Tiến hành thanh toán
          <i class="fas fa-arrow-right ml-2"></i>
        </button>
        <router-link 
          to="/products" 
          class="block text-center mt-3 text-gray-600 hover:text-orange-500 transition-colors"
        >
          <i class="fas fa-arrow-left mr-2"></i>
          Tiếp tục mua sắm
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import các thư viện cần thiết từ Vue
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AddressSelector from '@/components/AddressSelector.vue'

// Khởi tạo dữ liệu giỏ hàng mẫu
// Trong thực tế, dữ liệu này sẽ được lấy từ API
const cartItems = ref([
  {
    id: 1,
    name: 'Thức ăn cho chó Royal Canin',
    category: 'Thức ăn cho chó',
    price: 250000,
    quantity: 2,
    image: 'https://picsum.photos/200'
  },
  {
    id: 2,
    name: 'Cát vệ sinh cho mèo',
    category: 'Phụ kiện mèo',
    price: 150000,
    quantity: 1,
    image: 'https://picsum.photos/200'
  }
])

// Tính tổng tiền hàng (chưa tính phí vận chuyển)
const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0)
})

// Phí vận chuyển cố định
const shipping = ref(30000)

// Tính tổng tiền phải trả (bao gồm phí vận chuyển)
const total = computed(() => subtotal.value + shipping.value)

// Hàm tăng số lượng sản phẩm
function increase(item) {
  item.quantity++
}

// Hàm giảm số lượng sản phẩm (chỉ giảm khi số lượng > 1)
function decrease(item) {
  if (item.quantity > 1) {
    item.quantity--
  }
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function remove(item) {
  const index = cartItems.value.indexOf(item)
  if (index > -1) {
    cartItems.value.splice(index, 1)
  }
}

// Hàm chuyển hướng đến trang thanh toán
function goCheckout() {
  router.push('/checkout')
}

const router = useRouter()

// Các hàm API đã được comment lại - sẽ được implement sau
// async function fetchCart() {
//   const res = await axios.get('/api/cart')
//   cartItems.value = res.data.items
// }

// async function updateQuantity(itemId, quantity) {
//   await axios.put(`/api/cart/items/${itemId}`, { quantity })
//   fetchCart()
// }

// async function removeItem(itemId) {
//   await axios.delete(`/api/cart/items/${itemId}`)
//   fetchCart()
// }

// onMounted(fetchCart)

const showAddressSelector = ref(false)
const shippingAddress = ref({})

onMounted(() => {
  const saved = localStorage.getItem('shipping_address')
  if (saved) shippingAddress.value = JSON.parse(saved)
})

function onAddressSelected(address) {
  shippingAddress.value = address
  localStorage.setItem('shipping_address', JSON.stringify(address))
  showAddressSelector.value = false
}
</script>

<style scoped>
/* Chỉ giữ lại các styles không thể thay thế bằng Tailwind */
.cart-item.removing {
  transform: translateX(100%);
  opacity: 0;
}

/* CSS cho các phần tử tương tác trên mobile */
@media (hover: none) {
  .quantity-controls button,
  .remove-btn,
  .checkout-btn {
    min-height: 44px;
    min-width: 44px;
  }
}
</style>
