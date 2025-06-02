<template>
  <div class="account-page">
    <!-- Banner -->
    <div class="banner-wrapper">
      <img class="banner" src="@/assets/Avatar-Banner.png" alt="Account Banner" />

      <!-- Thẻ đè lên banner -->
      <div class="user-card">
        <h2>Nguyễn Văn A</h2>
        <span class="role">User</span>
      </div>
    </div>

    <!-- Phần nội dung chính -->
    <div class="main-container">
      <!-- Bên trái: Giới thiệu -->
      <div class="sidebar">
        <div class="joined-info">
          <h4>Giới thiệu</h4>
          <p>Tham gia từ tháng 8 2025</p>
        </div>
      </div>

      <!-- Bên phải: Tabs + Form -->
      <div class="content">
        <!-- Tabs -->
        <div class="tabs">
          <button :class="{ active: activeTab === 'account' }" @click="activeTab = 'account'">Thông tin tài
            khoản</button>
          <button :class="{ active: activeTab === 'wishlist' }" @click="activeTab = 'wishlist'">Wish List</button>
          <button disabled>Sổ địa chỉ</button>
          <button disabled>Lịch sử mua hàng</button>
          <button disabled>Đổi mật khẩu</button>
        </div>

        <!-- Nội dung tab -->
        <div v-if="activeTab === 'account'" class="form-box">
          <h3>Thông tin tài khoản</h3>
          <form>
            <label>Họ và tên</label>
            <input type="text" value="Nguyễn Văn A" />

            <label>Ngày sinh</label>
            <input type="text" value="16/3/2002" />

            <label>Giới tính</label>
            <input type="text" value="Nam" />

            <label>Số điện thoại</label>
            <input type="text" value="0123456789" />

            <label>Email</label>
            <input type="email" value="nguyenvana@gmail.com" />

            <div class="form-actions">
              <button type="button" class="edit">Chỉnh sửa</button>
              <button type="submit" class="save">Lưu</button>
            </div>
          </form>
        </div>

        <div v-if="activeTab === 'wishlist'" class="wishlist-box">
          <!-- Thanh tìm kiếm -->
          <div class="search-bar">
            <input type="text" v-model="searchQuery" placeholder="Tên sản phẩm, tên shop..." />
            <button><i class="fas fa-search"></i></button>
          </div>

          <!-- Danh sách sản phẩm yêu thích -->
          <div class="product-list">
            <div v-for="(product, index) in paginatedProducts" :key="index" class="product-card">
              <img :src="product.image" alt="Ảnh sản phẩm" />
              <div class="product-info">
                <h4>{{ product.name }}</h4>
                <p>{{ product.price }}₫</p>
              </div>
              <div class="product-actions">
                <button class="add-to-cart">Thêm vào giỏ hàng</button>
                <button class="wishlist-button">❤️</button>
              </div>
            </div>
          </div>


          <!-- Thanh phân trang -->
          <div class="pagination" v-if="totalPages > 1">
            <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">←</button>
            <button v-for="page in totalPages" :key="page" @click="goToPage(page)"
              :class="{ active: currentPage === page }">
              {{ page }}
            </button>
            <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">→</button>
          </div>


        </div>
      </div>
    </div>
  </div>


</template>

<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('account')
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 2

const products = ref([
  { name: 'Hạt Royal Canin', price: '350,000', image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880' },
  { name: 'Thức ăn Pedigree', price: '350,000', image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880' },
  { name: 'Hạt Whiskas', price: '350,000', image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880' },
  { name: 'Hạt CatPy', price: '350,000', image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880' },
])
const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredProducts.value.slice(start, start + itemsPerPage)
})

const totalPages = computed(() => {
  return Math.ceil(filteredProducts.value.length / itemsPerPage)
})

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}
const filteredProducts = computed(() => {
  const query = searchQuery.value.toLowerCase()
  return products.value.filter(p => p.name.toLowerCase().includes(query))
})
</script>

<style scoped>
.account-page {
  background-color: #f9f9f9;
}

.banner-wrapper {
  position: relative;
  width: 100%;
  display: flex;
  height: 100%;
  overflow: hidden;
  justify-content: center;
  background-color: #f9f9f9;
  padding: 20px 0 30px;
}

.banner {
  width: 100%;
  height: auto;
  display: block;
}

.user-card {
  position: absolute;
  bottom: -25px;
  left: 20px;
  background: var(--Primary, #FF7C00);
  border-radius: 0px 757.746px 757.746px 0px;
  box-shadow: 0px 3.031px 3.031px rgba(0, 0, 0, 0.25);
  padding: 20px 40px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  color: white;
  width: 260px;
  z-index: 10;
  margin-bottom: 20px;
}

.user-card h2 {
  margin: 0;
  font-size: 20px;
  font-weight: bold;
}

.user-card .role {
  font-size: 13px;
  background: white;
  color: orange;
  padding: 3px 12px;
  border-radius: 10px;
  display: inline-block;
  margin-top: 5px;
}

.main-container {
  display: flex;
  padding: 20px;
  gap: 20px;
  margin-top: 20px;
}

/* Sidebar */
.sidebar {
  width: 250px;
  background-color: #fff;
  border-right: 1px solid #ddd;
  padding: 10px;
}

.user-info {
  text-align: center;
  padding: 10px;
}

.avatar {
  width: 80px;
  height: 80px;
  background-color: orange;
  border-radius: 40px;
  margin: 0 auto 10px;
}

.role {
  font-size: 14px;
  color: white;
  background: orange;
  padding: 2px 10px;
  border-radius: 8px;
}

.joined-info {
  max-height: 120px;
  overflow: hidden;
  padding: 10px;
  border-top: 1px solid #ddd;
}

.joined-info h4 {
  margin-bottom: 5px;
}

/* Content */
.content {
  flex: 1;
  min-width: 0;
}

.tabs {
  display: flex;
  gap: 10px;
  border-bottom: 2px solid #eee;
  padding-bottom: 10px;
}

.tabs button {
  padding: 6px 12px;
  border: none;
  background: none;
  cursor: pointer;
  border-bottom: 2px solid transparent;
}

.tabs .active {
  color: orange;
  border-color: orange;
  font-weight: bold;
}

/* Form */
.form-box {
  margin-top: 20px;
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);

  text-align: left;
}

.form-box h3 {
  margin-bottom: 20px;
  text-align: center;
}

.form-box form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.form-box input {
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.form-actions {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 20px;
}

.edit,
.save {
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
}

.edit {
  background-color: white;
  border: 1px solid orange;
  color: orange;
}

.save {
  background-color: orange;
  color: white;
}

.wishlist-box {
  margin-top: 20px;
}

.search-bar {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.search-bar input {
  flex: 1;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.search-bar button {
  background-color: orange;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
}

.product-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.product-card {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #fff;
  padding: 12px;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
}

.product-card img {
  width: 60px;
  height: 60px;
  object-fit: cover;
}

.product-info h4 {
  margin: 0;
  font-size: 16px;
}

.product-info p {
  margin: 4px 0 0;
  color: orange;
  font-weight: bold;
}

.wishlist-button {
  margin-left: auto;
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: red;
}

.product-actions {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 10px;
}

.add-to-cart {
  background-color: orange;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  white-space: nowrap;
  transition: background-color 0.2s ease;
}

.add-to-cart:hover {
  background-color: #e67300;
}

.pagination {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  gap: 8px;
}

.pagination button {
  padding: 6px 10px;
  border: none;
  background-color: #eee;
  border-radius: 4px;
  cursor: pointer;
  min-width: 32px;
}

.pagination button.active {
  background-color: orange;
  color: white;
  font-weight: bold;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>