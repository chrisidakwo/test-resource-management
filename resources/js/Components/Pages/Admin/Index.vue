<template>
    <div class="block mb-4 bg-green-600 p-4 w-full rounded-md" v-if="flashMessage.length > 0">
        <h3 class="text-white font-semibold">{{ flashMessage }}</h3>
    </div>

    <div class="block w-full mb-4">
        <button class="btn btn-primary" type="button" @click="openCreateForm">New Resource</button>
    </div>

    <data-table
        :data="resources.data"
        :pagination="resources.pagination"
        @onPageChanged="handlePageChanged"
        @onSelectedAllRows="handleSelectedRows"
        @onEditRow="handleEditResource"
        @onDeleteRows="handleDestroyResources"
        :use-checkbox="true"
        :selected-rows-prop="selectedRows"
        max-height="600">
        <template v-slot:actionHeaders>
            <th class="bg-gray-300 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-700 font-bold tracking-wider uppercase text-xs"></th>
        </template>

        <template v-slot:dataRow="{ dataRow, activeHeaders }">
            <tr>
                <td class="border-dashed border-t border-gray-200 px-3">
                    <label
                        class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer">
                        <input type="checkbox" class="form-checkbox rowCheckbox focus:outline-none focus:shadow-outline" :name="dataRow.id"
                               @click="handleRowClick($event, dataRow.id)">
                    </label>
                </td>
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
                                Go to link
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
                            <a :href="dataRow.file" target="_blank"
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


    <div v-if="showModal" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-semibold text-gray-900 mb-6" id="modal-title">Update Resource</h3>

                                <div class="block mb-4 bg-red-600 p-4 w-full rounded-md" v-if="submitErrorMessage.summary.length > 0">
                                    <h3 class="text-white font-semibold">{{ submitErrorMessage.summary }}</h3>
                                </div>

                                <resource-form
                                    :data="resourceData.data"
                                    @onCancel="handleCancelSubmit"
                                    @onFormSubmit="handleFormSubmit"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import DataTable from "../../DataTable";
import Copy from "../../../icons/copy";
import useResources from "../../../composables/useResources";
import {onMounted, ref} from "vue";
import {copyToClipboard, externalRedirect} from "../../../utils";
import {useState} from "../../../composables/useState";
import ResourceForm from "./ResourceForm";

// Composables
const {
    resources,
    resourceData,
    getResources,
    createResource,
    updateResource,
    deleteResources,
} = useResources();

// Properties
const [page, setPage] = useState('1');
const [selectedRows, setSelectedRows] = useState([]);
const [showModal, setShowModal] = useState(false);
const [flashMessage, setFlashMessage] = useState('');
const submitErrorMessage = ref({
    summary: '',
    errors: '',
});

// Component hooks
onMounted(() => {
    getResources(page.value);
});

// Methods
/**
 *
 * @param {MouseEvent} $event
 * @param {string} id
 */
const handleRowClick = ($event, id) => {
    if ($event.target.checked) {
        setSelectedRows(selectedRows.value.concat(id));
    } else {
        setSelectedRows(selectedRows.value.filter((row) => row !== id));
    }
};

/**
 * Copy text to clipboard.
 *
 * @param {string} snippet
 */
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
    setPage(newPage.toString());

    // Refetch resources
    getResources(newPage.toString());
}

/**
 *
 * @param {array} rows
 */
const handleSelectedRows = (rows) => {
    setSelectedRows(rows);
}

const openCreateForm = () => {
    resourceData.data = null;

    setShowModal(true);
}

const handleEditResource = (event) => {
    resourceData.data = event.data;
    setShowModal(true);
}

const handleCancelSubmit = () => {
    setShowModal(false);
    setFlashMessage('');
}

const handleFormSubmit = (data) => {
    // Hack to get this damn thing submitting with the file! :(
    const formData = new FormData();

    if (data.file) {
        const file = data.file;
        formData.append('file', file);

        delete data.file;
    }

    Object.keys(data).forEach((value) => {
        formData.set(value, data[value]);
    });

    if (undefined !== data.id) {
        formData.set('_method', 'put');
        updateResource(resourceData.data.id, formData).then((result) => {
            setShowModal(false);

            // Refetch resources
            getResources(page.value);

            // Show flash message
            setFlashMessage('Resource has been updated');
        }).catch((error) => {
            if (error.response.status === 422) {
                const errorMessage = error.response.data.message;
                const errors = error.response.data.errors;

                submitErrorMessage.value = {
                    summary: errorMessage,
                    errors: errors,
                };
            }
        })
    } else {
        createResource(formData).then((result) => {
            setShowModal(false);

            // Refetch resources
            getResources(page.value);

            // Show flash message
            setFlashMessage('Resource has been added');
        }).catch((error) => {
            if (error.response.status === 422) {
                const errorMessage = error.response.data.message;
                const errors = error.response.data.errors;

                submitErrorMessage.value = {
                    summary: errorMessage,
                    errors: errors,
                };
            }
        })
    }
}

const handleDestroyResources = (resources) => {
    if (!window.confirm('Are you sure you want to lose these records?')) {
        return;
    }

    deleteResources(resources).then((response) => {
        setFlashMessage('Resources have been deleted');

        getResources(page.value);
    });
}
</script>
