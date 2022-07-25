import { ref } from "vue";

const useResources = () => {
    /**
     * Holds resources data and pagination information.
     *
     */
    const resources = ref({
        data: [],
        pagination: {},
    });

    /**
     * Return a list of resources with pagination information.
     * @param {string} page
     * @returns {Promise<void>}
     */
    const getResources = async (page) => {
        const response = await axios.get(`/api/resources?page=${page}`);
        console.log('resource', response);
        resources.value = {
            data: response.data.data,
            pagination: response.data.pagination
        }
    };

    /**
     * Delete resource.
     *
     * @param {string} resourceId
     * @returns {Promise<AxiosResponse<any>>}
     */
    const deleteResource = async (resourceId) => {
        return await axios.delete(`/api/resources/${resourceId}`);
    }

    return { resources, getResources, deleteResource };
}

export default useResources;
