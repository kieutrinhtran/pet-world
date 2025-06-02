<template>
  <div class="register-container">
    <img src="/images/Avatar.png" alt="Pet World Logo" class="logo" />

    <h1 class="title">ĐĂNG KÝ</h1>

    <div class="register-form">
      <form @submit.prevent="handleRegister">
        <label for="username">Tên đăng nhập</label>
        <input id="username" v-model="username" type="text" class="input-field" />
        <label for="password">Mật khẩu</label>
        <input id="password" v-model="password" type="password" class="input-field" />
        <label for="repassword">Nhập lại mật khẩu</label>
        <input id="repassword" v-model="repassword" type="password" class="input-field" />

        <button type="submit" class="register-button">Đăng kí</button>
      </form>
    </div>

    <div class="footer">
      <p>
        Đã có tài khoản?
        <router-link to="/login" class="link-highlight">Đăng nhập ngay</router-link>
      </p>
    </div>

    <!-- Thông báo lỗi -->
    <div v-if="errorMessage" class="error-message">
      {{ errorMessage }}
    </div>

    <!-- Popup đăng ký thành công -->
    <div v-if="showSuccess" class="popup success-popup">
      <span class="success-icon"><i class="fas fa-check-circle"></i></span>
      <p>ĐĂNG KÝ THÀNH CÔNG</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const username = ref('')
const password = ref('')
const repassword = ref('')
const errorMessage = ref('')
const showSuccess = ref(false)

// Kiểm tra mật khẩu: ít nhất 8 ký tự, 1 chữ hoa, 1 số, 1 ký tự đặc biệt
const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/

function handleRegister() {
  errorMessage.value = ''

  if (!username.value || !password.value || !repassword.value) {
    errorMessage.value = 'Vui lòng nhập đầy đủ thông tin.'
    return
  }

  if (!passwordPattern.test(password.value)) {
    errorMessage.value = 'Mật khẩu phải có ít nhất 8 ký tự, gồm chữ hoa, số và ký tự đặc biệt.'
    return
  }

  if (password.value !== repassword.value) {
    errorMessage.value = 'Không khớp với mật khẩu.'
    return
  }

  // Nếu tất cả điều kiện đều đạt
  showSuccess.value = true

  // Ẩn popup sau 2.5s
  setTimeout(() => {
    showSuccess.value = false
  }, 2500)
}
</script>
