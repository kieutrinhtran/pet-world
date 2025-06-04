<template>
  <div class="banner-wrapper">
    <img class="banner" src="@/assets/Banner.png" alt="Product Banner" />
  </div>
  <div class="product-detail-wrapper">
    <!-- Bên trái: Ảnh sản phẩm -->
    <div class="product-image">
      <img src="https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880" alt="Sản phẩm" />
    </div>

    <!-- Bên phải: Thông tin sản phẩm -->
    <div class="product-info">
      <div class="top-row">
        <div class="left-info">
          <h2 class="product-name">Hạt Royal Canin</h2>
          <div class="meta">
            <p class="category">Thức ăn</p>
            <p class="pet-type">Loại thú cưng: <strong>Chó</strong></p>
          </div>
        </div>
        <button class="like-button">♡</button>
      </div>

      <div class="price">
        <span class="current-price">350,000đ</span>
        <span class="original-price">400,000đ</span>
      </div>

      <div class="quantity-section">
        <label>Số lượng</label>
        <div class="quantity-and-actions">
          <div class="quantity-control">
            <button @click="decrease">-</button>
            <span>{{ quantity }}</span>
            <button @click="increase">+</button>
          </div>
          <div class="action-buttons">
            <button class="add-to-cart">Thêm vào giỏ hàng</button>
            <button class="buy-now">Mua ngay</button>
          </div>
        </div>
      </div>


      <div class="shipping-note">
        <span>🚚 <strong>Miễn phí shipping</strong>: Cho đơn trên 500,000đ</span>
      </div>
    </div>
  </div>

  <!-- Mô tả sản phẩm -->
  <div class="product-description-section">
    <h3 class="section-title">Mô tả</h3>
    <div class="description-content">
      <p><strong>Product Name:</strong> Royal Canin Kitten – Dry Food for Kittens</p>
      <p><strong>Brand:</strong> Royal Canin</p>
      <p><strong>Type:</strong> Dry Food</p>
      <p><strong>Target Audience:</strong> Kittens aged 4 – 12 months</p>

      <br />

      <p><strong>Feeding Guidelines:</strong></p>
      <ul class="feeding-list">
        <li>🔹 Feed according to the recommended portions on the packaging.</li>
        <li>🔹 Combine with clean water and nutritional milk if needed.</li>
        <li>🔹 Store in a dry place, away from direct sunlight.</li>
      </ul>
    </div>
  </div>

  <!-- Sản phẩm liên quan -->
  <div class="related-products-section">
    <h3 class="section-title">Sản phẩm liên quan</h3>
    <div class="related-products">
      <div class="related-card" v-for="(product, index) in relatedProducts" :key="index">
        <img :src="product.image" alt="Sản phẩm liên quan" />
        <div class="related-info">
          <h4>{{ product.name }}</h4>
          <p class="category">Thức ăn</p>
          <p class="price">
            <span class="current">{{ product.price }}đ</span>
            <span v-if="product.originalPrice" class="original">{{ product.originalPrice }}đ</span>
          </p>
        </div>
      </div>
    </div>
    <div class="show-more-wrapper">
      <button class="show-more-button">Hiển thị thêm</button>
    </div>
  </div>

</template>

<script setup>
import { ref } from 'vue'

const quantity = ref(1)

const increase = () => {
  quantity.value++
}

const decrease = () => {
  if (quantity.value > 1) quantity.value--
}

const relatedProducts = ref([
  {
    name: 'Royal Canin for Adult Dogs',
    price: '360,000',
    image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
  },
  {
    name: 'Royal Canin for Mother & Baby',
    price: '360,000',
    image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
  },
  {
    name: 'Royal Canin for Persian',
    price: '360,000',
    image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
  },
  {
    name: 'Royal Canin for Hair&Skin',
    price: '400,000',
    image: 'https://down-vn.img.susercontent.com/file/1f4ecf5cbd05ac1ee06a4be19e24f880',
  },
])

</script>

<style scoped>
.banner-wrapper {
  width: 100%;
  display: flex;
  justify-content: center;
  background-color: #f9f9f9;
  padding: 20px 0;
}

.banner {
  width: 100%;
  height: auto;
  display: block;
}

.product-detail-wrapper {
  display: flex;
  gap: 40px;
  padding: 40px;
  background: #fff;
  align-items: flex-start;
  flex-wrap: wrap;
}

.product-image {
  flex: 1;
  max-width: 50%;
  aspect-ratio: 4 / 5;
  width: 360px;
  height: 460px;
  flex-shrink: 0;
  border-radius: 12px;
  overflow: hidden;
}

.product-image img {
  width: 100%;
  height: 100%;
  border-radius: 12px;
  display: block;
  object-fit: cover;
}

.product-info {
  flex: 6;
  display: flex;
  flex-direction: column;
  gap: 16px;
  align-items: flex-start;
  line-height: 1.6;
  flex: 1;
  max-width: 50%;
}

.top-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  width: 100%;
  position: relative;
}

.left-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  align-items: flex-start;
}

.product-name {
  font-size: 24px;
  font-weight: bold;
  margin: 0;
  margin-bottom: 12px;
}

.like-button {
  font-size: 24px;
  background: none;
  border: none;
  cursor: pointer;
  position: absolute;
  right: 0;
  top: 0;
}

.meta {
  color: #555;
  font-size: 14px;
  display: flex;
  flex-direction: column;
  gap: 2px;
  margin: 0;
  padding: 0;
  margin-bottom: 8px;
}

.meta p {
  margin: 0;
  padding: 0;
  text-align: left;
}

.price {
  display: flex;
  align-items: center;
  gap: 16px;
  font-size: 20px;
  margin-bottom: 16px;
}

.current-price {
  font-weight: bold;
  color: #e05a00;
}

.original-price {
  color: #999;
  text-decoration: line-through;
}

.quantity-section label {
  font-weight: bold;
  margin-bottom: 4px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;

}

.quantity-and-actions {
  display: flex;
  align-items: center;
  gap: 24px;
}

.quantity-control {
  display: flex;
  align-items: center;
  gap: 12px;
}

.quantity-control button {
  width: 32px;
  height: 32px;
  font-size: 20px;
  border: 1px solid #ccc;
  background: #fff;
  cursor: pointer;
}

.action-buttons {
  display: flex;
  gap: 12px;
  margin-top: 12px;
}

.add-to-cart,
.buy-now {
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: bold;
  border: 1px solid #e05a00;
  cursor: pointer;
}

.add-to-cart {
  background: #fff;
  color: #e05a00;
}

.buy-now {
  background: #e05a00;
  color: white;
}

.shipping-note {
  margin-top: 20px;
  font-size: 14px;
  color: #444;
  border-top: 2px solid #e05a00;
  padding-top: 6px;
  width: fit-content;
}

.product-description-section,
.related-products-section {
  background: #fff;
  padding: 40px;
  border-top: 1px solid #eee;
}

.section-title {
  color: #e05a00;
  font-size: 22px;
  font-weight: bold;
  margin-bottom: 20px;
  text-align: center;
}

.description-content p {
  margin: 6px 0;
  font-size: 15px;
  color: #333;
  text-align: left;
  max-width: 600px;
}

.feeding-list {
  padding-left: 20px;
  list-style-type: none;
  text-align: left;
  max-width: 600px;
}

.feeding-list li {
  margin: 4px 0;
  font-size: 14px;
  color: #333;
  text-align: left;
  max-width: 600px;
}

.related-products {
  display: flex;
  flex-wrap: wrap;
  gap: 24px;
  justify-content: center;
}

.related-card {
  background: #f8f8f8;
  border-radius: 12px;
  padding: 12px;
  width: 200px;
  text-align: center;
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.related-info {
  width: 100%;
  text-align: left; /* Đảm bảo tất cả nội dung căn trái */
}

.related-info .category {
  font-size: 13px;
  color: #777;
  margin: 0 0 4px 0;
  text-align: left;
}

.related-card:hover {
  transform: translateY(-4px);
}

.related-card img {
  width: 100%;
  height: auto;
  border-radius: 8px;
  margin-bottom: 12px;
}

.related-info h4 {
  font-size: 15px;
  font-weight: bold;
  margin-bottom: 4px;
}

.related-info p {
  font-size: 14px;
  margin: 0;
}

.price {
  margin-top: 6px;
}

.price .current {
  font-weight: bold;
  color: #e05a00;
}

.price .original {
  text-decoration: line-through;
  color: #999;
  margin-left: 6px;
}

.show-more-wrapper {
  text-align: center;
  margin-top: 24px;
}

.show-more-button {
  border: 1px solid #e05a00;
  background: #fff;
  color: #e05a00;
  font-weight: bold;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s ease;
}

.show-more-button:hover {
  background: #e05a00;
  color: #fff;
}
</style>
