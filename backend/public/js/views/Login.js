const Login = {
    template: `
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            <form @submit.prevent="login" class="login-form">
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input 
                        id="usuario" 
                        v-model="usuario" 
                        type="text" 
                        required 
                        placeholder="Ingrese su usuario"
                    />
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input 
                        id="password" 
                        v-model="password" 
                        type="password" 
                        required 
                        placeholder="Ingrese su contraseña"
                    />
                </div>
                <button type="submit" class="login-button">Iniciar Sesión</button>
                <p v-if="error" class="error">{{ error }}</p>
            </form>
        </div>
    `,
    data() {
        return {
            usuario: '',
            password: '',
            error: ''
        }
    },
    methods: {
        async login() {
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        usuario: this.usuario,
                        password: this.password
                    })
                });
                
                if (!response.ok) throw new Error('Login failed');
                
                const data = await response.json();
                localStorage.setItem('token', data.token);
                app.currentView = 'vehiculos-view';
                app.isAuthenticated = true;
            } catch (err) {
                this.error = 'Credenciales incorrectas';
            }
        }
    }
};