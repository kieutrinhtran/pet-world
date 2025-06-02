<template>
  <div class="product-list">
    <!-- Banner -->
    <img class="banner" src="@/assets/Banner.png" alt="Product Banner" />

    <!-- Main layout -->
    <div class="main-content">
      <!-- Left: Filter -->
      <div class="filter-section">
        <div class="filter-title">
          <div class="filter-title-group">

            <!-- Inline SVG icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" class="icon">
              <g clip-path="url(#clip0_2200_5624)">
                <path d="M18.3332 2.5H1.6665L8.33317 10.3833V15.8333L11.6665 17.5V10.3833L18.3332 2.5Z" stroke="#FF813F"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </g>
              <defs>
                <clipPath id="clip0_2200_5624">
                  <rect width="20" height="20" fill="white" />
                </clipPath>
              </defs>
            </svg>

            <span>Filter</span>
          </div>
        </div>

        <div class="filter-group">
          <h3 class="filter-heading">Danh mục sản phẩm</h3>
          <ul class="filter-list">
            <li><input type="checkbox" id="food" value="Thức ăn" v-model="selectedCategories"><label for="food">Thức
                ăn</label></li>
            <li><input type="checkbox" id="toy" value="Đồ chơi" v-model="selectedCategories"><label for="toy">Đồ
                chơi</label></li>
            <li><input type="checkbox" id="care" value="Phụ kiện chăm sóc" v-model="selectedCategories"><label
                for="care">Phụ kiện chăm sóc</label></li>
            <li><input type="checkbox" id="cleaning" value="Vật dụng vệ sinh" v-model="selectedCategories"><label
                for="cleaning">Vật dụng vệ sinh</label></li>
            <li><input type="checkbox" id="bed" value="Chuồng và giường" v-model="selectedCategories"><label
                for="bed">Chuồng và giường</label></li>
            <li><input type="checkbox" id="snack" value="Snack/Bánh thưởng" v-model="selectedCategories"><label
                for="snack">Snack/Bánh thưởng</label></li>
            <li><input type="checkbox" id="supplement" value="Thực phẩm bổ sung" v-model="selectedCategories"><label
                for="supplement">Thực phẩm bổ sung</label></li>
            <li><input type="checkbox" id="fashion" value="Quần áo/Thời trang" v-model="selectedCategories"><label
                for="fashion">Quần áo/Thời trang</label></li>
          </ul>
        </div>

        <hr class="filter-divider" />

        <div class="filter-group">
          <h3 class="filter-heading">Loại thú cưng</h3>
          <ul class="filter-list">
            <li><input type="checkbox" id="dog" value="Chó" v-model="selectedPets"><label for="dog">Chó</label></li>
            <li><input type="checkbox" id="cat" value="Mèo" v-model="selectedPets"><label for="cat">Mèo</label></li>
          </ul>
        </div>

        <!-- Bộ lọc mức giá -->
        <div class="price-filter">
          <div class="slider-container">
            <!-- Min range -->
            <input type="range" :min="min" :max="max" v-model.number="minPrice" @input="checkMin" />

            <!-- Max range -->
            <input type="range" :min="min" :max="max" v-model.number="maxPrice" @input="checkMax" />

            <!-- Track fill (optional) -->
            <div class="slider-track" :style="trackStyle"></div>
          </div>
          <div class="price-values">
            Giá từ {{ minPrice }}₫ đến {{ maxPrice }}₫
          </div>
        </div>


      </div>

      <!-- Right: Product Cards -->
      <div class="product-section">
        <!-- Bọc bằng div mới -->
        <div class="sort-wrapper">
          <div class="sort-dropdown">
            <button @click="toggleDropdown" class="sort-button">
              Sắp xếp
              <span class="arrow">▼</span>
            </button>
            <div v-if="dropdownOpen" class="dropdown-menu">
              <div class="dropdown-item" v-for="(option, index) in sortOptions" :key="index"
                @click="selectOption(option.value)">
                {{ option.label }}
                <span v-if="sortOption === option.value" style="float: right;">✔</span>
              </div>
            </div>
          </div>
        </div>

        <div class="product-grid">
          <div v-for="product in paginatedProducts" :key="product.id" class="product-card"
            :class="{ active: activeProductId === product.id }" @click.stop="setActiveProduct(product.id)">
            <img :src="product.image" :alt="product.name" class="product-img" />

            <div class="overlay" v-if="activeProductId === product.id">
              <button class="btn-cart" @click.stop>Giỏ hàng</button>
              <button class="btn-buy" @click.stop>Mua ngay</button>
              <button class="btn-fav" @click.stop>Yêu thích</button>
            </div>

            <div class="product-info">
              <h3>{{ product.name }}</h3>
              <p>{{ product.category }}</p>
              <p>{{ formatPrice(product.price) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="pagination">
      <button @click="currentPage = Math.max(currentPage - 1, 1)" :disabled="currentPage === 1">«</button>
      <span>Trang {{ currentPage }} / {{ totalPages }}</span>
      <button @click="currentPage = Math.min(currentPage + 1, totalPages)"
        :disabled="currentPage === totalPages">»</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductList',
  data() {
    return {
      selectedProductIndex: null,
      min: 0,
      max: 500000,
      minPrice: 200,
      maxPrice: 800,
      activeProductId: null,
      selectedCategories: [],
      selectedPets: [],
      currentPage: 1,
      itemsPerPage: 6,
      sortOption: 'priceAsc',
      dropdownOpen: false,
      sortOptions: [
        { label: 'Giá tăng dần', value: 'priceAsc' },
        { label: 'Giá giảm dần', value: 'priceDesc' },
        { label: 'Từ A đến Z', value: 'nameAsc' },
        { label: 'Từ Z đến A', value: 'nameDesc' }
      ],
      products: [
        {
          id: 1,
          name: 'Nhà mèo leo',
          category: 'Đồ chơi',
          price: 360000,
          petType: 'Mèo',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        },
        {
          id: 2,
          name: 'Nhà mèo vuông',
          category: 'Đồ chơi',
          price: 360000,
          petType: 'Mèo',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        },
        {
          id: 3,
          name: 'Nhà mèo tròn',
          category: 'Đồ chơi',
          price: 350000,
          petType: 'Mèo',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        },
        {
          id: 4,
          name: 'Hạt Royal Canin',
          category: 'Thức ăn',
          price: 350000,
          petType: 'Chó',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        },
        {
          id: 5,
          name: 'Hạt Whiskas',
          category: 'Thức ăn',
          price: 350000,
          petType: 'Mèo',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        },
        {
          id: 6,
          name: 'Thức ăn Pedigree',
          category: 'Thức ăn',
          price: 350000,
          petType: 'Chó',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        },
        {
          id: 7,
          name: 'Máy cắt tỉa lông',
          category: 'Vật dụng vệ sinh',
          price: 150000,
          petType: 'Chó',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        },
        {
          id: 8,
          name: 'Royal Canin for Puppy Mini',
          category: 'Thức ăn',
          price: 420000,
          petType: 'Chó',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        },
        {
          id: 9,
          name: 'Hạt CatPy',
          category: 'Thức ăn',
          price: 200000,
          petType: 'Mèo',
          image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880'
        }
      ]
    }
  },


  mounted() {
    document.addEventListener('click', this.handleClickOutside);
    document.addEventListener('click', this.clearSelection);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
    document.removeEventListener('click', this.clearSelection);
  },


  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN').format(value);
    },

    setActiveProduct(id) {
      this.activeProductId = id;
    },
    handleClickOutside(event) {
      // Nếu click ngoài card
      if (!event.target.closest('.product-card')) {
        this.activeProductId = null;
      }
    },
    selectProduct(index, event) {
      event.stopPropagation();
      this.selectedProductIndex = index;
    },
    clearSelection() {
      this.selectedProductIndex = null;
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    selectOption(value) {
      this.sortOption = value;
      this.dropdownOpen = false;
      this.$emit('sort-changed', value);
    },
    formatPrice(value) {
      return new Intl.NumberFormat('vi-VN').format(value);
    },
    checkMin() {
      if (this.minPrice > this.maxPrice) {
        this.minPrice = this.maxPrice;
      }
    },
    checkMax() {
      if (this.maxPrice < this.minPrice) {
        this.maxPrice = this.minPrice;
      }
    }
  },

  computed: {
    filteredProducts() {
      return this.products.filter((product) => {
        const categoryMatch =
          this.selectedCategories.length === 0 || this.selectedCategories.includes(product.category);
        const petMatch =
          this.selectedPets.length === 0 || this.selectedPets.includes(product.petType);
        const priceMatch =
          product.price >= this.minPrice && product.price <= this.maxPrice;
        return categoryMatch && petMatch && priceMatch;
      });
    },
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;

      let sortedProducts = [...this.filteredProducts];

      switch (this.sortOption) {
        case 'priceAsc':
          sortedProducts.sort((a, b) => a.price - b.price);
          break;
        case 'priceDesc':
          sortedProducts.sort((a, b) => b.price - a.price);
          break;
        case 'nameAsc':
          sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
          break;
        case 'nameDesc':
          sortedProducts.sort((a, b) => b.name.localeCompare(a.name));
          break;
      }

      return sortedProducts.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
    },
    trackStyle() {
      const minPercent = ((this.minPrice - this.min) / (this.max - this.min)) * 100;
      const maxPercent = ((this.maxPrice - this.min) / (this.max - this.min)) * 100;
      return {
        left: `${minPercent}%`,
        width: `${maxPercent - minPercent}%`
      };
    }
  },



  watch: {
    selectedCategories() {
      this.currentPage = 1;
    },
    selectedPets() {
      this.currentPage = 1;
    },
    filteredProducts() {
      if (this.currentPage > this.totalPages) {
        this.currentPage = this.totalPages || 1;
      }
    },
    sortOption() {
      this.$emit('sort-changed', this.sortOption);
    }
  }
}
</script>


<style scoped>
.product-list {
  text-align: center;
}

.banner {
  width: 100%;
  height: auto;
  display: block;
}

/* Layout chính: 2 bên */
.main-content {
  display: flex;
  gap: 24px;
  padding: 32px;
  text-align: left;
}

/* Bên trái: bộ lọc */
.filter-section {
  width: 316px;
  height: 656px;
  flex-shrink: 0;
  padding: 16px;
  border-radius: 8px;
  overflow-y: auto;
}

.filter-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.filter-group .filter-list li {
  margin-bottom: 12px;
}

.filter-title {
  display: flex;
  justify-content: center;
  margin-bottom: 32px;
}

.filter-title-group {
  display: flex;
  align-items: center;
  gap: 8px;
}

.filter-title-group span {
  color: #F87537;
  font-family: 'Poppins', sans-serif;
  font-size: 28px;
  font-weight: 600;
  line-height: 20px;
  user-select: none;
}

.icon {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  vertical-align: middle;
  display: inline-block;
}

.filter-heading {
  margin-bottom: 16px;
  color: #000;
  -webkit-text-stroke-width: 1px;
  -webkit-text-stroke-color: #000;
  font-family: 'Poppins', sans-serif;
  font-size: 15px;
  font-weight: 600;
  line-height: 20px;
  text-align: left;
}

.filter-divider {
  width: 300px;
  height: 1px;
  border: none;
  border-top: 2px solid #F87537;
  flex-shrink: 0;
  margin: 24px auto;
}

/* Bên phải: danh sách sản phẩm */
.product-section {
  flex: 1;
}

/* Grid sản phẩm */
.product-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 49px;
}

/* Thẻ sản phẩm */
.product-card {
  position: relative;
  width: 285px;
  height: 445px;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.product-card.active {
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
}

.product-card .overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.product-card.active .overlay {
  opacity: 1;
  pointer-events: auto;
}

.btn-cart,
.btn-buy,
.btn-fav {
  margin: 8px;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  background-color: #FF813F;
  color: white;
  cursor: pointer;
}

.product-card:hover {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.product-img {
  width: 100%;
  height: 285px;
  object-fit: cover;
  border-radius: 10px;
}

.product-name {
  margin: 12px 0 4px;
  color: #FF813F;
  font-size: 16px;
  font-weight: 600;
}

.product-category {
  margin: 0;
  color: #6C6C6C;
  font-size: 14px;
}

.product-price {
  margin-top: 6px;
  color: #FF813F;
  font-weight: bold;
  font-size: 16px;
}

.pagination {
  margin-top: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 12px;
  font-family: 'Poppins', sans-serif;
}

.pagination button {
  padding: 6px 12px;
  border: none;
  border-radius: 10px;
  background-color: #FF813F;
  color: white;
  cursor: pointer;
  font-weight: bold;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.product-sort {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 16px;
  font-family: 'Poppins', sans-serif;
}

.product-sort select {
  padding: 6px 12px;
  border-radius: 10px;
  border: 1px solid #ccc;
}

.sort-dropdown {
  position: relative;
  display: inline-block;
  margin-bottom: 16px;
  left: -50px;
  font-family: 'Poppins', sans-serif;
}

.sort-button {
  background-color: white;
  border: 1px solid #FF813F;
  border-radius: 10px;
  padding: 8px 16px;
  font-size: 14px;
  color: #FF813F;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Mũi tên ▼ */
.sort-button .arrow {
  font-size: 12px;
  transition: transform 0.3s ease;
}

/* Dropdown menu */
.dropdown-menu {
  position: absolute;
  top: 110%;
  left: -10px;
  z-index: 10;
  background-color: #fff;
  border: 1px solid #FF813F;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  width: 180px;
  padding: 8px 0;
}

/* Mỗi lựa chọn */
.dropdown-item {
  padding: 10px 16px;
  font-size: 14px;
  color: #333;
  cursor: pointer;
  font-family: 'Poppins', sans-serif;
  transition: background-color 0.2s;
}

/* Hover item */
.dropdown-item:hover {
  background-color: #FFF0E6;
  /* Màu cam nhạt */
  color: #FF813F;
}

.sort-wrapper {
  display: flex;
  justify-content: flex-end;
  /* đẩy sang phải */
  margin-bottom: 16px;
}

.slider-container {
  position: relative;
  height: 36px;
}

input[type="range"] {
  -webkit-appearance: none;
  width: 100%;
  position: absolute;
  height: 4px;
  background: transparent;
  pointer-events: none;
}

input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 16px;
  width: 16px;
  border-radius: 50%;
  background: #3b82f6;
  cursor: pointer;
  pointer-events: auto;
  position: relative;
  z-index: 3;
}

.slider-track {
  position: absolute;
  height: 4px;
  background-color: #3b82f6;
  top: 50%;
  transform: translateY(-50%);
  z-index: 1;
  border-radius: 2px;
}
</style>
