#### $ rmdir ureshino_drinkOrder

#### $ git clone https://github.com/AfrociImari/ureshino_drinkOrder.git

#### ~/ureshino_drinkOrder$ cp .env.example .env

####  => ERROR [l11dev-app 10/12] RUN npm install     が出た場合は以下を変更

#### docker/php/Dockerfile
```
変更前　RUN npm install
変更後　RUN npm install --legacy-peer-deps
```
#### .envファイルの設定
```
変更前　APP_URL=http://localhost
変更後　APP_URL=http://192.168.0.5
```

#### .envファイルのDB設定
```
変更前　
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_vue_afroci
DB_USERNAME=root
DB_PASSWORD=

変更後
DB_CONNECTION=mysql
DB_HOST=l11dev-mysql
DB_PORT=3306
DB_DATABASE=l11dev
DB_USERNAME=root
DB_PASSWORD=root
```

#### vite.config.jsファイルの設定
```
serverでhostアドレスを設定する
 hmr: {
            host: '192.168.0.105',
        },
```

#### Docker起動する
```
docker-compose down
docker-compose up -d
```

#### docker containerに入れる
```
docker compose exec l11dev-app bash
```
 
#### 以下をcontainer内で実行する
```
//App_keyを作成する
php artisan key:generate 
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

