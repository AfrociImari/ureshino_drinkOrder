<!-- eslint-disable prettier/prettier -->
<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";

// Props
const props = defineProps({
    showCartModal: {
        type: Boolean,
        required: true,
    },
    checkIn: {
            type: Object,
            required: true,
        }
});

const emit = defineEmits(["close"]);

// Local state
const isVisible = ref(props.showCartModal);
const showAlert = ref(false);
const orderDataList = ref([]);

// Watch for prop changes to update local state
watch(
    () => props.showCartModal,
    (newVal) => {
        isVisible.value = newVal;
    }
);

// Watch local state to emit close when modal is hidden
watch(isVisible, (val) => {
    if (!val) emit("close");
});

// Get order data
const getOrderList = async () => {
    try {
        const res = await axios.get(`/orderCart/${props.checkIn.checkin_id}`);
        orderDataList.value = res.data;
    } catch (error) {
        console.error("Error fetching order list:", error);
    }
};

// Quantity controls
const plusQuantity = (index) => {
    orderDataList.value[index].quantity++;
    updateTotalAmount(index);
    const orderData = orderDataList.value[index];
    updateQuantityOrderCart(orderData);
   
};

const minusQuantity = (index) => {
    if (orderDataList.value[index].quantity > 1) {
        orderDataList.value[index].quantity--;
        updateTotalAmount(index);
        const orderData = orderDataList.value[index];
        updateQuantityOrderCart(orderData);
    }
};

const updateQuantityOrderCart = (data) => {
    axios.put(`/orderCart/${data.id}`, {
        quantity: data.quantity,
        total_amount: data.total_amount})
                    .then(() => {
                        //this.initialize();
                    })
                    .catch((error) => {
                        console.log(error);
            });
   
};


const updateTotalAmount = (index) => {
    orderDataList.value[index].total_amount =
        Number(orderDataList.value[index].unitprice) * Number(orderDataList.value[index].quantity);
};

const deleteOrderItem = (item,index) => {
    orderDataList.value.splice(index, 1);
    axios.delete(`/orderCart/${item.id}`).then(() => {
        emit("refresh");
        if (orderDataList.value.length === 0) {
            isVisible.value = false;
        }
    });
    
};

// Confirm order
const confirmOrder = async () => {
    try {
        const promises = orderDataList.value.flatMap((item) => [
            axios.post("/drinkOrder", {
                checkin_id: props.checkIn.checkin_id,
                date: props.checkIn.date,
                type: props.checkIn.type,
                product_name: item.product_name,
                variant: item.variant,
                quantity: item.quantity.toString(),
                amount: item.total_amount.toString(),
                served_flg: 'false'
            }),
            axios.delete(`/orderCart/${item.id}`)
        ]);

        await Promise.all(promises);

        // Trigger parent to refresh order data
        emit("refresh");
        orderDataList.value = [];

        triggerAlert(); // success message or UI feedback
    } catch (error) {
        console.error("Error confirming order:", error);
    }
};

const triggerAlert = () => {
    showAlert.value = true;
    setTimeout(() => {
        showAlert.value = false;
        isVisible.value = false;
    }, 2000);
};

onMounted(() => {
    getOrderList();
    setInterval(getOrderList, 2000); // Poll every 2 seconds
});
</script>

<template>
    <v-dialog v-model="isVisible" scrollable max-width="400px" persistent>
        <v-container>
            <v-card v-if="orderDataList.length > 0">
                <v-card-title>注文カート</v-card-title>
                <v-divider></v-divider>

                <!-- Scrollable Content -->
                <v-card-text style="padding: 0; height: 500px">
                    <v-sheet style="height: auto">
                        <v-list style="max-height: 400px; overflow-y: auto">
                            <v-list-item
                                v-for="(item, i) in orderDataList"
                                :key="i"
                                class="rounded-lg border"
                            >
                                <v-row
                                    align="center"
                                    justify="space-between"
                                    class="w-100"
                                >
                                    <!-- Left: Item Content -->
                                    <v-col cols="6">
                                        <v-list-item-content>
                                            <v-list-item-title>{{
                                                item.product_name
                                            }}</v-list-item-title>
                                            <v-list-item-subtitle
                                                >小計: ¥{{
                                                    item.total_amount
                                                }}</v-list-item-subtitle
                                            >
                                        </v-list-item-content>
                                    </v-col>

                                    <!-- Right: Quantity Controls -->
                                    <v-col
                                        cols="6"
                                        class="d-flex align-center justify-end"
                                    >
                                        <v-icon
                                            class="me-2"
                                            style="
                                                font-size: 25px;
                                                cursor: pointer;
                                            "
                                            @click="minusQuantity(i)"
                                        >
                                            mdi-minus-circle
                                        </v-icon>

                                        <v-chip
                                            class="text-h6 mx-2"
                                            variant="outlined"
                                            pill
                                            style="
                                                width: 30px;
                                                height: 30px;
                                                min-width: 30px;
                                                min-height: 30px;
                                                display: flex;
                                                justify-content: center;
                                                align-items: center;
                                                border-radius: 50%;
                                                padding: 0;
                                            "
                                        >
                                            {{ item.quantity }}
                                        </v-chip>

                                        <v-icon
                                            class="me-2"
                                            style="
                                                font-size: 25px;
                                                cursor: pointer;
                                            "
                                            @click="plusQuantity(i)"
                                        >
                                            mdi-plus-circle
                                        </v-icon>
                                        <v-spacer></v-spacer>
                                        <v-icon
                                            class="me-2"
                                            style="
                                                font-size: 25px;
                                                cursor: pointer;
                                            "
                                            @click="deleteOrderItem(item,i)"
                                        >
                                            mdi-delete
                                        </v-icon>
                                    </v-col>
                                </v-row>
                            </v-list-item>
                        </v-list>
                    </v-sheet>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions class="d-flex justify-end">
                    <v-btn color="blue darken-1" text @click="confirmOrder">
                        注文する
                    </v-btn>
                    <v-btn color="red darken-1" text @click="isVisible = false">
                        キャンセル
                    </v-btn>
                </v-card-actions>
            </v-card>

            <!-- Success Alert -->
            <v-alert v-if="showAlert" type="success" class="mt-4">
                注文が成功しました！
            </v-alert>
        </v-container>
    </v-dialog>
</template>
