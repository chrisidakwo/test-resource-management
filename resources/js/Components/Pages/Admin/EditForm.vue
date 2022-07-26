<template>
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="type" class="text-sm text-gray-500">Resource Type</label>
            <select
                id="type"
                class="form-select"
                v-model="type.value"
                :ref="type.ref"
                aria-disabled="true"
                disabled
            >
                <option value="html">HTML</option>
                <option value="link">Link</option>
                <option value="pdf">PDF</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="title" class="text-sm text-gray-500">Title</label>
            <input id="title"
                   class="form-input"
                   type="text"
                   v-model="title.value"
                   :ref="title.ref"
                   required="required"
                   :class="{'error': undefined !== title.error}"
            />
            <span v-if="title.error" class="text-sm text-red-600">{{ toSentenceCase(title.error.message) }}</span>
        </div>

        <div class="mb-4" v-if="type.value === 'link'">
            <label class="text-sm text-gray-500">Resource Link</label>
            <input class="form-input"
                   type="url"
                   v-model="link.value"
                   :ref="link.ref"
                   required="required"
                   :class="{'error': undefined !== link.error}"
            />
            <span v-if="link.error" class="text-sm text-red-600">{{ toSentenceCase(link.error.message) }}</span>
        </div>

        <div class="mb-4" v-if="type.value === 'link'">
            <div class="flex items-center mb-4">
                <input id="default-link_target" v-model="link_target.value" type="checkbox" class="form-checkbox">
                <label for="link_target" class="ml-2 font-medium text-gray-900">Open link in a new tab</label>
            </div>
        </div>

        <div class="mb-4" v-show="type.value === 'pdf'">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="pdf_file">Select PDF file</label>
            <input class="form-file" aria-describedby="pdf_help" id="pdf_file" type="file" @input="handleFileInput">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="pdf_help">Only PDFs allowed. File size should not exceed 3MB</p>
            <span v-if="file.error" class="text-sm text-red-600">{{ toSentenceCase(file.error.message) }}</span>
        </div>

        <div class="mb-4" v-if="type.value === 'html'">
            <label for="description" class="text-sm text-gray-500">Description</label>
            <textarea id="description"
                      class="form-input"
                      v-model="description.value"
                      :ref="description.ref"
                      rows="7"
                      :class="{'error': undefined !== title.error}"
            ></textarea>
            <span v-if="description.error" class="text-sm text-red-600">{{ toSentenceCase(description.error.message) }}</span>
        </div>

        <div class="mb-4" v-show="type.value === 'html'">
            <label class="text-sm text-gray-500">HTML Snippet</label>
            <textarea name="html_snippet"
                      id="froala"
                      v-model="html_snippet.value"
                      rows="7"
                      :class="{'error': undefined !== html_snippet.error}"
            ></textarea>
            <span v-if="html_snippet.error" class="text-sm text-red-600">{{ toSentenceCase(html_snippet.error.message) }}</span>
        </div>

        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-cyan-600 text-base font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:ml-3 sm:w-auto sm:text-sm">Update Resource</button>
            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="cancelUpdateResource">Cancel</button>
        </div>
    </form>
</template>

<script setup>
import {useForm} from "vue-hooks-form";
import {getImgURL, toSentenceCase} from "../../../utils";
import {onMounted, reactive, ref} from 'vue'
import FroalaEditor from "froala-editor/js/froala_editor.pkgd.min.js"
import {isValidUrl} from "../../../utils/validators";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['onCancel', 'onFormSubmit']);

const { useField, handleSubmit } = useForm({
    defaultValues: { ...props.data },
});

const title = useField('title', {
    rule: { required: true, min: 5 },
});

const type = useField('type', {
    rule: {
        required: true,
        validator: (rule, value) => {
            if (false === ['pdf', 'html', 'link'].includes(value)) {
                return new Error('Resource type can be either of: pdf, html, or link');
            }

            return true;
        }
    },
});

const description = useField('description', {
    rule: {
        validator: (rule, value) => {
            if (type.value === 'html' && value.length === 0) {
                return new Error('Please provide a description');
            }

            return true;
        }
    }
});

const html_snippet = useField('htmlSnippet', {
    rule: {
        validator: (rule, value) => {
            if (type.value === 'html' && value.length === 0) {
                return new Error('Please provide an HTML snippet');
            }

            return true;
        }
    }
});

const link = useField('link', {
    rule: {
        validator: (rule, value) => {
            if (type.value === 'link') {
                if (value.length === 0) {
                    return new Error('Please provide a link');
                }

                if (!isValidUrl(value)) {
                    return new Error('The provided link is not a valid URL');
                }
            }

            return true;
        }
    }
});

const link_target = ref(props.data.link_target === '_blank');

const file = useField('file', {
    rule: {
        validator: (rule, value) => {
            if (type.value === 'pdf' && typeof value !== 'object') {
                return new Error('Please select a PDF file');
            }

            return true;
        }
    }
})

const handleFileInput= ($event) => {
    file.value = $event.target.files[0];
}

const loadPDFile = (url) => {
    getImgURL(url, (imgBlob) => {
        let fileName = url.split('/').reverse()[0];

        let pdf = new File([imgBlob], fileName,{ type: "application/pdf" });

        let container = new DataTransfer();
        container.items.add(pdf);

        file.value = pdf;
        document.querySelector('#pdf_file').files = container.files;
    });
}

onMounted(() => {
    // Initialize
    initFroala();

    if (null !== props.data.file) {
        loadPDFile(props.data.file);
    }
})

// Cancel update resource
const cancelUpdateResource = () => {
    emit('onCancel');
}

const submitForm = () => {
    const data = {
        title: title.value,
        type: type.value,
    }

    if (type.value === 'html') {
        data.html_snippet = html_snippet.value;
        data.description = description.value;
    } else if (type.value === 'link') {
        data.link = link.value;

        if (undefined === link_target.value) {
            data.link_target = '_parent';
        } else {
            data.link_target = "_blank";
        }
    } else if (type.value === 'pdf') {
        data.file = file.value;
    }

    console.log('link_target', link_target.value);
    return;

    emit('onFormSubmit', data);
}

/**
 * Just for the sake of using a WYSIWYG editor.
 * Initialize Froala from the JS file. Default export from package is not compatible with Vue3.
 */
const initFroala = () => {
    new FroalaEditor("#froala", {
        inlineMode: false,
        pastePlain: true,
        paragraphy: false,
        quickInsertEnabled: false,
        toolbarButtons: {
            // Key represents the more button from the toolbar.
            moreText: {
                // List of buttons used in the  group.
                buttons: ['clearFormatting'],
                // Alignment of the group in the toolbar.
                align: 'left',
                // By default, 3 buttons are shown in the main toolbar. The rest of them are available when using the more button.
                buttonsVisible: 1
            },
            moreParagraph: {
                buttons: ['alignLeft', 'alignJustify', 'formatOLSimple', 'formatOL', 'formatUL'],
                align: 'left',
                buttonsVisible: 6
            },
            moreMisc: {
                buttons: ['undo', 'redo', 'html', 'selectAll'],
                align: 'right',
                buttonsVisible: 4
            }
        },
        // Change buttons for XS screen.
        // toolbarButtonsXS: [['undo', 'redo', 'html'], ['bold', 'italic', 'underline']],
        placeholderText: 'Enter HTML snippet',
        attribution: false,
        key: 'enter-your-license-key-here',
        disableRightClick: true,
        height: 130,
        fileUpload: false,
        imageUpload: false,
        imagePaste: false,
        imagePasteProcess: false,
        imageResize: false,
        crossDomain: false,
        events: {
            'keyup': function (inputEvent) {
                html_snippet.value = this.html.get()
            },
            'click': function (clickEvent) {
                html_snippet.value = this.html.get()
            },
            'commands.after': function (cmd, param1, param2) {
                html_snippet.value = this.html.get()
            },
            'paste.after': function (pasteEvent) {
                html_snippet.value = this.html.get()
            }
        }
    });
}
</script>

<style lang="css" scoped>
@import 'froala-editor/css/froala_editor.pkgd.min.css';
</style>

