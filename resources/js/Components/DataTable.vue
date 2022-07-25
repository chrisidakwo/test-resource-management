<script>

export default {
    props: {
        data: Array,
        pagination: Object,
        maxHeight: String | undefined,
    },
    data() {
        return {
            selectedRows: [],
            searchStr: '',
            openDisplayDropdown: false,
            currentPage: 1,
            headings: [
                {
                    key: 'title',
                    value: 'Title',
                },
                {
                    key: 'type',
                    value: 'Resource Type',
                },
                {
                    key: 'link',
                    value: 'Link',
                },

                {
                    key: 'file',
                    value: 'File URL',
                },
            ]
        }
    },
    methods: {
        toggleColumn(key) {
            // Note: All td must have the same class name as the headings key!
            let columns = document.getElementsByClassName(key);

            if (this.$refs[key][0].classList.contains('hidden') && this.$refs[key][0].classList.contains(key)) {
                [].forEach.call(columns, column => column.classList.remove('hidden'));
            } else {
                [].forEach.call(columns, column => column.classList.add('hidden'));
            }
        },

        tableSearch(event) {
            this.searchStr = event.target.value;
        },

        updateOpenDisplayDropdown() {
            this.openDisplayDropdown = !this.openDisplayDropdown;
        },

        /**
         * Update current page and emit onPageChanged event
         * @param {string} page
         */
        updateCurrentPage(page) {
            let newPage = this.currentPage;

            if (page.indexOf('Next') >= 0) {
                newPage = this.currentPage + 1;
            } else if (page.indexOf('Previous') >= 0) {
                newPage = this.currentPage - 1;
            } else {
                newPage = Number.parseInt(page);
            }

            if (newPage !== this.currentPage) {
                this.currentPage = newPage;
                this.$emit('onPageChanged', newPage);
            }
        }
    },

    computed: {
        /**
         * Easily search through data, if search string exists. Else, return data as-is.
         *
         * @returns {*[]}
         */
        filteredData() {
            if (this.searchStr.length === 0) {
                return this.data;
            }

            return this.data.filter((value) => {
                // Search only with the title
                return value.title.toLowerCase().indexOf(this.searchStr.toLowerCase()) > -1
            })
        }
    }
}
</script>

<template>
    <div class="container mx-auto py-6">
        <div v-if="selectedRows.length > 0" class="bg-teal-200 z-40 w-full shadow">
            <div class="container mx-auto px-4 py-4">
                <div class="flex md:items-center">
                    <div class="mr-4 flex-shrink-0">
                        <svg class="h-8 w-8 text-teal-600"  viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-teal-800 text-lg">
                        {{ selectedRows.length }} rows are selected
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 flex justify-between items-center">
            <div class="flex-1">
                <div class="relative md:w-1/3">
                    <input type="search"
                           class="w-full border-gray-200 pl-10 pr-4 py-2 rounded-md shadow focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                           placeholder="Search..." @input="event => tableSearch(event)">

                    <div class="absolute top-0 left-0 inline-flex items-center p-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24"
                             stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                             stroke-linejoin="round">
                            <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                            <circle cx="10" cy="10" r="7" />
                            <line x1="21" y1="21" x2="15" y2="15" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <div class="shadow rounded-lg flex">
                    <div class="relative">
                        <button @click="updateOpenDisplayDropdown()"
                                class="rounded-lg inline-flex items-center bg-white hover:text-blue-500 focus:outline-none focus:shadow-outline text-gray-500 font-semibold py-2 px-2 md:px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:hidden" viewBox="0 0 24 24"
                                 stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                <path d="M5.5 5h13a1 1 0 0 1 0.5 1.5L14 12L14 19L10 16L10 12L5 6.5a1 1 0 0 1 0.5 -1.5" />
                            </svg>
                            <span class="hidden md:block">Display</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>

                        <div v-show="openDisplayDropdown"
                             class="z-40 absolute top-0 right-0 w-40 bg-white rounded-lg shadow-lg mt-12 -mr-1 block py-1 overflow-hidden">
                            <template v-for="heading in headings">
                                <label
                                    class="flex justify-start items-center text-truncate hover:bg-gray-100 px-4 py-2">
                                    <div class="text-teal-600 mr-3">
                                        <input type="checkbox" class="form-checkbox focus:outline-none focus:shadow-outline" checked @click="toggleColumn(heading.key)">
                                    </div>
                                    <div class="select-none text-gray-700">
                                        {{ heading.value }}
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative mb-6" :style="[maxHeight ? { maxHeight }: {}]">
            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                <thead>
                    <tr class="text-left">
                        <template v-for="heading in headings" :key="heading.key">
                            <th class="bg-gray-300 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-700 font-bold tracking-wider uppercase text-xs"
                                :ref="heading.key" :class="{ [heading.key]: true }">
                                {{ heading.value }}
                            </th>
                        </template>
                        <slot name="actionHeaders" />
                    </tr>
                </thead>

                <tbody>
                    <template v-for="row in filteredData" :key="row.id" v-if="data.length > 0">
                        <slot name="dataRow" :dataRow="row"/>
                    </template>
                    <template v-else>
                        <tr>
                            <td :colspan="headings.length" class="border-dashed border-t border-gray-200 text-center">
                                <span class="text-gray-700 px-6 py-3 block">
                                    There's no available data
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <nav v-if="pagination.total > data.length" aria-label="Pagination" class="pagination">
            <ul class="inline-flex -space-x-px shadow">
                <template v-for="link in pagination.links">
                    <li>
                        <span class="pagination-link stale" v-html="link.label" v-if="null === link.url"></span>
                        <a
                            :href="link.url"
                            class="pagination-link"
                            :class="{ 'current': link.active }"
                            v-html="link.label"
                            v-if="null !== link.url"
                            :title="'Page ' + link.label"
                            @click.prevent="updateCurrentPage(link.label)"
                        />
                    </li>
                </template>
            </ul>
        </nav>
    </div>
</template>
