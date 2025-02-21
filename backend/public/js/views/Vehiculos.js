const Vehiculos = {
    template: `
        <div class="container">
            <div class="filter-container">
                <input 
                    v-model="filtro" 
                    type="text" 
                    placeholder="Filtrar por matrícula..." 
                    class="form-control"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Color</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="vehiculo in vehiculosFiltrados" :key="vehiculo.id">
                        <td>{{ vehiculo.matricula }}</td>
                        <td>{{ vehiculo.marca }}</td>
                        <td>{{ vehiculo.modelo }}</td>
                        <td>{{ vehiculo.color }}</td>
                        <td>{{ vehiculo.tipovehiculo }}</td>
                        <td>{{ vehiculo.estado }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `,
    data() {
        return {
            vehiculos: [],
            filtro: '',
            loading: false
        }
    },
    computed: {
        vehiculosFiltrados() {
            if (!Array.isArray(this.vehiculos)) return [];
            return this.vehiculos.filter(vehiculo =>
                vehiculo.matricula.toLowerCase().includes(this.filtro.toLowerCase())
            );
        }
    },
    mounted() {
        this.cargarVehiculos();
    },
    methods: {
        async cargarVehiculos() {
            this.loading = true;
            try {
                const response = await fetch('/api/vehiculos', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                const data = await response.json();
                this.vehiculos = data;
            } catch (error) {
                console.error('Error al cargar vehículos:', error);
                this.vehiculos = [];
            } finally {
                this.loading = false;
            }
        }
    }
};