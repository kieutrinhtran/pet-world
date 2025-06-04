<template>
  <div class="register-container">
    <img src="@/assets/Avatar.png" alt="Logo" class="logo" />
    <h2 class="title">ĐĂNG KÝ</h2>
    <form @submit.prevent="register">
      <label>Tên đăng nhập</label>
      <input v-model="username" type="text" required />

      <label>Mật khẩu</label>
      <input v-model="password" type="password" required />

      <label>Nhập lại mật khẩu</label>
      <input v-model="confirmPassword" type="password" required />

      <button type="submit">Đăng kí</button>
    </form>
    <div class="links">
      <p>Đã có tài khoản? <a href="/login">Đăng nhập ngay</a></p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      confirmPassword: ''
    }
  },
  methods: {
    register() {
      if (this.password !== this.confirmPassword) {
        alert("Mật khẩu không khớp!");
        return;
      }
      // Xử lý đăng ký tại đây
      console.log("Đăng ký:", this.username, this.password);
    }
  }
}
</script>

<style scoped>
.register-container {
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
  color: #f49a00;
  text-decoration: none;
}

.links a:hover {
  text-decoration: underline;
  color: #d88400;
}

</style>
