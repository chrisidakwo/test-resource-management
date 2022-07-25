require('./bootstrap');

import { createApp } from "vue";
import DataTable from "./Components/DataTable";
import HomePage from "./Components/Pages/Home/Index";

const app = createApp({});
app.component('data-table', DataTable);
app.component('home-page', HomePage);
app.mount('#app');

