import axios from "axios";

export const apiClient = axios.create({
    withCredentials: true, // required to handle the CSRF token
});
