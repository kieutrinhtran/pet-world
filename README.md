# Pet World - Hệ Thống Quản Lý Cửa Hàng Thú Cưng

## Giới Thiệu
Pet World là một ứng dụng web quản lý cửa hàng thú cưng, được xây dựng với Vue.js cho frontend và PHP cho backend. Ứng dụng cung cấp các tính năng quản lý sản phẩm, đơn hàng, khách hàng và thống kê doanh thu.

## Tính Năng Chính

### 1. Tính Năng Dành Cho Admin

#### 1.1. Quản Lý Sản Phẩm
- Xem danh sách sản phẩm
- Thêm sản phẩm mới
- Cập nhật thông tin sản phẩm
- Inactive sản phẩm

#### 1.2. Quản Lý Đơn Hàng
- Xem danh sách đơn hàng
- Xem chi tiết đơn hàng
- Cập nhật trạng thái đơn hàng

#### 1.3. Quản Lý Khách Hàng
- Xem danh sách khách hàng

#### 1.4. Thống Kê & Báo Cáo
- Tổng số đơn hàng
- Tổng số khách hàng
- Tổng số sản phẩm
- Thống kê doanh thu theo tháng

### 2. Tính Năng Dành Cho Người Dùng

#### 2.1. Quản Lý Tài Khoản
- Đăng ký tài khoản
- Đăng nhập/Đăng xuất/Đổi mật khẩu
- Xem thông tin cá nhân
- Cập nhật thông tin cá nhân
- Xem danh sách sản phẩm yêu thích

#### 2.2. Mua Sắm
- Xem danh sách sản phẩm
- Xem chi tiết sản phẩm
- Lọc sản phẩm theo danh mục

#### 2.3. Giỏ Hàng
- Thêm sản phẩm vào giỏ hàng
- Cập nhật số lượng sản phẩm
- Xóa sản phẩm khỏi giỏ hàng
- Xem tổng giá trị giỏ hàng

#### 2.4. Đặt Hàng
- Xem phí vận chuyển
- Đặt hàng

#### 2.5. Theo Dõi Đơn Hàng
- Xem lịch sử mua hàng
- Xem chi tiết đơn hàng
- Theo dõi trạng thái đơn hàng


## Công Nghệ Sử Dụng

### Frontend
- Vue.js 3
- Tailwind CSS

### Backend
- PHP
- MySQL

## Cài Đặt

### Cài Đặt Frontend
```bash
# Cài đặt dependencies
npm install
npm install -g @vue/cli

# Chạy development server
npm run serve

# Build cho production
npm run build
```

### Cài Đặt Backend
```bash
npm install js-cookie

#
php -S localhost:8000

# Cấu hình database trong file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pet_world
DB_USERNAME=root
DB_PASSWORD=

# Import database
mysql -u root -p pet_world < scriptmysql_database/pet_world.sql
```

## Cấu Trúc Dự Án

### Frontend (`/src`)
- `/api`: Cấu hình API endpoints
- `/assets`: Hình ảnh và tài nguyên tĩnh
- `/components`: Các component Vue.js
- `/layouts`: Layout templates
- `/router`: Cấu hình routing
- `/store`: Vuex store modules
- `/utils`: Các utility functions
- `/views`: Các trang chính của ứng dụng

### Backend (`/backend`)
- `/config`: Cấu hình database và ứng dụng
- `/controllers`: Xử lý logic nghiệp vụ
- `/middleware`: Middleware xác thực và phân quyền
- `/model`: Database models
- `/routes`: Định nghĩa API routes
- `/services`: Business logic services


## Hướng dẫn khởi tạo dự án Vue và kết nối GitHub

1. Mở terminal và chuyển đến thư mục bạn muốn lưu project:

   ```bash
   cd "C:\Users\Trinh\Documents\0. VueJS"
   ```
2. Clone code về:

   ```bash
   git clone https://github.com/kieutrinhtran/pet-shop.git
   ```
3. Lệnh mở VS Code
   ```bash
   code .
   ```
4. Khởi tạo Git:

   ```bash
   git init
   ```
5. Thêm remote origin (chỉ cần làm 1 lần):

   ```bash
   git remote add origin https://github.com/kieutrinhtran/pet-shop.git
   ```
6. Tạo branch trinh trên remote repository (nếu chưa tồn tại, đẩy tất cả commit từ branch local lên branch remote) (thay `trinh` bằng tên của bạn, sau này chỉ push code lên branch của mình):

   ```bash
   git push -u origin trinh
   ```
---

## 🔗 Kết nối với GitHub

### Clone repo (nếu đã tạo sẵn trên GitHub)

```bash
git clone https://github.com/kieutrinhtran/pet-shop
cd pet-shop
git add .
git commit -m "initial commit"
```


## 🌿 Một số lệnh để quản lý nhánh và đẩy code lên GitHub trong terminal

1. Xem các nhánh hiện có:

   ```bash
   git branch
   ```
2. Tạo và chuyển sang nhánh mới (thay `trinh` bằng tên của bạn, sau này chỉ push code lên branch của mình):

   ```bash
   git checkout -b trinh
   ```
3. Kiểm tra nhánh hiện tại:

   ```bash
   git branch
   ```
4. Thêm thay đổi và commit:

   ```bash
   git add .
   git commit -m "Add code to trinh branch"
   ```
5. Push nhánh mới lên GitHub:

   ```bash
   git push -u origin trinh
   ```
6. Fetch về từ nhánh main
   ```bash
   git fetch origin main
   ```
7. Merge các thay đổi từ main vào branch hiện tại của bạn
   ```bash
   git merge origin/main
   ```
8. Merge các thay đổi từ branch hiện tại (trinh) vào main
   ```bash
   git merge trinh
   ```
---