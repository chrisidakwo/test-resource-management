<template>
    <data-table
        :data="resources.data"
        :pagination="resources.pagination"
        @onPageChanged="handlePageChanged"
        max-height="600">
        <template v-slot:actionHeaders>
            <th class="bg-gray-300 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-700 font-bold tracking-wider uppercase text-xs"></th>
        </template>

        <template v-slot:dataRow="{ dataRow }">
            <tr>
                <td class="border-dashed border-t border-gray-200 title">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        {{ dataRow.title }}
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200 type">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        {{ dataRow.type.toUpperCase() }}
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200 link">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        <template v-if="dataRow.link">
                            <a :href="dataRow.link" :target="dataRow.link_target" v-if="dataRow.link_target === '_blank'">
                                {{ dataRow.link }}
                            </a>
                            <a :href="dataRow.link" @click.prevent="externalRedirect(dataRow.link)" v-else>
                                {{ dataRow.link }}
                            </a>
                        </template>
                        <template v-else>---</template>
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200 file">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        <template v-if="dataRow.file">{{ dataRow.file }}</template>
                        <template v-else>---</template>
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        <template v-if="dataRow.html_snippet">
                            <button class="btn uppercase text-sm btn-nude" @click="copyToClipboard(dataRow.html_snippet)">
                                <Copy color="rgb(8 145 178)"/>
                                <span class="ml-1">Copy HTML</span>
                            </button>
                        </template>
                    </span>
                </td>
            </tr>
        </template>
    </data-table>
</template>

<script setup>
import DataTable from "../../DataTable";
import Copy from "../../../icons/copy";
import useResources from "../../../composables/useResources";
import { onMounted, ref } from "vue";
import { externalRedirect, copyToClipboard } from "../../../utils";

// Composables
const { resources, getResources, deleteResource } = useResources();

// Properties
const page = ref('1');

// Component hooks
onMounted(() => {
   getResources(page.value);
});

// Methods
/**
 * Handle page changed event from datatable
 *
 * @param {int} newPage
 */
const handlePageChanged = (newPage) => {
    page.value = newPage.toString();

    // Refetch resources
    getResources(newPage.toString());
}

const destroyResource = (resourceId) => {
    if (!window.confirm('Are you sure you want to lose this record?')) {
        return;
    }

    deleteResource(resourceId).then((response) => {
        getResources(page.value);
    });
}
</script>

<style scoped>

</style>
