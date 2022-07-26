import {createApp} from "vue";
import HomePage from "./Components/Pages/Home/Index";
import AdminIndex from "./Components/Pages/Admin/Index";

const app = createApp({});
app.component('home-page', HomePage);
app.component('admin-index', AdminIndex);
app.mount('#app');

