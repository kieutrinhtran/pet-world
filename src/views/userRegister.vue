<template> 
  <div class="login-container">
    <img src="@/assets/Avatar.png" alt="Logo" class="logo" />
    <h2 class="title">ĐĂNG NHẬP</h2>
    <form @submit.prevent="login">
      <label>Tên đăng nhập</label>
      <input v-model="username" type="text" required />

      <label>Mật khẩu</label>
      <input v-model="password" type="password" required />

      <button type="submit">Đăng nhập</button>
    </form>
    <div class="links">
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
    };
  },
  methods: {
    async login() {
      if (!this.username || !this.password) {
        alert("Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.");
        return;
      }

      try {
        const response = await fetch("/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            user_name: this.username,
            password: this.password
          })
        });

        const data = await response.json();

        if (response.ok && data.user_id) {
          // Đăng nhập thành công
          alert(`Đăng nhập thành công. Xin chào, ${data.user_name}!`);
          // Redirect hoặc lưu thông tin user tại đây nếu cần
        } else if (data.error) {
          alert(data.message || "Thiếu thông tin đăng nhập.");
        } else {
          alert("Tên đăng nhập hoặc mật khẩu không đúng.");
        }
      } catch (error) {
        console.error("Lỗi khi gọi API:", error);
        alert("Có lỗi xảy ra. Vui lòng thử lại sau.");
      }
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
