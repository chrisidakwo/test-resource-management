import axios from "axios";

const apiClient = axios.create({
    withCredentials: true, // required to handle the CSRF token
    headers: {
        'accept': 'application/json',
        'content-type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    }
});

apiClient.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default apiClient;
