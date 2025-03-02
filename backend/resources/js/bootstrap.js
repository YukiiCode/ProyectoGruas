import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Function to set auth token
const setAuthToken = (token) => {
    if (token) {
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    } else {
        delete window.axios.defaults.headers.common['Authorization'];
    }
};

// Set initial token if exists
setAuthToken(localStorage.getItem('token'));

// Add interceptor to handle 401 responses
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('token');
            setAuthToken(null);
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// Export the setAuthToken function
export { setAuthToken };
