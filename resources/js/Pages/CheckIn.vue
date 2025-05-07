<template>
    <Head title=" チェックイン一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                チェックイン一覧
            </h2>
        </template>

        <!-- Show alert if orderDataList is empty -->
        <v-alert v-if="showOrderStopAlert" type="warning" class="mt-4">
            まだ注文されていません
        </v-alert>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <v-container>
                        <v-data-table
                            :headers="headers"
                            :items="desserts"
                            :sort-by="[{ key: 'name', order: 'asc' }]"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title></v-toolbar-title>
                                    <v-divider
                                        class="mx-4"
                                        inset
                                        vertical
                                    ></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-dialog
                                        v-model="dialog"
                                        max-width="500px"
                                    >
                                        <template v-slot:activator="{ props }">
                                            <v-btn
                                                class="mb-2"
                                                color="primary"
                                                v-bind="props"
                                            >
                                                新規作成
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5">{{
                                                    formTitle
                                                }}</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            md="4"
                                                            sm="6"
                                                        >
                                                            <v-text-field
                                                                v-model="
                                                                    editedItem.room_id
                                                                "
                                                                label="部屋ID"
                                                            ></v-text-field>
                                                        </v-col>
                                                        <v-col
                                                            cols="12"
                                                            md="4"
                                                            sm="6"
                                                        >
                                                            <v-text-field
                                                                v-model="
                                                                    editedItem.date
                                                                "
                                                                label="日付"
                                                            ></v-text-field>
                                                        </v-col>
                                                        <v-col
                                                            cols="12"
                                                            md="4"
                                                            sm="6"
                                                        >
                                                            <v-text-field
                                                                v-model="
                                                                    editedItem.timing
                                                                "
                                                                label="時間"
                                                            ></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                    color="blue-darken-1"
                                                    variant="text"
                                                    @click="close"
                                                >
                                                    Cancel
                                                </v-btn>
                                                <v-btn
                                                    color="blue-darken-1"
                                                    variant="text"
                                                    @click="save"
                                                >
                                                    Save
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                    <v-dialog
                                        v-model="dialogDelete"
                                        max-width="500px"
                                    >
                                        <v-card>
                                            <v-card-title class="text-h5"
                                                >Are you sure you want to delete
                                                this item?</v-card-title
                                            >
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                    color="blue-darken-1"
                                                    variant="text"
                                                    @click="closeDelete"
                                                    >Cancel</v-btn
                                                >
                                                <v-btn
                                                    color="blue-darken-1"
                                                    variant="text"
                                                    @click="deleteItemConfirm"
                                                    >OK</v-btn
                                                >
                                                <v-spacer></v-spacer>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:[`item.actions`]="{ item }">
                                <v-icon
                                    class="mx-3"
                                    size="large"
                                    color="blue"
                                    @click="editItem(item)"
                                >
                                    mdi-pencil
                                </v-icon>
                                <v-icon
                                    class="mx-3"
                                    size="large"
                                    color="red"
                                    @click="deleteItem(item)"
                                >
                                    mdi-delete
                                </v-icon>
                                <v-icon
                                    class="mx-3"
                                    size="large"
                                    @click="clickQrcodeBtn(item)"
                                >
                                    mdi-qrcode
                                </v-icon>
                                <!-- v-if="orderCountByRoom[item.room.room_name] > 0 -->
                                <v-badge
                                    :key="orderCountByRoom[item.checkin_id]"
                                    v-if="item.order_stop == 'false'"
                                    :content="
                                        orderCountByRoom[item.checkin_id] || 0
                                    "
                                    color="red"
                                    overlap
                                >
                                    <v-icon
                                        v-if="item.order_stop == 'false'"
                                        class="mx-3"
                                        size="large"
                                        color="green"
                                        @click="makeOrderStop(item)"
                                    >
                                        mdi-cart-arrow-right
                                    </v-icon>
                                </v-badge>
                            </template>
                            <template v-slot:no-data>
                                <v-btn color="primary" @click="initialize">
                                    Reset
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-container>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <!-- Modal for Larger QR Code -->
    <div v-if="showQrCodeModal" class="modal" @click="showQrCodeModal = false">
        <div class="modal-content">
            <QrcodeVue :value="qrValue" :size="400" level="H" />
        </div>
    </div>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import QrcodeVue from 'qrcode.vue';

export default {
    components: {
        AuthenticatedLayout,
        QrcodeVue,
    },

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item';
        },

        orderCountByRoom() {
            const counts = {};
            if (!Array.isArray(this.orderCartData)) {
                return counts; // Safety check
            }

            this.orderCartData.forEach((order) => {
                const checkin = order.checkin_id;
                if (!counts[checkin]) {
                    counts[checkin] = 0;
                }
                counts[checkin] += parseInt(order.quantity) || 0;
            });

            return counts;
        },
    },

    data: () => ({
        dialog: false,
        dialogDelete: false,
        headers: [
            { title: 'チェックイン', key: 'checkin_id' },
            { title: '部屋番号', key: 'room.room_name' },
            { title: '日付', key: 'date' },
            { title: '時間', key: 'timing' },
            { title: 'タイプ', key: 'type' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],
        desserts: [],
        editedIndex: -1,
        editedItem: {},
        defaultItem: {},
        qrValue: '',
        showQrCodeModal: false,
        showOrderStopAlert: false,
        orderCartData: [],
    }),

    watch: {
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        },
    },

    mounted() {
        this.initialize();
        this.getOrderCart();
        this.polling = setInterval(this.getOrderCart, 3000); // Every 3s
    },

    beforeUnmount() {
        clearInterval(this.polling);
    },

    methods: {
        initialize() {
            axios
                .get('/checkin')
                .then((res) => {
                    this.desserts = res.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        clickQrcodeBtn(item) {
            if (item.qr_code == null || item.qr_code == '') {
                this.createQrCode(item); //Create new QrCode
            } else {
                this.qrValue = item.qr_code;
                this.showQrCodeModal = true;
                console.log(this.qrValue);
            }
        },
        async createQrCode(item) {
            await axios
                .post('/drinkMenu/getEncryptedUrl', {
                    checkin_id: item.checkin_id,
                })
                .then((res) => {
                    this.qrValue = res.data;
                    this.showQrCodeModal = true;
                })
                .catch((error) => {
                    console.log(error);
                });

            await axios
                .put(`/checkin/updateQrCode/${item.checkin_id}`, {
                    qrCode: this.qrValue,
                })
                .then(() => {
                    this.initialize();
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        async getOrderCart() {
            // Fetch the order cart data
            try {
                const res = await axios.get(`/orderCart`);
                this.orderCartData = res.data;
                //console.log('Order Cart:', this.orderCartData);
            } catch (error) {
                console.error(error);
            }
        },

        async makeOrderStop(item) {
            // Fetch the order cart data
            const res = await axios.get(`/orderCart/${item.checkin_id}`);
            if (res.data.length == 0) {
                await axios
                    .put(`/checkin/orderStop/${item.checkin_id}`, {
                        orderStop: 'true', //set 'true' value to order_stop data
                    })
                    .then(() => {
                        //
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                await axios
                    .put(`/checkin/updateQrCode/${item.checkin_id}`, {
                        qrCode: null, //set null to qr_code
                    })
                    .then(() => {
                        this.initialize();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                this.showOrderStopAlert = true;
            }
        },

        editItem(item) {
            this.editedIndex = this.desserts.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },

        deleteItem(item) {
            this.editedIndex = this.desserts.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true;
        },

        deleteItemConfirm() {
            this.desserts.splice(this.editedIndex, 1);
            this.closeDelete();
        },

        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        closeDelete() {
            axios.delete(`/users/${this.editedItem.id}`).then(() => {
                this.dialogDelete = false;
            });
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        save() {
            if (this.editedIndex === -1) {
                axios
                    .post('/checkin', {
                        room_id: this.editedItem.room_id,
                        date: this.editedItem.date,
                        timing: this.editedItem.timing,
                        qr_code: null,
                    })
                    .then(() => {
                        this.initialize();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                axios
                    .put(`/checkin/${this.editedItem.id}`, {
                        name: this.editedItem.name,
                        email: this.editedItem.email,
                        password: this.editedItem.password,
                    })
                    .then(() => {
                        this.initialize();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
            this.close();
        },
    },
};
</script>

<style scoped>
.qr-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}
.qr-small {
    cursor: pointer;
    transition: transform 0.2s;
}

.qr-small:hover {
    transform: scale(1.1);
}

/* Modal Styling */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
}
</style>
