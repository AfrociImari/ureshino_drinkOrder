<template>
    <v-row justify="center">
        <v-dialog v-model="isVisible" scrollable max-width="300px" persistent>
            <v-card>
                <v-card-title>注文履歴</v-card-title>
                <v-divider></v-divider>
                <v-card-text style="height: 500px">
                    <v-list>
                        <v-list-item
                            v-for="(item, i) in orderDataList"
                            :key="i"
                            class="rounded-lg border"
                        >
                            <v-list-item-content>
                                <div class="d-flex justify-space-between">
                                    <div>
                                        <v-list-item-title>{{
                                            item.product_name
                                        }}</v-list-item-title>
                                        <v-list-item-subtitle>{{
                                            item.quantity
                                        }}</v-list-item-subtitle>
                                        <v-list-item-subtitle>{{
                                            item.amount.toLocaleString()
                                        }}</v-list-item-subtitle>
                                    </div>
                                    <div class="text-right">
                                        <v-list-item-subtitle>{{
                                            formatTime(item.created_at)
                                        }}</v-list-item-subtitle>
                                    </div>
                                </div>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text class="text-h6 font-weight-bold">
                    合計金額: ¥{{ totalOrderAmount.toLocaleString() }}
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="isVisible = false"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    props: {
        showHistoryModal: {
            type: Boolean,
            required: true,
        },
        checkIn: {
            type: Object,
            required: true,
        },
    },
    computed: {
        isVisible: {
            get() {
                return this.showHistoryModal;
            },
            set(value) {
                if (!value) this.$emit('close');
            },
        },

        totalOrderAmount() {
            return this.orderDataList.reduce(
                (sum, item) => sum + Number(item.amount),
                0,
            );
        },
    },

    watch: {
        showHistoryModal(newVal) {
            if (newVal) {
                this.getOrderHistory();
            }
        },
    },
    data: () => ({
        orderDataList: [],
    }),
    created() {
        this.getOrderHistory();
    },

    methods: {
        getOrderHistory() {
            axios
                .get(`/drinkOrder/${this.checkIn.checkin_id}`)
                .then((res) => {
                    this.orderDataList = res.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        formatTime(datetime) {
            const date = new Date(datetime);
            return date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
        },
    },
};
</script>
