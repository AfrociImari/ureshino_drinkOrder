// stores/useCartStore.js
import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        orders: JSON.parse(localStorage.getItem('selectedOrders') || '[]'),
    }),
    getters: {
        isEmpty: (state) => state.orders.length === 0,
    },
    actions: {
        setOrders(orders) {
            this.orders = orders;
            localStorage.setItem('selectedOrders', JSON.stringify(orders));
        },
        clearOrders() {
            this.orders = [];
            localStorage.removeItem('selectedOrders');
        },
    },
});
