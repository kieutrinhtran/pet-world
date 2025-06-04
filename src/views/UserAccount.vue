<template>
  <div class="account-page">
    <!-- Banner -->
    <div class="banner-wrapper">
      <!-- <img
        class="banner"
        src="@/assets/Avatar-Banner.png"
        alt="Account Banner"
      /> -->

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
          <button :class="{ active: activeTab === 'account' }" @click="activeTab = 'account'">
            Thông tin tài khoản
          </button>
          <button :class="{ active: activeTab === 'wishlist' }" @click="activeTab = 'wishlist'">
            Wish List
          </button>
          <button :class="{ active: activeTab === 'address' }" @click="activeTab = 'address'">
            Sổ địa chỉ
          </button>
          <button :class="{ active: activeTab === 'history' }" @click="activeTab = 'history'">Lịch sử mua hàng</button>
           <button :class="{ active: activeTab === 'password' }" @click="activeTab = 'password'">Đổi mật khẩu</button>
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
            <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">
              ←
            </button>
            <button v-for="page in totalPages" :key="page" @click="goToPage(page)"
              :class="{ active: currentPage === page }">
              {{ page }}
            </button>
            <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">
              →
            </button>
          </div>
        </div>

                <div v-if="activeTab === 'address'" class="address-book">
          <!-- Form chỉnh sửa địa chỉ -->
          <div v-if="editAddressMode" class="address-edit-form">
            <h3>Chỉnh sửa địa chỉ</h3>
            <div class="form-group">
              <label>Họ và tên</label>
              <input type="text" v-model="editingAddress.name">
            </div>
            <div class="form-group">
              <label>Số điện thoại</label>
              <input type="text" v-model="editingAddress.phone">
            </div>
            <div class="form-group">
              <label>Số nhà, tên đường</label>
              <input type="text" v-model="editingAddress.street">
            </div>
            <div class="form-group">
              <label>Phường</label>
              <div class="select-wrapper">
                <select v-model="editingAddress.ward">
                  <option value="WAR1">Phường 6</option>
                  <option value="WAR10">Phường 10</option>
                  <!-- Thêm các phường khác từ API -->
                </select>
                <span class="select-arrow">▼</span>
              </div>
            </div>
            <div class="form-group">
              <label>Quận/Huyện</label>
              <div class="select-wrapper">
                <select v-model="editingAddress.district" disabled>
                  <option value="Quận 3">Quận 3</option>
                  <!-- Populate from API -->
                </select>
                <span class="select-arrow">▼</span>
              </div>
            </div>
            <div class="form-group">
              <label>Thành phố</label>
              <div class="select-wrapper">
                <select v-model="editingAddress.city" disabled>
                  <option value="TP. Hồ Chí Minh">Thành phố Hồ Chí Minh</option>
                  <!-- Populate from API -->
                </select>
                <span class="select-arrow">▼</span>
              </div>
            </div>
            <div class="default-checkbox">
              <label class="toggle-switch">
                <input type="checkbox" v-model="editingAddress.default">
                <span class="toggle-slider"></span>
              </label>
              <span>Đặt làm địa chỉ mặc định</span>
            </div>
            <div class="form-actions">
              <button class="save-btn" @click="saveAddress">Lưu</button>
            </div>
          </div>
        
          <!-- Loading indicator -->
          <div v-else-if="isLoading" class="loading-indicator">
            <p>Đang tải địa chỉ...</p>
          </div>
          
          <!-- No addresses message -->
          <div v-else-if="addresses.length === 0" class="no-addresses">
            <p>Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ mới.</p>
            <button class="add-address-btn" @click="addNewAddress">Thêm địa chỉ mới</button>
          </div>
        
          <!-- Address list -->
          <div v-else>
            <div v-for="(address, idx) in addresses" :key="address.address_id" class="address-card">
              <div class="address-content">
                <div class="address-header">
                  <div class="name-phone-group">
                    <span class="address-name">{{ address.name }}</span>
                    <span class="address-phone">{{ address.phone }}</span>
                  </div>
                </div>
                <div class="address-detail">{{ address.detail }}</div>
                <div class="address-label">
                  <span class="address-default" :class="{ orange: address.default, gray: !address.default }">
                    Mặc định
                  </span>
                </div>
              </div>
              <div class="address-actions">
                <button class="action-btn chinhsua" @click="editAddress(idx)">Chỉnh sửa</button>
                <button class="action-btn xoadiachi" @click="deleteAddress(idx)">Xóa địa chỉ</button>
              </div>
            </div>
        
            <button class="add-address-btn" @click="addNewAddress">Thêm địa chỉ mới</button>
          </div>
        </div>

        <div v-if="activeTab === 'history'" class="order-history">
          <div v-if="selectedOrder" class="order-detail-form">
            <h3>Chi tiết đơn hàng</h3>

            <div class="order-items">
              <div class="order-item-detail" v-for="(item, idx) in selectedOrder.items" :key="idx">
                <img :src="item.image" alt="Ảnh sản phẩm" class="product-image" />

                <div class="order-info">
                  <div class="order-category">{{ item.category }}</div>
                  <div class="order-name">{{ item.name }}</div>
                  <div class="order-quantity">{{ item.quantity }}g × {{ item.count }}</div>
                  <div class="order-price">{{ item.price }}</div>
                </div>

                <div class="item-actions">
                  <button class="wishlist-btn">
                    <i class="far fa-heart"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="order-summary">
              <div class="summary-row">
                <span>Tình trạng đơn hàng:</span>
                <span class="status-value completed">{{ selectedOrder.status }}</span>
              </div>
              <div class="summary-row total-row">
                <span>Tổng tiền:</span>
                <span class="total-price">{{ selectedOrder.total }}</span>
              </div>
            </div>

            <div class="form-actions">
              <button class="back-btn" @click="selectedOrder = null">Quay lại</button>
            </div>
          </div>

          <div v-else>
            <div class="search-bar">
              <input type="text" v-model="orderSearchQuery" placeholder="Tên sản phẩm, tên shop..." />
              <button><i class="fas fa-search"></i></button>
            </div>

            <div class="order-list">
              <!-- Order item -->
              <div class="order-item" v-for="(order, index) in filteredOrders" :key="index">
                <div class="order-product">
                  <img :src="order.image" alt="Ảnh sản phẩm" class="product-image" />

                  <div class="order-info">
                    <div class="order-category">{{ order.category }}</div>
                    <div class="order-name">{{ order.name }}</div>
                    <div class="order-quantity">{{ order.quantity }}g × {{ order.count }}</div>
                    <div class="order-price">{{ order.price }}</div>
                  </div>
                </div>

                <div class="order-status">
                  <div class="status-label">
                    <i class="fas fa-truck-moving"></i> Tình trạng đơn hàng:
                  </div>
                  <span class="status-value completed">{{ order.status }}</span>
                </div>

                <div class="order-total">
                  <div>Thành tiền:</div>
                  <div class="total-price">{{ order.total }}</div>
                </div>

                <div class="order-actions">
                  <button class="view-more-btn" @click="viewOrderDetail(order)">Xem thêm</button>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div class="pagination">
              <button class="prev-btn" @click="prevOrderPage" :disabled="orderCurrentPage === 1">
                <i class="fas fa-chevron-left"></i>
              </button>
              <div class="page-info">Trang {{ orderCurrentPage }}/{{ orderTotalPages }}</div>
              <button class="next-btn" @click="nextOrderPage" :disabled="orderCurrentPage === orderTotalPages">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>

        <div v-if="activeTab === 'password'" class="password-change-form">
  <h3>Đổi mật khẩu</h3>
  
  <div class="form-group">
    <label>Tên đăng nhập</label>
    <input type="text" value="Nguyen Van A" disabled />
  </div>
  
  <div class="form-group">
    <label>Mật khẩu hiện tại</label>
    <input type="password" v-model="passwordData.current" />
  </div>
  
  <div class="form-group">
    <label>Mật khẩu mới</label>
    <input type="password" v-model="passwordData.new" />
  </div>
  
  <div class="form-group password-actions">
    <button class="cancel-btn" @click="resetPasswordForm">Hủy</button>
    <button class="save-btn" @click="changePassword">Lưu</button>
  </div>
</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { customerService } from '@/services/api';
import { onMounted } from 'vue';
import { ref, computed } from 'vue';

const activeTab = ref('account');
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 2;
// const addresses = ref([
//   {
//     name: 'Nguyễn Văn A',
//     phone: '(+84) 347 895 555',
//     detail: '123 Nguyễn Thị Minh Khai, Phường 6, Quận 3, TP. HCM',
//     default: true,
//   },
//   {
//     name: 'Nguyễn Văn A',
//     phone: '(+84) 347 895 555',
//     detail: '123 Nguyễn Thị Minh Khai, Phường 6, Quận 3, TP. HCM',
//     default: false,
//   },
//   {
//     name: 'Nguyễn Văn A',
//     phone: '(+84) 347 895 555',
//     detail: '123 Nguyễn Thị Minh Khai, Phường 6, Quận 3, TP. HCM',
//     default: false,
//   },
// ]);

const products = ref([
  {
    name: 'Hạt Royal Canin',
    price: '350,000',
    image:
      'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
  },
  {
    name: 'Thức ăn Pedigree',
    price: '350,000',
    image:
      'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
  },
  {
    name: 'Hạt Whiskas',
    price: '350,000',
    image:
      'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
  },
  {
    name: 'Hạt CatPy',
    price: '350,000',
    image:
      'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
  },
]);
const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredProducts.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() => {
  return Math.ceil(filteredProducts.value.length / itemsPerPage);
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};
const filteredProducts = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return products.value.filter((p) => p.name.toLowerCase().includes(query));
});
// Lấy danh sách địa chỉ từ API
const addresses = ref([]);
const isLoading = ref(false);

// Fetch địa chỉ từ API khi component mounted
onMounted(async () => {
  await fetchAddresses();
});

const fetchAddresses = async () => {
  try {
    isLoading.value = true;
    const response = await customerService.getAddress();
    
    if (response && response.success && response.addresses) {
      addresses.value = response.addresses.map(addr => ({
        address_id: addr.address_id,
        name: addr.customer_name,
        phone: addr.phone,
        detail: addr.address_line, // Địa chỉ chi tiết
        ward_id: addr.ward_id, // Sẽ cần một hàm để chuyển ward_id thành tên Phường/Quận/TP
        default: addr.is_default === 1,
        created_at: addr.created_at
      }));
    }
  } catch (error) {
    console.error("Lỗi khi tải địa chỉ:", error);
  } finally {
    isLoading.value = false;
  }
};

// Sửa lại hàm save address để gửi dữ liệu lên API
const saveAddress = async () => {
  try {
    // Tạo dữ liệu để gửi lên API
    const addressData = {
      address_id: editingAddressIndex.value >= 0 ? addresses.value[editingAddressIndex.value].address_id : "",
      customer_id: "123", // Cần lấy customer_id từ session hoặc localStorage
      address_line: editingAddress.value.street,
      ward_id: editingAddress.value.ward,
      is_default: editingAddress.value.default ? 1 : 0,
      created_at: new Date().toISOString().slice(0, 19).replace('T', ' ') // Format: YYYY-MM-DD HH:MM:SS
    };

    // Gọi API để cập nhật hoặc thêm mới địa chỉ
    const response = await customerService.saveAddress(addressData);
    
    if (response && response.success) {
      // Nếu thành công, làm mới danh sách địa chỉ
      await fetchAddresses();
      
      // Reset form và trở về danh sách
      editAddressMode.value = false;
      editingAddressIndex.value = -1;
      
      // Thông báo thành công
      alert("Địa chỉ đã được lưu thành công!");
    } else {
      alert("Có lỗi xảy ra khi lưu địa chỉ. Vui lòng thử lại.");
    }
  } catch (error) {
    console.error("Lỗi khi lưu địa chỉ:", error);
    alert("Có lỗi xảy ra khi lưu địa chỉ. Vui lòng thử lại.");
  }
};

const getCustomerId = () => {
  return localStorage.getItem('customerId') || "123"; // Giá trị mặc định tạm thời
};

// Thêm mới địa chỉ
const addNewAddress = () => {
  editingAddress.value = {
    address_id: "",
    name: '',
    phone: '',
    street: '',
    ward: 'WAR1', // Giá trị mặc định
    district: 'Quận 3', // Giá trị mặc định
    city: 'TP. Hồ Chí Minh', // Giá trị mặc định
    default: false
  };
  editingAddressIndex.value = -1;
  editAddressMode.value = true;
};

// Sửa lại hàm xóa địa chỉ để gọi API
const deleteAddress = async (index) => {
  if (confirm('Bạn có chắc muốn xóa địa chỉ này?')) {
    try {
      const addressId = addresses.value[index].address_id;
      // Cần thêm API endpoint để xóa địa chỉ
      // await customerService.deleteAddress(addressId);
      
      // Làm mới danh sách địa chỉ
      await fetchAddresses();
    } catch (error) {
      console.error("Lỗi khi xóa địa chỉ:", error);
      alert("Có lỗi xảy ra khi xóa địa chỉ. Vui lòng thử lại.");
    }
  }
};

const orderSearchQuery = ref('');
const orderCurrentPage = ref(1);
const ordersPerPage = 4;
const selectedOrder = ref(null);

const orders = ref([
  {
    id: 1,
    category: 'THỨC ĂN CHO CHÓ',
    name: 'Hạt Royal Canin',
    quantity: '300',
    count: '2',
    price: '350,000',
    total: '700,000đ',
    status: 'Hoàn thành',
    image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
    items: [
      {
        category: 'THỨC ĂN CHO CHÓ',
        name: 'Hạt Royal Canin',
        quantity: '300',
        count: '2',
        price: '350,000',
        image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
      }
    ]
  },
  {
    id: 2,
    category: 'THỨC ĂN CHO CHÓ',
    name: 'Thức ăn Pedigree',
    quantity: '300',
    count: '2',
    price: '350,000',
    total: '700,000đ',
    status: 'Hoàn thành',
    image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
    items: [
      {
        category: 'THỨC ĂN CHO CHÓ',
        name: 'Thức ăn Pedigree',
        quantity: '300',
        count: '2',
        price: '350,000',
        image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
      }
    ]
  },
  {
    id: 3,
    category: 'THỨC ĂN CHO MÈO',
    name: 'Hạt Whiskas',
    quantity: '300',
    count: '2',
    price: '350,000',
    total: '700,000đ',
    status: 'Hoàn thành',
    image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
    items: [
      {
        category: 'THỨC ĂN CHO MÈO',
        name: 'Hạt Whiskas',
        quantity: '300',
        count: '2',
        price: '350,000',
        image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
      }
    ]
  },
  {
    id: 4,
    category: 'THỨC ĂN CHO CHÓ',
    name: 'Hạt CatPy',
    quantity: '300',
    count: '2',
    price: '350,000',
    total: '700,000đ',
    status: 'Hoàn thành',
    image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
    items: [
      {
        category: 'THỨC ĂN CHO CHÓ',
        name: 'Hạt CatPy',
        quantity: '300',
        count: '2',
        price: '350,000',
        image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
      }
    ]
  }
]);

// Tính toán đơn hàng được lọc dựa trên tìm kiếm
const filteredOrders = computed(() => {
  const query = orderSearchQuery.value.toLowerCase();
  return orders.value.filter(order =>
    order.name.toLowerCase().includes(query) ||
    order.category.toLowerCase().includes(query)
  );
});



// Tính tổng số trang
const orderTotalPages = computed(() => {
  return Math.ceil(filteredOrders.value.length / ordersPerPage);
});

// Các hàm điều hướng phân trang
const nextOrderPage = () => {
  if (orderCurrentPage.value < orderTotalPages.value) {
    orderCurrentPage.value++;
  }
};

const prevOrderPage = () => {
  if (orderCurrentPage.value > 1) {
    orderCurrentPage.value--;
  }
};

// Hiển thị chi tiết đơn hàng
const viewOrderDetail = (order) => {
  selectedOrder.value = order;
};

const passwordData = ref({
  current: '',
  new: ''
});

const resetPasswordForm = () => {
  passwordData.value.current = '';
  passwordData.value.new = '';
};

const changePassword = () => {
  if (!passwordData.value.current || !passwordData.value.new) {
    alert('Vui lòng nhập đầy đủ thông tin');
    return;
  }
  
  alert('Mật khẩu đã được cập nhật thành công!');
  resetPasswordForm();
};
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
  background: var(--Primary, #ff7c00);
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

.address-book {
  margin-top: 20px;
  max-width: 100%;
}

.address-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-bottom: 15px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.address-content {
  flex: 1;
}

.address-header {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 5px;
}

.name-phone-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.address-name {
  font-size: 20px;
  font-weight: bold;
  color: #ff7c00;
}

.address-phone {
  font-size: 14px;
  color: #666;
}

.address-detail {
  font-size: 14px;
  color: #333;
  margin-bottom: 10px;
  line-height: 1.4;
}

.address-label {
  margin-top: 5px;
}

.address-default {
  display: inline-block;
  font-size: 13px;
  padding: 2px 12px;
  border-radius: 20px;
}

.address-default.orange {
  background: #ff7c00;
  color: #fff;
}

.address-default.gray {
  background: #bdbdbd;
  color: #fff;
}

.address-actions {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.action-btn {
  border: 1px solid #ff7c00;
  background: #fff;
  color: #ff7c00;
  border-radius: 20px;
  padding: 5px 15px;
  cursor: pointer;
  font-size: 13px;
  white-space: nowrap;
}

.action-btn:hover {
  background: #ff7c00;
  color: #fff;
}

.add-address-btn {
  margin-top: 10px;
  border: 1px solid #ff7c00;
  background: #fff;
  color: #ff7c00;
  border-radius: 20px;
  padding: 7px 20px;
  cursor: pointer;
  font-size: 15px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}

.add-address-btn:hover {
  background: #ff7c00;
  color: #fff;
}


.address-edit-form {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.address-edit-form h3 {
  color: #ff7c00;
  font-size: 24px;
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  color: #333;
  font-size: 14px;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 15px;
}

.select-wrapper {
  position: relative;
}

.select-arrow {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 10px;
  color: #999;
  pointer-events: none;
}

.default-checkbox {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 20px;
  margin-bottom: 25px;
}

/* Toggle switch style */
.toggle-switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 34px;
}

.toggle-slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked+.toggle-slider {
  background-color: #ff7c00;
}

input:checked+.toggle-slider:before {
  transform: translateX(26px);
}

.save-btn {
  background: #fff;
  border: 1px solid #ff7c00;
  color: #ff7c00;
  padding: 8px 40px;
  border-radius: 20px;
  font-size: 16px;
  cursor: pointer;
  display: block;
  margin: 0 auto;
  transition: all 0.3s;
}

.save-btn:hover {
  background: #ff7c00;
  color: #fff;
}

.order-history {
  margin-top: 20px;
}

.order-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-top: 20px;
}

.order-item {
  background: #fff;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.order-product {
  display: flex;
  gap: 15px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.product-image {
  width: 70px;
  height: 70px;
  object-fit: contain;
}

.order-info {
  flex: 1;
}

.order-category {
  font-size: 12px;
  color: #666;
  margin-bottom: 4px;
}

.order-name {
  font-size: 16px;
  font-weight: bold;
  color: #ff7c00;
  margin-bottom: 4px;
}

.order-quantity {
  font-size: 13px;
  color: #333;
  margin-bottom: 4px;
}

.order-price {
  font-size: 14px;
  font-weight: bold;
}

.order-status {
  display: flex;
  align-items: center;
  gap: 5px;
  margin: 12px 0;
  font-size: 14px;
}

.status-label {
  color: #333;
  display: flex;
  align-items: center;
  gap: 6px;
}

.status-value {
  font-weight: 500;
}

.status-value.completed {
  color: #2eb82e;
}

.order-total {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
  font-size: 14px;
}

.total-price {
  font-size: 16px;
  font-weight: bold;
  color: #ff7c00;
}

.order-actions {
  display: flex;
  justify-content: flex-end;
}

.view-more-btn {
  background: white;
  border: 1px solid #ccc;
  color: #333;
  border-radius: 20px;
  padding: 6px 15px;
  font-size: 13px;
  cursor: pointer;
}

.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 25px;
  gap: 15px;
}

.prev-btn,
.next-btn {
  border: 1px solid #ccc;
  background: white;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.page-info {
  font-size: 14px;
  color: #333;
}

.order-detail-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 100;
}

.modal-content {
  background: white;
  width: 80%;
  max-width: 800px;
  border-radius: 10px;
  padding: 20px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.modal-header h3 {
  color: #ff7c00;
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #666;
}

.order-items {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.order-item-detail {
  display: flex;
  gap: 15px;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 8px;
  align-items: center;
}

.item-actions {
  display: flex;
  align-items: center;
}

.wishlist-btn {
  background: none;
  border: none;
  font-size: 18px;
  color: #ccc;
  cursor: pointer;
  transition: color 0.2s;
}

.wishlist-btn:hover {
  color: #ff7c00;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  font-size: 14px;
}

.total-row {
  font-weight: bold;
  border-top: 1px solid #eee;
  margin-top: 10px;
  padding-top: 15px;
}

.order-summary {
  margin-top: 20px;
  padding: 15px;
  background: #f9f9f9;
  border-radius: 8px;
}

/* Thêm tính năng tổng tiền ở dưới cùng */
.orders-total {
  margin-top: 20px;
  padding: 15px;
  background: #fff;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.orders-total .label {
  font-size: 16px;
  font-weight: bold;
}

.orders-total .amount {
  font-size: 18px;
  font-weight: bold;
  color: #ff7c00;
}

.order-detail-form {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.order-detail-form h3 {
  color: #ff7c00;
  font-size: 24px;
  text-align: center;
  margin-bottom: 20px;
}

.back-btn {
  background: #fff;
  border: 1px solid #ff7c00;
  color: #ff7c00;
  padding: 8px 40px;
  border-radius: 20px;
  font-size: 16px;
  cursor: pointer;
  display: block;
  margin: 20px auto 0;
  transition: all 0.3s;
}

.back-btn:hover {
  background: #ff7c00;
  color: #fff;
}

.order-detail-modal {
  display: none;
}


.password-change-form {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  margin-top: 20px;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

.password-change-form h3 {
  color: #ff7c00;
  font-size: 24px;
  text-align: center;
  margin-bottom: 30px;
}

.password-change-form .form-group {
  margin-bottom: 20px;
}

.password-change-form label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #333;
}

.password-change-form input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 15px;
}

.password-change-form input:disabled {
  background-color: #f9f9f9;
  color: #666;
}

.password-actions {
  display: flex;
  justify-content: center;
  margin-top: 30px;
  gap: 20px;
}

.cancel-btn {
  background: #fff;
  border: 1px solid #ccc;
  color: #666;
  padding: 8px 40px;
  border-radius: 20px;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.2s;
}

.cancel-btn:hover {
  background: #f5f5f5;
}

</style>
