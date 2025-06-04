<template>
  <div class="banner-wrapper">
    <img class="banner" src="@/assets/Banner.png" alt="Product Banner" />
  </div>
  <div class="product-detail-wrapper">
    <!-- Bên trái: Ảnh sản phẩm -->
    <div class="product-image">
      <img src="@/assets/imageProduct.png" alt="Royal Canin Kitten" />
    </div>

    <!-- Bên phải: Thông tin sản phẩm -->
    <div class="product-info">
      <div class="top-row">
        <div class="left-info">
          <h2 class="product-name">Hạt Royal Canin</h2>
          <p class="category">THỨC ĂN</p>
          <p class="pet-type">LOẠI THÚ CƯNG: CHÓ</p>
          <div class="price-container">
            <span class="current-price">350,000</span>
            <span class="price-unit">VND</span>
            <span class="original-price">400,000</span>
          </div>
        </div>
        <button class="like-button">
          <i class="fas fa-heart"></i>
        </button>
      </div>

      <div class="quantity-section">
        <p class="quantity-label">Số lượng</p>
        <div class="quantity-control">
          <button @click="decrease">−</button>
          <input type="text" v-model="quantity" min="1" readonly />
          <button @click="increase">+</button>
        </div>
      </div>

      <div class="action-buttons">
        <button class="add-to-cart">Thêm vào giỏ hàng</button>
        <button class="buy-now">Mua ngay</button>
      </div>

      <div class="shipping-note">
        <i class="fas fa-truck"></i>
        <span>Miễn phí shipping: Cho đơn trên 500,000đ</span>
      </div>
    </div>
  </div>

  <!-- Mô tả sản phẩm -->
  <div class="product-description-section">
    <h3 class="section-title">Mô tả</h3>
    <div class="description-content">
      <p>Product name: Royal Canin Kitten - Dry Food for Kittens</p>
      <p>Brand: Royal Canin</p>
      <p>Type: Dry Food</p>
      <p>Target Audience: Kittens aged 4 - 12 months</p>

      <h4>Feeding Guidelines:</h4>
      <ul class="feeding-list">
        <li>Feed according to the recommended portions on the packaging</li>
        <li>Combine with clean water and nutritional milk if needed</li>
        <li>Store in a dry place, away from direct sunlight</li>
      </ul>
    </div>
  </div>
  <div class="related-products-section">
    <h3 class="section-title">Sản phẩm liên quan</h3>
    <div class="related-products-grid">
      <div class="related-product-card" v-for="product in relatedProducts" :key="product.id">
        <div class="product-image">
          <img :src="product.image" :alt="product.name">
        </div>
        <div class="product-info">
          <h4 class="product-name">{{ product.name }}</h4>
          <p class="product-category">{{ product.category }}</p>
          <p class="product-price">{{ product.price.toLocaleString() }}</p>
        </div>
      </div>
    </div>
    <button class="show-more-btn">Hiện thị thêm</button>
  </div>
</template>

<style scoped>
.banner-wrapper {
  width: 100%;
  height: 300px;
  overflow: hidden;
}

.banner {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-detail-wrapper {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
}

.product-image {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-image img {
  width: 100%;
  height: auto;
  display: block;
}

.product-info {
  position: relative;
}

.top-row {
  position: relative;
  padding-right: 40px;
}

.left-info {
  margin-right: 40px;
}

.product-name {
  font-size: 20px;
  color: #FF5722;
  margin-bottom: 16px;
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
}

.category,
.pet-type {
  color: #666;
  font-size: 14px;
  margin: 8px 0;
  text-transform: uppercase;
}

.price-container {
  margin: 24px 0;
  display: flex;
  align-items: baseline;
  gap: 8px;
}

.current-price {
  font-size: 32px;
  color: #333;
  font-weight: 600;
}

.price-unit {
  color: #333;
  font-size: 16px;
}

.original-price {
  color: #999;
  text-decoration: line-through;
  font-size: 16px;
}

.quantity-label {
  color: #333;
  margin-bottom: 8px;
  font-size: 14px;
}

.quantity-control {
  display: inline-flex;
  align-items: center;
  border: 1px solid #FF5722;
  border-radius: 4px;
  overflow: hidden;
}

.quantity-control button {
  width: 36px;
  height: 36px;
  border: none;
  background: white;
  color: #FF5722;
  font-size: 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.quantity-control input {
  width: 50px;
  height: 36px;
  border: none;
  border-left: 1px solid #FF5722;
  border-right: 1px solid #FF5722;
  text-align: center;
  font-size: 16px;
}

.action-buttons {
  display: flex;
  gap: 16px;
  margin: 24px 0;
}

.add-to-cart,
.buy-now {
  flex: 1;
  padding: 12px 24px;
  border-radius: 4px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  border: 1px solid #F87537;
}

.add-to-cart {
  background: white;
  color: #F87537;
}

.buy-now {
  background: #F87537;
  color: white;
}

.shipping-note {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #F87537;
  font-size: 14px;
}

.shipping-note i {
  color: #F87537;
}

.like-button {
  position: absolute;
  top: 0;
  right: 0;
  width: 40px;
  height: 40px;
  border: none;
  background: none;
  font-size: 24px;
  color: #ddd;
  cursor: pointer;
  transition: all 0.3s ease;
}

.like-button.active {
  color: #F87537;
}

.like-button:hover {
  transform: scale(1.1);
}

/* Related Products Section */
.related-products-section {
  max-width: 1200px;
  margin: 40px auto;
  padding: 24px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.related-products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
  margin: 24px 0;
}

.related-product-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.3s ease;
  cursor: pointer;
}

.related-product-card:hover {
  transform: translateY(-4px);
}

.section-title {
  color: #F87537;
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 24px;
  font-family: 'Poppins', sans-serif;
  position: relative;
  padding-bottom: 12px;
}

.product-description-section {
  max-width: 1200px;
  margin: 40px auto;
  padding: 32px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-description-section .section-title {
  color: #333;
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 24px;
  font-family: 'Poppins', sans-serif;
  text-transform: uppercase;
}

.description-content {
  color: #666;
  line-height: 1.8;
}

.description-content p {
  margin: 12px 0;
  font-size: 15px;
}

.description-content h4 {
  color: #333;
  font-size: 18px;
  font-weight: 600;
  margin: 24px 0 16px;
}

.feeding-list {
  list-style: none;
  padding: 0;
  margin: 16px 0;
}

.feeding-list li {
  padding-left: 24px;
  position: relative;
  margin: 12px 0;
  font-size: 15px;
  color: #666;
}

.feeding-list li::before {
  content: '•';
  position: absolute;
  left: 8px;
  color: #FF5722;
  font-size: 16px;
}

/* Responsive for description section */
@media (max-width: 768px) {
  .product-description-section {
    padding: 24px;
    margin: 24px 20px;
  }

  .description-content p,
  .feeding-list li {
    font-size: 14px;
  }
}

.show-more-btn {
  display: block;
  margin: 0 auto;
  padding: 12px 24px;
  background: white;
  border: 1px solid #F87537;
  color: #F87537;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.show-more-btn:hover {
  background: #FFF3E0;
}

/* Responsive Design */
@media (max-width: 992px) {
  .product-detail-wrapper {
    grid-template-columns: 1fr;
  }

  .related-products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .related-products-section .section-title {
    font-size: 18px;
    margin-bottom: 16px;
  }
}

@media (max-width: 576px) {
  .action-buttons {
    flex-direction: column;
  }

  .related-products-grid {
    grid-template-columns: 1fr;
  }
}
</style>
<script>
export default {
  data() {
    return {
      quantity: 1,
      relatedProducts: [
        {
          id: 1,
          name: 'Royal Canin for Adult Dogs',
          category: 'Sản phẩm',
          price: 360000,
          image: require('@/assets/product1.png')
        },
        {
          id: 2,
          name: 'Royal Canin for Mother & Baby',
          category: 'Sản phẩm',
          price: 360000,
          image: require('@/assets/product2.png')
        },
        {
          id: 3,
          name: 'Royal Canin for Persian',
          category: 'Sản phẩm',
          price: 360000,
          image: require('@/assets/product3.png')
        },
        {
          id: 4,
          name: 'Royal Canin for Hair&Skin',
          category: 'Sản phẩm',
          price: 400000,
          image: require('@/assets/product4.png')
        }
      ]
    }
  },
  methods: {
    decrease() {
      if (this.quantity > 1) {
        this.quantity--
      }
    },
    increase() {
      this.quantity++
    }
  }
}
</script>
