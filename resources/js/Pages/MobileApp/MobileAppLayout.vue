<!-- eslint-disable prettier/prettier -->
<template>
    <v-app>
      <!-- Fixed Toolbar -->
      <v-app-bar color="brown" dark flat app>
        <v-toolbar-title>飲み物</v-toolbar-title>
        <v-toolbar-title>{{ checkInParam.room.room_name }}</v-toolbar-title>
  
        <v-spacer></v-spacer>
  
        <v-btn icon>
          <v-icon>mdi-magnify</v-icon>
        </v-btn>
  
        <v-btn icon @click="orderCartClick">
          <v-badge v-if="orderCartCount > 0" :content="Number(orderCartCount)" color="red" overlap>
            <v-icon>mdi-cart</v-icon>
          </v-badge>
          <v-icon v-else>mdi-cart</v-icon>
        </v-btn>
  
        <v-btn icon @click="showHistoryModal = true">
          <v-icon>mdi-history</v-icon>
        </v-btn>
  
        <v-menu offset-y>
          <template #activator="{ props }">
            <v-btn icon v-bind="props">
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>
  
          <v-list>
            <v-list-item
              v-for="item in settingItems"
              :key="item.title"
              @click="handleSettingAction(item.action)"
            >
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-app-bar>
  
      <!-- Page Content -->
      <v-main>
        <v-container fluid class="pt-4">
          <v-alert v-if="emptyOrderAlert" type="warning" class="mt-4">
            注文カートは空です！
          </v-alert>
  
          <!-- Modals -->
          <OrderHistoryModal
            :showHistoryModal="showHistoryModal"
            :checkIn="checkInParam"
            @close="showHistoryModal = false"
          />
  
          <OrderCartModal
            :showCartModal="showCartModal"
            :checkIn="checkInParam"
            @close="showCartModal = false"
            @refresh="getOrderCart"
          />
  
          <CourseMenuModal
            :showCourseMenuModal="showCourseMenuModal"
            :checkIn="checkInParam"
            @close="showCourseMenuModal = false"
          />
  
          <slot />

        </v-container>
      </v-main>

     <!-- Floating Action Button -->
        <v-btn
        color="brown"
        dark
        fab
        fixed
        elevation="8"
        class="ma-4"
        style="
          position: fixed;
          bottom: 12px;
          right: 24px;
         z-index: 1500; /* LOWER than dialog */
          width: 64px;
          height: 64px;
          border-radius: 50%;
          min-width: 0;
        "
        @click="onFabClick"
        >
        <v-icon size="32">mdi-cart-arrow-right</v-icon>
        </v-btn>

        <!-- Fixed Footer (must come after FAB) -->
        <v-footer app padless>
        <v-col class="text-center" cols="12">
        {{ new Date().getFullYear() }} — <strong>Afroci Co.Ltd</strong>
        </v-col>
        </v-footer>
    </v-app>
</template> 

<script>
import OrderHistoryModal from '@/Pages/MobileApp/OrderHistoryModal.vue';
import OrderCartModal from '@/Pages/MobileApp/OrderCartModal.vue';
import CourseMenuModal from '@/Pages/MobileApp/CourseMenuModal.vue';
import eventBus from '@/Event/eventBus';

export default {
    components: {
        OrderHistoryModal,
        OrderCartModal,
        CourseMenuModal,
    },
    props: {
        checkInParam: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            showHistoryModal: false,
            showCartModal: false,
            showCourseMenuModal: false,
            orderCartData: [],
            //orderCartCount: 0,
            emptyOrderAlert: false,
            settingItems: [
                { title: 'コースメニュー', action: 'goToCourseMenu' },
                { title: '再読み込み', action: 'reloadPage' },
                { title: 'ヘルプ', action: 'openHelp' },
                // { title: 'ログアウト', action: 'logout' },
            ],
        };
    },

    computed: {
        orderCartCount() {
            if (Array.isArray(this.orderCartData)) {
                return this.orderCartData.reduce(
                    (total, item) => total + (item.quantity || 0),
                    0,
                );
            }
            return 0;
        },
    },
    mounted() {
        this.getOrderCart();
        eventBus.on('cart-updated', this.getOrderCart);
        this.polling = setInterval(this.getOrderCart, 2000); // Every 3s
    },

    beforeUnmount() {
        eventBus.off('cart-updated', this.getOrderCart);
        clearInterval(this.polling);
    },
    methods: {
        async getOrderCart() {
            // Fetch the order cart data
            try {
                const res = await axios.get(
                    `/orderCart/${this.checkInParam.checkin_id}`,
                );
                this.orderCartData = res.data;
                //this.orderCartCount = this.orderCartData.length;
            } catch (error) {
                console.error(error);
            }
        },

        orderCartClick() {
            if (this.orderCartCount > 0) {
                this.showCartModal = true;
            } else {
                this.emptyOrderAlert = true;
                setTimeout(() => {
                    this.emptyOrderAlert = false;
                }, 2000);
            }
        },
        handleSettingAction(action) {
            if (typeof this[action] === 'function') {
                this[action]();
            } else {
                console.warn(`No method defined for action: ${action}`);
            }
        },

        goToCourseMenu() {
            this.showCourseMenuModal = true;
        },

        reloadPage() {
            location.reload();
        },

        logout() {
            axios.post('/logout').then(() => {
                location.href = '/login';
            });
        },

        openHelp() {
            alert('ヘルプページは準備中です！');
        },

        onFabClick() {
            // show order cart modal
            this.showCartModal = true;
        },
    },
};
</script>

<style>
.pdf-content {
    padding: 20px;
    border: 1px solid #ddd;
    background: white;
}
</style>
