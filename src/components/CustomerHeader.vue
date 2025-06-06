<script>
import { useCart } from '../store/cart'

export default {
  name: 'CustomerHeader',
  data() {
    return {
      searchQuery: '',
      isLoggedIn: false,
      userName: '', // Không hard-code tên mặc định ở đây
    };
  },
  computed: {
    menuItems() {
      return [
        { name: 'Trang chủ', path: '/', isActive: this.isHome },
        { name: 'Sản phẩm', path: '/products', isActive: this.isProducts },
        { name: 'Về chúng tôi', path: '/about', isActive: this.isAbout },
      ];
    },
    isHome() {
      return this.$route.path === '/';
    },
    isProducts() {
      return this.$route.path.startsWith('/products');
    },
    isAbout() {
      return this.$route.path === '/about';
    },
    cartItemCount() {
      return this.cart.itemCount;
    },
  },
  methods: {
    handleSearch() {
      if (this.searchQuery.trim()) {
        this.$router.push({
          path: '/products',
          query: { search: this.searchQuery.trim() },
        });
      }
    }
  },
  mounted() {
    const storedName = localStorage.getItem('user_name');
    if (storedName) {
      this.userName = storedName;
      this.isLoggedIn = true;
    }
  },
  setup() {
    const cart = useCart()
    return { cart }
  }
}
</script>
