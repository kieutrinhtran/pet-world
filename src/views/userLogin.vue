<template>
  <div class="login-container">
    <img src="@/assets/logo.png" alt="Logo" class="logo" />
    <h2 class="title">ĐĂNG NHẬP</h2>
    <form @submit.prevent="login">
      <label>Tên đăng nhập</label>
      <input v-model="username" type="text" required />
      
      <label>Mật khẩu</label>
      <input v-model="password" type="password" required />

      <button type="submit">Đăng nhập</button>
    </form>
    <div class="links">
      <p>Quên mật khẩu?</p>
      <p>Chưa có tài khoản? <a href="/register">Đăng ký ngay</a></p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: ''
    }
  },
  methods: {
    login() {
      // Xử lý đăng nhập ở đây
      console.log("Đăng nhập với:", this.username, this.password);
    }
  }
}
</script>

<style scoped>
.login-container {
  background-color: white;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.logo {
  width: 180px;
  margin-bottom: 2rem;
}

.title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 1.5rem;
}

form {
  width: 100%;
  max-width: 320px;
  display: flex;
  flex-direction: column;
}

label {
  margin-top: 1rem;
  margin-bottom: 0.25rem;
  font-weight: 500;
}

input {
  padding: 10px;
  border: 1px solid #ccc;
  background-color: #fef1d8;
  border-radius: 5px;
  font-size: 14px;
}

button {
  margin-top: 1.5rem;
  padding: 10px;
  background-color: #f49a00;
  color: white;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

button:hover {
  background-color: #d88400;
}

.links {
  margin-top: 1rem;
  text-align: center;
  font-size: 14px;
}

.links a {
  font-weight: bold;
  color: black;
  text-decoration: none;
}

.links a:hover {
  text-decoration: underline;
}
</style>
