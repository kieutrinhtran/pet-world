<template>
  <section>
    <h2 class="text-base font-bold">Mua sắm theo giống thú cưng</h2>
    <div class="flex items-center justify-center mt-2 space-x-6">
      <button class="w-full overflow-hidden rounded quick-category" @click="goToPet('Cho')">img
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

<style scoped></style>
