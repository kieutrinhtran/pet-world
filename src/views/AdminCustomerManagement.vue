<template>
  <div class="customer-page">
    <!-- Tìm kiếm + bộ lọc -->
    <div class="search-section">
      <span
        >Hiển thị {{ filteredCustomers.length }} từ {{ customers.length }} kết quả tìm kiếm</span
      >
      <AdminSearchBar v-model="searchQuery" placeholder="Tìm kiếm khách hàng theo tên, email hoặc ID..." @input="() => {}" class="search-input" />
    </div>

    <!-- Bảng danh sách khách hàng -->
    <table class="customer-table">
      <thead>
        <tr>
          <th>ID khách hàng</th>
          <th>Họ và tên</th>
          <th>Email</th>
          <th>Ngày sinh</th>
          <th>Số điện thoại</th>
          <th>Giới tính</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(customer, index) in filteredCustomers" :key="index">
          <td>{{ customer.id }}</td>
          <td>{{ customer.name }}</td>
          <td>{{ customer.email }}</td>
          <td>{{ customer.dob }}</td>
          <td>{{ customer.phone }}</td>
          <td>{{ customer.gender }}</td>
          <td class="action-buttons">
            <button @click="viewHistory(customer.id)">👁️</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Footer component -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import Footer from '@/components/FooterComponent.vue'
import AdminSearchBar from '@/components/AdminSearchBar.vue'

const router = useRouter()

// Danh sách khách hàng
const customers = ref([
  {
    id: 20462,
    name: 'Matt Dickerson',
    email: 'hat@example.com',
    dob: '13/05/2022',
    phone: '701112233',
    gender: 'Nam'
  },
  {
    id: 18935,
    name: 'Wiktoria',
    email: 'laptop@example.com',
    dob: '22/05/2022',
    phone: '701112233',
    gender: 'Nam'
  },
  {
    id: 54519,
    name: 'Trixie Byrd',
    email: 'phone@example.com',
    dob: '15/06/2022',
    phone: '701112233',
    gender: 'Nam'
  }
])

// Tìm kiếm
const searchQuery = ref('')

// Lọc kết quả theo tìm kiếm
const filteredCustomers = computed(() => {
  if (!searchQuery.value) return customers.value
  return customers.value.filter(
    c =>
      c.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      c.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      c.id.toString().includes(searchQuery.value)
  )
})

// Điều hướng lịch sử
const viewHistory = id => {
  router.push(`/history/${id}`)
}
</script>ss

<style scoped>
.customer-page {
  padding: 20px;
  font-family: 'Arial', sans-serif;
}

.banner {
  background-color: #fff3e0;
  padding: 30px;
  border-radius: 12px;
  margin-bottom: 20px;
}

.banner h1 {
  font-size: 24px;
  color: #222;
}

.search-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.search-input {
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  width: 250px;
}

.customer-table {
  width: 100%;
  border-collapse: collapse;
}

.customer-table th,
.customer-table td {
  border: 1px solid #eee;
  padding: 10px;
  text-align: center;
}

.customer-table th {
  background-color: #f8f8f8;
}

.action-buttons button {
  margin: 0 5px;
  padding: 4px 6px;
  font-size: 16px;
  cursor: pointer;
  background: none;
  border: none;
}
</style>
