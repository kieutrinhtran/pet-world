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

<style scoped>
/* Nền trắng, canh giữa nội dung */
.login-container {
  background-color: #ffffff;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
  font-family: 'Roboto', sans-serif;
}

/* Logo Pet World */
.logo {
  max-width: 250px;
  margin-bottom: 20px;
}

/* Tiêu đề */
.title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 25px;
  color: #000;
}

/* Khung form đăng nhập/đăng ký */
.login-form {
  width: 100%;
  max-width: 400px;
}

/* Nhãn và input */
label {
  display: block;
  font-size: 14px;
  margin: 12px 0 5px;
  color: #000;
}

.input-field {
  width: 100%;
  padding: 10px 14px;
  border-radius: 4px;
  border: 1px solid #ccc;
  background-color: #fcecd0;
  font-size: 14px;
}

/* Nút đăng nhập/đăng ký */
.login-button {
  margin-top: 20px;
  width: 100%;
  padding: 12px;
  background-color: #eb8c00;
  color: #fff;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s;
}

.login-button:hover {
  background-color: #cf7a00;
}

/* Footer liên kết */
.footer {
  margin-top: 20px;
  font-size: 14px;
  color: #000;
  text-align: center;
}

.footer a {
  color: #000;
  text-decoration: underline;
}

.link-highlight {
  color: #eb8c00;
  font-weight: bold;
  text-decoration: none;
}

/* Thông báo lỗi */
.error-message {
  color: red;
  margin-top: 10px;
  font-size: 14px;
}

/* Popup thành công */
.popup.success-popup {
  margin-top: 25px;
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #f57c00;
  font-weight: bold;
  font-size: 18px;
}

.success-icon {
  font-size: 40px;
  margin-bottom: 10px;
}
</style>

