import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
window.axios.defaults.headers.post['Access-Control-Allow-Origin'] = '*';
window.window.axios.defaults.headers.common['Content-Type'] =
    'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
