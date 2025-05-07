<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import QrcodeVue from 'qrcode.vue';
</script>

<template>
    <Head title="ユーザー一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                ユーザー一覧
            </h2>
        </template>

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
                                                New Item
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
                                                                    editedItem.name
                                                                "
                                                                label="Name"
                                                            ></v-text-field>
                                                        </v-col>
                                                        <v-col
                                                            cols="12"
                                                            md="4"
                                                            sm="6"
                                                        >
                                                            <v-text-field
                                                                v-model="
                                                                    editedItem.email
                                                                "
                                                                label="Email"
                                                            ></v-text-field>
                                                        </v-col>
                                                        <v-col
                                                            cols="12"
                                                            md="4"
                                                            sm="6"
                                                        >
                                                            <v-text-field
                                                                v-model="
                                                                    editedItem.password
                                                                "
                                                                label="Password"
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
                                    class="me-2"
                                    size="small"
                                    @click="editItem(item)"
                                >
                                    mdi-pencil
                                </v-icon>
                                <v-icon size="small" @click="deleteItem(item)">
                                    mdi-delete
                                </v-icon>
                                <v-icon size="small" @click="createQrCode">
                                    mdi-qrcode
                                </v-icon>
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
    <div v-if="isModalOpen" class="modal" @click="isModalOpen = false">
        <div class="modal-content">
            <QrcodeVue :value="qrValue" :size="400" level="H" />
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        dialog: false,
        dialogDelete: false,
        headers: [
            { title: 'Name', key: 'name' },
            { title: 'Email', key: 'email' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],
        desserts: [],
        editedIndex: -1,
        editedItem: {
            id: '',
            name: '',
            email: '',
            password: '',
        },
        defaultItem: {
            id: '',
            name: '',
            email: '',
            password: '',
        },
        // qrValue: 'http://192.168.0.104/showReceiptMobile',
        qrValue: '',
        isModalOpen: false,
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item';
        },
    },

    watch: {
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        },
    },

    created() {
        this.initialize();
    },

    methods: {
        initialize() {
            axios
                .get('/users')
                .then((res) => {
                    this.desserts = res.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        createQrCode() {
            var room_no = '202';
            var date = '2024-04-03';
            var type = 'lunch';
            axios
                .post(`/drinkMenu/getEncryptedUrl/${room_no}/${date}/${type}`)
                .then((res) => {
                    this.qrValue = res.data;
                    console.log(this.qrValue)
                    this.isModalOpen = true;
                })
                .catch((error) => {
                    console.log(error);
                });
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
                    .post('/users', {
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
            } else {
                axios
                    .put(`/users/${this.editedItem.id}`, {
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
