import { reactive } from "vue";
import apiClient from '../utils/api';

const useResources = () => {
    /**
     * Holds resources data and pagination information.
     */
    const resources = reactive({
        data: [],
        pagination: {},
    });

    const resourceData = reactive({
        data: {}
    });

    /**
     * Return a list of resources with pagination information.
     * @param {string} page
     * @returns {Promise<void>}
     */
    const getResources = async (page) => {
        const response = await apiClient.get(`/api/resources?page=${page}`);
        resources.data = response.data.data;
        resources.pagination = response.data.pagination;
    };

    /**
     * Create a new resource.
     *
     * @param {Object} data
     * @returns {Promise<AxiosResponse<any>>}
     */
    const createResource = async (data) => {
        const response = await apiClient.post('/api/resources', data);

        resourceData.data = response.data;
    }

    /**
     * Download a resource's file.
     *
     * @param resourceId
     * @returns {Promise<void>}
     */
    const downloadResource = async (resourceId) => {
        await apiClient.get(`/api/resources/${resourceId}/download`);
    }

    /**
     * Delete resource.
     *
     * @param {string} resourceId
     * @param {Object} data
     * @returns {Promise<AxiosResponse<any>>}
     */
    const updateResource = async (resourceId, data) => {
        return await apiClient.post(`/api/resources/${resourceId}`, data, {
            headers: {
                'content-type': 'multipart/form-data',
            }
        });
    }

    /**
     * Delete resource.
     *
     * @param {string[]} resourceId
     * @returns {Promise<AxiosResponse<any>>}
     */
    const deleteResources = async (resources) => {
        return await apiClient.delete(`/api/resources?resources=${resources.join(',')}`);
    }

    return { resources, resourceData, getResources, downloadResource, updateResource, deleteResources };
}

export default useResources;
