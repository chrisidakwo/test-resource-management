import {createApp} from "vue";
import HomePage from "./Components/Pages/Home/Index";

const app = createApp({});
app.component('home-page', HomePage);
app.mount('#app');

