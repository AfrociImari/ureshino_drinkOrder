<template>
    <v-row justify="center">
        <v-dialog v-model="isVisible" scrollable max-width="400px" persistent>
            <v-card>
                <v-card-title>{{ orderDataList[0]?.course_type || '' }}</v-card-title>
                <v-divider></v-divider>

                <v-card-text style="height: 600px; overflow-x: auto">
                    <div
                        style="
                            display: flex;
                            flex-wrap: wrap;
                            max-width: calc(10 * 40px);  /* 10 columns × column width */
                        "
                    >
                        <div
                            v-for="(item, index) in orderDataList"
                            :key="index"
                            class="yuji-syuku-regular"
                            style="
                                writing-mode: vertical-rl;
                                text-orientation: upright;
                                margin-top: 6px;
                                margin-right: 6px;
                                padding: 2px;
                                width: 40px;    /* fixed width per column */
                                min-height: 200px;
                                font-size: 16px;
                                border: 1px solid #ccc;
                            "
                        >
                            {{ item.course_name }}
                        </div>
                    </div>
                </v-card-text>
                <!-- <v-divider></v-divider>
                <v-card-text class="text-h6 font-weight-bold">
                    合計金額: ¥{{ totalOrderAmount.toLocaleString() }}
                </v-card-text>
                <v-divider></v-divider> -->
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
        showCourseMenuModal: {
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
                return this.showCourseMenuModal;
            },
            set(value) {
                if (!value) this.$emit('close');
            },
        },

        // totalOrderAmount() {
        //     return this.orderDataList.reduce(
        //         (sum, item) => sum + Number(item.amount),
        //         0,
        //     );
        // },
    },

    watch: {
        showCourseMenuModal(newVal) {
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
                .get('/courseMenu')
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


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Yuji+Syuku&display=swap');

.yuji-syuku-regular {
    font-family: 'Yuji Syuku', serif;
    font-weight: 400;
    font-style: normal;
}
</style>