<template>
  <section>
    <h2 class="text-base font-bold">Mua sắm theo giống thú cưng</h2>
    <div class="flex items-center justify-center mt-2 space-x-6">
      <button class="w-full overflow-hidden rounded quick-category" @click="goToPet('Cho')">
        <img
          class="w-full h-full"
          src="@/assets/images/dog_banner.png"
          alt="Dog"
        />
      </button>
      <button class="w-full overflow-hidden rounded quick-category" @click="goToPet('Meo')">
        <img
          class="w-full h-full"
          src="@/assets/images/cat_banner.png"
          alt="Cat"
        />
      </button>
    </div>
  </section>
</template>

<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()

function goToPet(petType) {
  router.push({ path: '/products', query: { pet: petType } })
}
</script>

<style scoped>
.quick-category {
  transition: transform 0.3s ease, box-shadow 0.3s ease, border 0.3s ease;
  border-radius: 12px;
  overflow: hidden;
  display: block;
  border: 2px solid transparent; /* đảm bảo không bị lệch layout */
}

/* Ảnh bên trong */
.quick-category img {
  transition: transform 0.3s ease;
  border-radius: 12px;
  display: block;
}

/* Hover toàn khối */
.quick-category:hover {
  transform: scale(1.02);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
  border: 2px solid #ff9000; /* viền cam nổi bật */
}

/* Ảnh bên trong phóng nhẹ */
.quick-category:hover img {
  transform: scale(1.05);
}
</style>
