<!-- eslint-disable prettier/prettier -->
<template>
    <MobileAppLayout :checkInParam="checkIn">
        <div class="tabs-container">
            <v-tabs v-model="activeTab" align-tabs="center" grow>
              <v-tab
                v-for="(drinkData, index) in drinkDataArray"
                :key="index"
                :value="index"
              >
                {{ drinkData.parent_category }}
              </v-tab>
            </v-tabs>
          </div>      

          <div class="content-container">
            <v-window v-model="activeTab">
              <v-window-item
                v-for="(drinkData, index) in drinkDataArray"
                :key="index"
                :value="index"
              >
                <v-list class="scrollable-list">
                  <v-list-item
                    v-for="(item, i) in drinkData.data"
                    :key="i"
                    @click="handleClick(item)"
                    class="rounded-lg border"
                  >
                    <template v-slot:prepend>
                      <v-avatar size="100">
                        <v-img :src="item.image" alt="Item Image"></v-img>
                      </v-avatar>
                    </template>
      
                    <v-list-item-content>
                      <v-list-item-title>{{ item.product_name }}</v-list-item-title>
                      <v-list-item-subtitle>{{ item.unitprice.toLocaleString() }}</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </v-window-item>
            </v-window>
          </div>

        <v-dialog v-model="showDetailFlg" scrollable persistent>
            <v-card
                class="rounded-t-m mx-auto my-auto"
                elevation="2"
                max-width="100%"
                style="
                    border: 2px solid #5d4037;
                    border-radius: 12px;
                    border-top-left-radius: 12px;
                    border-top-right-radius: 12px;
                "
            >
                <v-img
                    height="250"
                    width="100%"
                    :src="drinkDetailData.image"
                    align="center"
                    class="rounded-t-m"
                    cover
                ></v-img>

                <div class="d-flex justify-space-between align-center px-16">
                    <v-card-title class="pa-0">{{
                        drinkDetailData.product_name
                    }}</v-card-title>
                    <span class="text-h5">{{ selectedChipValue }}円</span>
                </div>

                <v-card-text align="center">
                    <div class="d-flex align-center mt-3 justify-center">
                        <v-icon
                            class="me-2"
                            style="font-size: 40px; margin-left: 15px"
                            @click="plusQuantity"
                        >
                            mdi-plus-circle
                        </v-icon>
                        <v-icon
                            class="me-2"
                            style="font-size: 40px; margin-left: 15px"
                            @click="minusQuantity"
                        >
                            mdi-minus-circle
                        </v-icon>

                        <v-chip
                            class="text-h5 ms-6"
                            variant="outlined"
                            rounded="xl"
                            style="
                                width: 40px;
                                height: 40px;
                                justify-content: center;
                                align-items: center;
                            "
                        >
                            {{ drinkQuantity }}
                        </v-chip>
                    </div>
                </v-card-text>

                <v-divider class="mx-4"></v-divider>

                <v-card-title>サイズを選んでください</v-card-title>
                
                <v-card-text>
                    <v-chip-group
                        v-model="selectedChipValue"
                        class="d-flex flex-wrap justify-center"
                        mandatory
                        @update:modelValue="handleChipChange">
                        <v-chip
                            v-for="(chip, index) in dataChipsList"
                            :key="index"
                            :value="chip.value"
                            :class="{'selected-chip': selectedChipIndex === index}"
                            style="
                                font-size: 20px;
                                font-weight: bold;
                                height: 50px;
                                width: 100px;
                                padding: 10px 20px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                text-align: center;
                            "
                            outlined
                        >
                            {{ chip.label }}
                        </v-chip>
                    </v-chip-group>
                </v-card-text>

                <v-card-actions class="justify-center py-4">
                    <v-btn
                        color="green lighten-2"
                        variant="outlined"
                        class="mx-2"
                        text
                        @click="sendToCart"
                    >
                        カートに入れる
                    </v-btn>
                    <v-btn
                        color="orange lighten-2"
                        variant="outlined"
                        class="mx-2"
                        text
                        @click="getInitialData"
                    >
                        キャンセル
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </MobileAppLayout>
</template>

<script>
import MobileAppLayout from '@/Pages/MobileApp/MobileAppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import eventBus from '@/Event/eventBus';

export default {
    components: {
        MobileAppLayout,
    },
    setup() {
        const page = usePage();
        const checkIn = computed(() => page.props.checkin);

        return {
            page,
            checkIn,
        };
    },

    data: () => ({
        activeTab: 0,
        drinkDataArray: [],
        drinkDetailData: {},
        showDetailFlg: false,
        showAlert: false,
        drinkQuantity: 1,
        showHistoryModal: false,
        orderedDrinkData: {},
        orderedDrinkDataArray: [],
        dataChipsList: [],
        selectedChipLabel: null, // Store the selected chip label
        selectedChipValue: null, // Store the selected chip value
        selectedChipIndex: null, // Store the selected chip index
    }),

    created() {
        this.getInitialData();
    },

    computed: {
        drinkTotalAmount() {
            return this.drinkQuantity * this.selectedChipValue; // Auto-calculated total
        },
    },

    mounted() {
        this.polling = setInterval(() => {
            this.getCheckinStatus();
        }, 3000);
    },

    beforeUnmount() {
        clearInterval(this.polling);
    },

    methods: {
        getInitialData() {
            const grouped = {};
            const uniqueItems = new Set(); // Store unique product names
            this.showDetailFlg = false;
            this.drinkQuantity = 1;

            axios
                .get('/drinkMenu')
                .then((res) => {
                    res.data.forEach((item) => {
                        if (!uniqueItems.has(item.product_name)) {
                            uniqueItems.add(item.product_name); // Add unique product name

                            if (!grouped[item.category.parent_category]) {
                                grouped[item.category.parent_category] = [];
                            }
                            grouped[item.category.parent_category].push(item);
                        }
                    });

                    this.drinkDataArray = Object.keys(grouped).map(
                        (parent_category) => ({
                            parent_category,
                            data: grouped[parent_category],
                        }),
                    );
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async handleClick(item) {
            this.showDetailFlg = true;

            // Fetch the detailed drink data based on the menu_id
            try {
                const res = await axios.get(`/drinkMenu/${item.menu_id}`);
                this.drinkDetailData = res.data;
            } catch (error) {
                console.error(error);
            }

            // Clear the previous size options (chip list)
            this.dataChipsList = [];

            // Fetch the variants (Small, Medium, Large) and their unit prices based on product_name
            try {
                const res = await axios.get(
                    `/drinkMenu/variants/${item.product_name}`,
                );
                // Assuming the response contains an array of variants with unitprice
                res.data.forEach((cat) => {
                    this.dataChipsList.push({
                        label: cat.variant, // The name of the variant (e.g., Small, Medium, Large)
                        value: cat.unitprice, // The price associated with the variant
                    });
                });

                // Default selectedChip to the first item's unit price (optional)
                if (this.dataChipsList.length > 0) {
                    this.selectedChipIndex = 0;
                    this.selectedChipLabel = this.dataChipsList[0].label;
                    this.selectedChipValue = this.dataChipsList[0].value;
                }
            } catch (error) {
                console.error(error);
            }
        },

        handleChipChange(value) {
            const selectedChipIndex = this.dataChipsList.findIndex(
                (chip) => chip.value === value,
            );

            if (selectedChipIndex !== -1) {
                this.selectedChipIndex = selectedChipIndex;
                this.selectedChipLabel =
                    this.dataChipsList[selectedChipIndex].label;
                this.selectedChipValue = value;
            }
        },

        plusQuantity() {
            this.drinkQuantity++;
        },

        minusQuantity() {
            if (this.drinkQuantity > 1) {
                this.drinkQuantity--; // Decrease, but not below 1
            }
        },

        selectChip(index, label, value) {
            this.selectedChipIndex = index; // Track the specific index
            this.selectedChipLabel = label;
            this.selectedChipValue = value;
        },

        async sendToCart() {
            console.log('Label:' + this.selectedChipLabel);
            console.log('Value:' + this.selectedChipValue);
            await axios
                .post('/orderCart', {
                    checkin_id: this.checkIn.checkin_id,
                    product_name: this.drinkDetailData.product_name,
                    variant: this.selectedChipLabel,
                    unitprice: this.selectedChipValue,
                    quantity: this.drinkQuantity,
                    total_amount: this.selectedChipValue * this.drinkQuantity,
                })
                .then(() => {
                    eventBus.emit('cart-updated'); // Allow listener to be registered
                })
                .catch((error) => {
                    console.log(error);
                });

            this.showDetailFlg = false;
        },

        async getCheckinStatus() {
            try {
                const res = await axios.get(`/checkin/${this.checkIn}`);
                if (res.data.order_stop == 'true') {
                    this.getInitialData();
                }
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>

<style>
.tabs-container {
    position: sticky;
    top: 64px; /* v-app-bar height */
    z-index: 9;
    background-color: white;
}

.content-container {
    height: calc(100vh - 64px - 48px); /* app-bar (64px) + tabs (48px) */
    overflow-y: auto;
}

.scrollable-list {
    max-height: 100%;
}

.pdf-content {
    padding: 20px;
    border: 1px solid #ddd;
    background: white;
}
.custom-chip {
    font-size: 24px;
    font-weight: bold;
    height: 50px;
    width: 100px;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}
.selected-chip {
    background-color: #712809 !important; /* deep-purple */
    color: white !important;
}
</style>

