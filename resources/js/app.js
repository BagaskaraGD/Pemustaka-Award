import './bootstrap';
import axios from 'axios';
window.axios = axios;
// (Opsional) Kalau backend kamu beda domain:
axios.defaults.baseURL = 'http://backend-pemustakaaward.test/api/';// ganti dengan URL backend kamu
