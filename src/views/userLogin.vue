<template>
  <div class="login-container">
    <img src="@/assets/Avatar.png" alt="Logo" class="logo" />
    <h2 class="title">ĐĂNG NHẬP</h2>
    <form @submit.prevent="login">
      <label>Tên đăng nhập</label>
      <input v-model="username" type="text" required />

      <label>Mật khẩu</label>
      <input v-model="password" type="password" required />

      <label>Loại đăng nhập:</label>
      <select v-model="loginType">
        <option value="user">Người dùng</option>
        <option value="admin">Quản trị viên</option>
      </select>

      <button type="submit">Đăng nhập</button>
    </form>

    <div class="links">
      <p>
    Chưa có tài khoản?
    <a @click.prevent="goToRegister" href="#">Đăng ký ngay</a>
      </p>
    </div>


    <!-- Popup đăng nhập thành công -->
    <div v-if="showSuccess" class="modal">
      <div class="modal-content">
        <img src="@/assets/Popup.png" alt="Thành công" class="icon" />
        <p>ĐĂNG NHẬP THÀNH CÔNG</p>
      </div>
    </div>

    <!-- Thông báo lỗi -->
    <div v-if="errorMessage" class="error-message">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      loginType: 'user',
      errorMessage: '',
      showSuccess: false
    };
  },
  methods: {
  async login() {
    const url = this.loginType === 'admin'
    ? 'http://localhost:8000/login'
    : 'http://localhost:8000/user/login';

    const body = this.loginType === 'admin'
    ? { user_name: this.username, password: this.password }
    : { username: this.username, password: this.password };

    try {
    const response = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });

    const data = await response.json();

    if (data.error === true && data.message) {
      this.errorMessage = data.message;
      setTimeout(() => this.errorMessage = '', 5000);
      return;
    }

    if (data === false) {
      this.errorMessage = 'Sai tên đăng nhập hoặc mật khẩu';
      setTimeout(() => this.errorMessage = '', 5000);
      return;
    }

    localStorage.setItem('user_name', data.user_name || this.username);
    localStorage.setItem('role', data.role || 'user');

    this.showSuccess = true;
    setTimeout(() => {
      this.showSuccess = false;
      if (this.loginType === 'admin') {
        this.$router.push({ name: 'AdminCustomerManagement' });
      } else {
        this.$router.push({ name: 'HomePage' });
      }
    }, 1500);
  } catch (error) {
    console.error('Lỗi kết nối API:', error);
    this.errorMessage = 'Không thể kết nối tới máy chủ';
    setTimeout(() => this.errorMessage = '', 5000);
  }
}

    goToRegister() {
      this.$router.push('/register');
    }
  }
};
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
input,
select {
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
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}
.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  text-align: center;
  color: #f49a00;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}
.modal-content .icon {
  display: block;
  margin: 0 auto 1rem auto;
  width: 50px;
}
.error-message {
  color: red;
  margin: 1rem 0;
  font-weight: bold;
  text-align: center;
}
</style>
