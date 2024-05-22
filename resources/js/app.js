import './bootstrap';
import {createApp} from 'vue';
import App from './components/App.vue';
import {usePlugins} from "./helpers.js";

const app = createApp(App);

usePlugins(app);

app.mount("#app");
