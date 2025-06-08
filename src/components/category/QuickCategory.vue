<template>
  <section>
    <h2 class="text-base font-bold">Mua sắm theo giống thú cưng</h2>
    <div class="flex items-center justify-center mt-2 space-x-6">
      <!-- Dùng đúng tên hàm: goToCategory -->
      <button class="w-full overflow-hidden rounded quick-category" @click="goToCategory('Cho')">
        <img
          class="w-full h-full"
          src="@/assets/images/dog_banner.png"
          alt="Dog"
        />
      </button>
      <button class="w-full overflow-hidden rounded quick-category" @click="goToCategory('Meo')">
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

// Hàm điều hướng tới trang sản phẩm kèm filter theo giống
const goToCategory = (animalType) => {
  router.push({
    path: '/products',
    query: {
      category: animalType
    }
  })
}
</script>

<style scoped></style>
