<template>
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-info">
        <span>Sistema de Gestión de Grúas</span>
        <span>|</span>
        <a href="mailto:contacto@gruas.com">contacto@gruas.com</a>
      </div>
      <div class="footer-bottom">
        <p>&copy; {{ currentYear }} Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer',
  computed: {
    currentYear() {
      return new Date().getFullYear();
    }
  }
};
</script>

<style scoped>
.footer {
  background-color: var(--primary-color);
  color: white;
  padding: 1rem 0;
  margin-top: auto;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  text-align: center;
}

.footer-info {
  margin-bottom: 0.5rem;
}

.footer-info span {
  margin: 0 0.5rem;
}

.footer-info a {
  color: white;
  text-decoration: none;
}

.footer-info a:hover {
  text-decoration: underline;
}

.footer-bottom p {
  margin: 0;
  font-size: 0.9rem;
  opacity: 0.8;
}
</style>