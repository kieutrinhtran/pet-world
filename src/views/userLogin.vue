<template>
  <div class="login-container">
    <img src="/images/Avatar.png" alt="Pet World Logo" class="logo" />

    <h1 class="title">ĐĂNG NHẬP</h1>

    <div class="login-form">
      <form @submit.prevent="handleLogin">
        <label for="username">Tên đăng nhập</label>
        <input id="username" v-model="username" type="text" class="input-field" />

        <label for="password">Mật khẩu</label>
        <input id="password" v-model="password" type="password" class="input-field" />

        <button type="submit" class="login-button">Đăng nhập</button>
      </form>
    </div>

    <div class="footer">
      <p><a href="#">Quên mật khẩu?</a></p>
      <p>
        Chưa có tài khoản?
        <router-link to="/register" class="link-highlight">Đăng ký ngay</router-link>
      </p>
    </div>

    <!-- Thông báo lỗi -->
    <div v-if="errorMessage" class="error-message">
      {{ errorMessage }}
    </div>

    <!-- Popup đăng nhập thành công -->
    <div v-if="showSuccess" class="popup success-popup">
      <span class="success-icon"><i class="fas fa-check-circle"></i></span>
      <p>ĐĂNG NHẬP THÀNH CÔNG</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router' // ✅ Thêm useRouter để điều hướng

const username = ref('')
const password = ref('')
const errorMessage = ref('')
const showSuccess = ref(false)

const router = useRouter() // Khởi tạo router

function handleLogin() {
  errorMessage.value = ''

  if (!username.value || !password.value) {
    errorMessage.value = 'Vui lòng nhập đầy đủ thông tin.'
    return
  }

  showSuccess.value = true

  // Hiển thị popup và chuyển hướng sau 2.5 giây
  setTimeout(() => {
    showSuccess.value = false
    router.push('/customers') // Điều hướng tới trang CustomerPage
  }, 2500)
}
</script>

<style scoped>
.login-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #ffffff; 
  font-family: 'Segoe UI', sans-serif;
  color: #000;
}

.logo {
  width: 200px;
  margin-bottom: 20px;
}

.title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 24px;
}

.login-form {
  width: 320px;
  display: flex;
  flex-direction: column;
}

.input-field {
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
  margin-bottom: 16px;
  background-color: #f9ebd2; 
}

.login-button {
  background-color: #e89206;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.3s;
}

.login-button:hover {
  background-color: #cc7e03;
}

.footer {
  margin-top: 24px;
  text-align: center;
  font-size: 14px;
}

.footer a {
  color: #000;
  text-decoration: none;
}

.link-highlight {
  color: #e89206;
  font-weight: 500;
}

.error-message {
  color: red;
  margin-top: 12px;
  font-size: 14px;
}

.popup.success-popup {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: transparent;
  margin-top: 30px;
  animation: fadeIn 0.3s ease-in-out;
}

.success-icon {
  color: #f57c00;
  font-size: 48px;
  margin-bottom: 8px;
}

.popup.success-popup p {
  color: #f57c00;
  font-weight: bold;
  font-size: 16px;
  margin: 0;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}
</style>
