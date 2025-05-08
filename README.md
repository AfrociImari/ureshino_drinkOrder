<p>$ rmdir ureshino_drinkOrder/</p>

<p>$ git clone https://github.com/AfrociImari/ureshino_drinkOrder.git</p>

<p>~/ureshino_drinkOrder$ cp .env.example .env</p>

<p> => ERROR [l11dev-app 10/12] RUN npm install     が出た場合は以下を変更</p>

docker/php/Dockerfile
<p>変更前　RUN npm install</p>
<p>変更後　RUN npm install --legacy-peer-deps</p>

.envファイルの設定
<p>変更前　APP_URL=http://localhost</p>
<p>変更後　APP_URL=http://192.168.0.5</p>

.envファイルのDB設定
<p>変更前　
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_vue_afroci
DB_USERNAME=root
DB_PASSWORD=
</p>

<p>変更後
DB_CONNECTION=mysql
DB_HOST=l11dev-mysql
DB_PORT=3306
DB_DATABASE=l11dev
DB_USERNAME=root
DB_PASSWORD=root
</p>

vite.config.jsファイルの設定
<p>serverでhostアドレスを設定する
 hmr: {
            host: '192.168.0.105',
        },
</p>

Docker起動する
docker-compose down
docker-compose up -d

//docker containerに入れる
docker compose exec l11dev-app bash
 
<p>container内で実行する
//App_keyを作成する
php artisan key:generate 
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
</p>