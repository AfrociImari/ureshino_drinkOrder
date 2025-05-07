<template>
    <v-card>
        <v-toolbar color="warning" dark dense flat>
            <v-toolbar-title class="text-body-2">
                QR Code エラー
            </v-toolbar-title>
        </v-toolbar>
        <v-card-text>
            <p>現在のQRCodeはご利用できません。
            チェックインする時に新しいQRCodeを発行されたので
            チェックイン窓口に連絡してください。</p>
           <p style="color: red; margin-top: 16px;"> 新しいQRCodeを発行された後に使えない場合は
            注文が停止された状況になりました。</p>
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
        </v-card-actions>
    </v-card>
</template>
