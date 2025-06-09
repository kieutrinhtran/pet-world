<template>
  <!-- Trang thông báo đặt hàng thành công -->
  <div class="order-success">
    <!-- Breadcrumb điều hướng các bước -->
    <div class="breadcrumb">
      <router-link to="/" class="text-gray-600 hover:text-orange-500">Trang chủ</router-link> &gt;
      <router-link to="/cart" class="text-gray-600 hover:text-orange-500">Giỏ hàng</router-link>
      &gt;
      <router-link to="/shipping" class="text-gray-600 hover:text-orange-500"
        >Vận chuyển</router-link
      >
      &gt;
      <router-link to="/checkout" class="text-gray-600 hover:text-orange-500"
        >Thanh toán</router-link
      >
      &gt;
      <span class="text-gray-800">Thành công</span>
    </div>

    <div class="success-content">
      <!-- Icon và tiêu đề thành công -->
      <div class="success-header">
        <div class="success-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="text-2xl font-bold mb-2">Đặt hàng thành công!</h1>
        <p class="text-gray-600">Cảm ơn bạn đã đặt hàng tại Pet Shop!</p>
      </div>

      <!-- Các nút thao tác sau khi đặt hàng -->
      <div class="order-actions">
        <router-link to="/" class="home-btn">
          <i class="fas fa-home mr-2"></i>
          Về trang chủ
        </router-link>
        <router-link to="/account" class="order-btn">
          <i class="fas fa-list-alt mr-2"></i>
          Xem tài khoản người dùng
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
// Không cần import và xử lý API vì đây là trang thông báo thành công đơn giản
</script>

<style scoped>
/* Breadcrumb styles */
.breadcrumb {
  @apply flex items-center gap-2 text-sm text-gray-500 ml-8 mt-8;
}

/* Success content styles */
.success-content {
  @apply max-w-2xl mx-auto text-center;
}

.success-header {
  @apply mb-8;
}

.success-icon {
  @apply w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4;
}

/* Button styles */
.order-actions {
  @apply flex flex-col sm:flex-row gap-4 justify-center;
}

.home-btn, .order-btn {
  @apply px-6 py-3 rounded-lg font-semibold transition-colors duration-200;
}

.home-btn {
  @apply bg-orange-500 text-white hover:bg-orange-600;
}

.order-btn {
  @apply bg-gray-100 text-gray-700 hover:bg-gray-200;
}

/* Responsive styles */
@media (max-width: 640px) {
  .success-content {
    @apply px-4;
  }
}
</style>
