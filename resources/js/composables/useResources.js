import {ref} from "vue";
import {apiClient as axios} from '../utils/api';

const useResources = () => {
    /**
     * Holds resources data and pagination information.
     *
     */
    const resources = ref({
        data: [],
        pagination: {},
    });

    const resourceData = ref({});

    /**
     * Return a list of resources with pagination information.
     * @param {string} page
     * @returns {Promise<void>}
     */
    const getResources = async (page) => {
        const response = await axios.get(`/api/resources?page=${page}`);
        resources.value = {
            data: response.data.data,
            pagination: response.data.pagination
        }
    };

    /**
     * Create a new resource.
     *
     * @param {Object} data
     * @returns {Promise<AxiosResponse<any>>}
     */
    const createResource = async (data) => {
        const response = await axios.post('/api/resources', data);

        resourceData.value = response.data;
    }

    /**
     * Download a resource's file.
     *
     * @param resourceId
     * @returns {Promise<void>}
     */
    const downloadResource = async (resourceId) => {
        await axios.get(`/api/resources/${resourceId}/download`);
    }

    /**
     * Delete resource.
     *
     * @param {string} resourceId
     * @returns {Promise<AxiosResponse<any>>}
     */
    const deleteResource = async (resourceId) => {
        return await axios.delete(`/api/resources/${resourceId}`);
    }

    return { resources, getResources, downloadResource, deleteResource };
}

export default useResources;
