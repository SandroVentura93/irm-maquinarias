<template>
  <div class="container mt-4">
    <h2>🛠️ Gestión de Productos</h2>

    <!-- Buscador -->
    <input v-model="busqueda" @input="cargarProductos" class="form-control mb-3" placeholder="Buscar por código, parte o descripción" />

    <!-- Tabla -->
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Código</th>
          <th>Descripción</th>
          <th>Stock</th>
          <th>Precio</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in productos" :key="p.id">
          <td>{{ p.codigo }}</td>
          <td>{{ p.descripcion }}</td>
          <td :class="{ 'text-danger': p.stock_actual <= Math.min(p.stock_minimo, 5) }">
            {{ p.stock_actual }}
          </td>
          <td>S/ {{ p.precio_unitario }}</td>
          <td>
            <button class="btn btn-sm btn-warning me-1" @click="editar(p)">Editar</button>
            <button class="btn btn-sm btn-danger" @click="eliminar(p.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Formulario -->
    <div class="card mt-4">
      <div class="card-header">{{ producto.id ? 'Editar Producto' : 'Nuevo Producto' }}</div>
      <div class="card-body">
        <form @submit.prevent="guardar">
          <div class="mb-2">
            <label>Código</label>
            <input v-model="producto.codigo" class="form-control" required />
          </div>
          <div class="mb-2">
            <label>Descripción</label>
            <input v-model="producto.descripcion" class="form-control" required />
          </div>
          <div class="mb-2">
            <label>Precio Unitario</label>
            <input v-model.number="producto.precio_unitario" type="number" class="form-control" required />
          </div>
          <div class="mb-2">
            <label>Stock Mínimo</label>
            <input v-model.number="producto.stock_minimo" type="number" class="form-control" />
          </div>
          <button class="btn btn-primary" type="submit">Guardar</button>
          <button class="btn btn-secondary ms-2" type="button" @click="reset">Cancelar</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      productos: [],
      producto: {
        id: null,
        codigo: '',
        descripcion: '',
        precio_unitario: 0,
        stock_minimo: 0
      },
      busqueda: ''
    };
  },
  methods: {
    cargarProductos() {
      axios.get('/api/productos', { params: { q: this.busqueda } })
        .then(res => this.productos = res.data.data);
    },
    editar(p) {
      this.producto = { ...p };
    },
    eliminar(id) {
      if (confirm('¿Eliminar este producto?')) {
        axios.delete(`/api/productos/${id}`).then(() => this.cargarProductos());
      }
    },
    guardar() {
      const metodo = this.producto.id ? 'put' : 'post';
      const url = this.producto.id ? `/api/productos/${this.producto.id}` : '/api/productos';

      axios[metodo](url, this.producto)
        .then(() => {
          this.reset();
          this.cargarProductos();
        });
    },
    reset() {
      this.producto = {
        id: null,
        codigo: '',
        descripcion: '',
        precio_unitario: 0,
        stock_minimo: 0
      };
    }
  },
  mounted() {
    this.cargarProductos();
  }
};
</script>

<style scoped>
.text-danger {
  font-weight: bold;
}
</style>
