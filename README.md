<p>$ rmdir ureshino_drinkOrder/</p>

<p>$ git clone https://github.com/AfrociImari/ureshino_drinkOrder.git</p>

<p>~/ureshino_drinkOrder$ cp .env.example .env</p>

<p> => ERROR [l11dev-app 10/12] RUN npm install     が出た場合は以下を変更</p>

docker/php/Dockerfile
<p>変更前　RUN npm install</p>
<p>変更後　RUN npm install --legacy-peer-deps</p>
