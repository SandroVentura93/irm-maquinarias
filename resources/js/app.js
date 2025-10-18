require('./bootstrap');

import { createApp } from 'vue';
import Productos from './components/Productos.vue';

createApp({}).component('productos', Productos).mount('#app');

