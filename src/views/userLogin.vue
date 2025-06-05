<template>
  <div class="login-container">
    <img src="/images/Avatar.png" alt="Pet World Logo" class="logo" />

    <h1 class="title">ĐĂNG NHẬP</h1>

    <div class="login-form">
      <form @submit.prevent="handleLogin">
        <label for="username">Tên đăng nhập</label>
        <input
          id="username"
          v-model="username"
          type="text"
          class="input-field"
        />

        <label for="password">Mật khẩu</label>
        <input
          id="password"
          v-model="password"
          type="password"
          class="input-field"
        />

        <button type="submit" class="login-button">Đăng nhập</button>
      </form>
    </div>

    <div class="footer">
      <p><a href="#">Quên mật khẩu?</a></p>
      <p>
        Chưa có tài khoản?
        <router-link to="/register" class="link-highlight"
          >Đăng ký ngay</router-link
        >
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
import { ref } from 'vue';
import { useRouter } from 'vue-router'; // ✅ Thêm useRouter để điều hướng

const username = ref('');
const password = ref('');
const errorMessage = ref('');
const showSuccess = ref(false);

const router = useRouter(); // Khởi tạo router

function handleLogin() {
  errorMessage.value = '';

  if (!username.value || !password.value) {
    errorMessage.value = 'Vui lòng nhập đầy đủ thông tin.';
    return;
  }

  showSuccess.value = true;

  // Hiển thị popup và chuyển hướng sau 2.5 giây
  setTimeout(() => {
    showSuccess.value = false;
    router.push('/customers'); // Điều hướng tới trang CustomerPage
  }, 2500);
}
</script>
