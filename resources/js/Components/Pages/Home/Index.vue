<template>
    <data-table :data="[]" :pagination="{}" max-height="600" />
</template>

<script setup>
import DataTable from "../../DataTable";
import { useResources } from "../../../composables/useResources";
import { onMounted } from "vue";

onMounted(() => {
   const { resources, getResources } = useResources();
});
</script>

<style scoped>

</style>
