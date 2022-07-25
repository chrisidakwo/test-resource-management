<template>
    <data-table
        :data="resources.data"
        :pagination="resources.pagination"
        @onPageChanged="handlePageChanged"
        max-height="600">
        <template v-slot:actionHeaders>
            <th class="bg-gray-300 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-700 font-bold tracking-wider uppercase text-xs"></th>
        </template>

        <template v-slot:dataRow="{ dataRow, activeHeaders }">
            <tr>
                <td class="border-dashed border-t border-gray-200 title" v-if="hasHeader(activeHeaders, 'title')">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        {{ dataRow.title }}
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200 type" v-if="hasHeader(activeHeaders, 'type')">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        {{ dataRow.type.toUpperCase() }}
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200 description" v-if="hasHeader(activeHeaders, 'description')">
                    <span class="text-gray-700 px-6 py-3 flex items-center" :title="dataRow.description">
                        {{ dataRow.description ? dataRow.description.substr(0, 50) + '...' : '' }}
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200 link" v-if="hasHeader(activeHeaders, 'link')">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        <template v-if="dataRow.link">
                            <a :href="dataRow.link"
                               :target="dataRow.linkTarget"
                               class="underline text-cyan-600 active:text-cyan-800"
                               v-if="dataRow.linkTarget === '_blank'">
                                {{ dataRow.link }}
                            </a>
                            <a :href="dataRow.link"
                               @click.prevent="externalRedirect(dataRow.link)"
                               class="underline text-cyan-600 active:text-cyan-800"
                               v-else>
                                Go to link
                            </a>
                        </template>
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200 file" v-if="hasHeader(activeHeaders, 'file')">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        <template v-if="dataRow.file">
                            <a :href="dataRow.file"
                               @click.prevent="download(dataRow.id)"
                               class="underline text-cyan-600 active:text-cyan-800">
                                Download PDF
                            </a>
                        </template>
                    </span>
                </td>

                <td class="border-dashed border-t border-gray-200">
                    <span class="text-gray-700 px-6 py-3 flex items-center">
                        <template v-if="dataRow.htmlSnippet">
                            <button class="btn uppercase text-sm btn-nude" @click="copy(dataRow.htmlSnippet)">
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
import {onMounted, ref} from "vue";
import {copyToClipboard, externalRedirect} from "../../../utils";

// Composables
const { resources, getResources, deleteResource, downloadResource } = useResources();

// Properties
const page = ref('1');

// Component hooks
onMounted(() => {
   getResources(page.value);
});

// Methods
const copy = (snippet) => {
    copyToClipboard(snippet).then(() => alert(`Copied to clipboard \n\n ${snippet}`));
}

/**
 * Checks if a column is part of the active headers.
 *
 * @param {array} headers
 * @param {string} column
 * @returns {boolean}
 */
const hasHeader = (headers, column) => {
    return undefined !== headers.find((header) => header.key === column);
}

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

/**
 * Download a resource's PDF file.
 *
 * @param {string} resourceId
 */
const download = (resourceId) => {

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
