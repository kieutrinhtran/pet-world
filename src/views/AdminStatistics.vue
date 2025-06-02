<template>
  <div class="admin-statistics">
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
    </div>
    <div v-else-if="error" class="error">
      {{ error }}
    </div>
    <div v-else class="stats-section">
      <h2>Thống kê tình hình kinh doanh</h2>
      <div class="stats-cards">
        <div class="stat-card">
          <div class="stat-icon customers"><i class="fas fa-users"></i></div>
          <div class="stat-info">
            <div class="stat-title">Tổng số khách hàng</div>
            <div class="stat-value">{{ totalCustomers.toLocaleString() }}</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon orders"><i class="fas fa-shopping-basket"></i></div>
          <div class="stat-info">
            <div class="stat-title">Tổng đơn hàng</div>
            <div class="stat-value">{{ totalOrders.toLocaleString() }}</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon revenue"><i class="fas fa-coins"></i></div>
          <div class="stat-info">
            <div class="stat-title">Tổng doanh thu</div>
            <div class="stat-value">{{ totalRevenue.toLocaleString() }} VND</div>
          </div>
        </div>
      </div>
      <div class="charts-row">
        <div class="chart-card">
          <h3>Thống kê doanh thu theo tháng</h3>
          <div class="flex justify-center items-center">
            <canvas id="revenueChart" width="350" height="260"></canvas>
          </div>
        </div>
        <div class="chart-card">
          <h3>Thống kê danh mục sản phẩm bán chạy</h3>
          <div class="flex justify-center items-center">
            <canvas id="categoryChart" width="260" height="260"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import Chart from 'chart.js/auto'
import { orderService, customerService } from '@/services/api'

const totalCustomers = ref(0)
const totalOrders = ref(0)
const totalRevenue = ref(0)
const monthlyRevenue = ref({ labels: [], data: [] })
const bestCategories = ref({ labels: [], data: [] })
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    loading.value = true
    // Lấy tổng số khách hàng
    const customersResponse = await customerService.getCount()
    totalCustomers.value = customersResponse.total_customers

    // Lấy thống kê đơn hàng
    const ordersResponse = await orderService.getStatistics()
    totalOrders.value = ordersResponse.total_orders
    totalRevenue.value = ordersResponse.total_revenue

    // Lấy doanh thu theo tháng
    const monthlyResponse = await orderService.getMonthlyRevenue()
    monthlyRevenue.value = {
      labels: monthlyResponse.labels,
      data: monthlyResponse.data
    }

    // Lấy danh mục bán chạy
    const categoriesResponse = await orderService.getBestCategories()
    bestCategories.value = {
      labels: categoriesResponse.labels,
      data: categoriesResponse.data
    }

    // Vẽ biểu đồ doanh thu theo tháng
    const ctx1 = document.getElementById('revenueChart').getContext('2d')
    new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: monthlyRevenue.value.labels,
        datasets: [
          {
            label: 'Doanh thu (VND)',
            data: monthlyRevenue.value.data,
            backgroundColor: '#ff9800',
            borderRadius: 8
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => `${ctx.parsed.y.toLocaleString()} VND` } }
        },
        scales: {
          y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } }
        }
      }
    })

    // Vẽ biểu đồ danh mục bán chạy
    const ctx2 = document.getElementById('categoryChart').getContext('2d')
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: bestCategories.value.labels,
        datasets: [
          {
            data: bestCategories.value.data,
            backgroundColor: ['#ff9800', '#ffd54f', '#4fc3f7', '#81c784', '#f06292']
          }
        ]
      },
      options: {
        plugins: {
          legend: { position: 'right' }
        }
      }
    })
  } catch (err) {
    error.value = err.message
    console.error('Chi tiết lỗi:', err)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.admin-statistics {
  min-height: 100vh;
  background: #fafbfc;
  padding-bottom: 40px;
}
.hero {
  background: linear-gradient(90deg, #fff 60%, #ff9800 100%);
  padding: 0 0 32px 0;
}
.hero-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1200px;
  margin: 0 auto;
  padding: 32px 0 0 0;
  gap: 32px;
}
.brand {
  color: #ff9800;
  font-weight: 700;
  font-size: 1.2rem;
  letter-spacing: 1px;
}
.hero-content h1 {
  font-size: 2.2rem;
  color: #222;
  margin: 8px 0 0 0;
  font-weight: 800;
}
.slogan {
  color: #666;
  margin-top: 8px;
  font-size: 1.1rem;
}
.hero-img img {
  width: 260px;
  height: 180px;
  object-fit: cover;
  border-radius: 2rem 0 2rem 0;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
}
.stats-section {
  max-width: 1200px;
  margin: 0 auto;
  padding: 32px 16px 0 16px;
}
.stats-section h2 {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 24px;
  color: #222;
  text-align: left;
}
.stats-cards {
  display: flex;
  gap: 32px;
  margin-bottom: 32px;
  flex-wrap: wrap;
}
.stat-card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
  display: flex;
  align-items: center;
  gap: 18px;
  padding: 24px 32px;
  min-width: 280px;
  flex: 1 1 280px;
}
.stat-icon {
  font-size: 2.2rem;
  width: 56px;
  height: 56px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff3e0;
  color: #ff9800;
  box-shadow: 0 2px 8px rgba(255, 152, 0, 0.08);
}
.stat-icon.orders {
  background: #e3f2fd;
  color: #2196f3;
}
.stat-icon.revenue {
  background: #e8f5e9;
  color: #43a047;
}
.stat-info {
  flex: 1;
}
.stat-title {
  color: #888;
  font-size: 1rem;
  margin-bottom: 0.5rem;
}
.stat-value {
  font-size: 2.1rem;
  font-weight: bold;
  color: #222;
}
.stat-change {
  margin-top: 0.5rem;
  font-size: 1rem;
}
.stat-change.positive {
  color: #43a047;
}
.charts-row {
  display: flex;
  gap: 32px;
  flex-wrap: wrap;
  margin-top: 8px;
  justify-content: center;
}
.chart-card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
  padding: 24px 32px;
  min-width: 350px;
  max-width: 420px;
  flex: 1 1 350px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.chart-card h3 {
  margin-bottom: 1rem;
  font-size: 1.1rem;
  color: #222;
  font-weight: 600;
  text-align: center;
}
canvas {
  display: block;
  max-width: 100%;
  height: auto !important;
}
@media (max-width: 900px) {
  .hero-content {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }
  .hero-img img {
    width: 100%;
    max-width: 320px;
    height: auto;
  }
  .charts-row {
    flex-direction: column;
    gap: 24px;
  }
  .stats-cards {
    flex-direction: column;
    gap: 18px;
  }
}

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 400px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #ff9800;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error {
  color: #dc2626;
  text-align: center;
  padding: 24px;
  font-size: 1.1rem;
}
</style>
