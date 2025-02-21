const app = Vue.createApp({
    data() {
        return {
            currentView: 'login',
            isAuthenticated: false,
            user: null
        }
    },
    methods: {
        setView(view) {
            if (!this.isAuthenticated && view !== 'login') {
                this.currentView = 'login';
                return;
            }
            this.currentView = view;
        },
        handleLogin(userData) {
            this.user = userData;
            this.isAuthenticated = true;
            this.currentView = 'vehiculos';
            localStorage.setItem('token', userData.token);
        },
        logout() {
            this.user = null;
            this.isAuthenticated = false;
            this.currentView = 'login';
            localStorage.removeItem('token');
        },
        checkAuth() {
            const token = localStorage.getItem('token');
            if (token) {
                this.isAuthenticated = true;
                this.currentView = 'vehiculos';
            }
        }
    },
    mounted() {
        this.checkAuth();
    }
});

// Register components
app.component('login-view', Login);
app.component('vehiculos-view', Vehiculos);
app.component('retiradas-view', Retiradas);

// Mount the app
app.mount('#app');