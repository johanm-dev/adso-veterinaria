<?php if (isset($_SESSION['usuario_id'])): ?>
    </div>
</main>

<footer class="bg-white border-top py-3 mt-auto">
    <div class="container-fluid px-4 text-center text-muted small">
        Veterinaria Digital &copy; <?= date('Y') ?> - ADSO SENA (Johan Muñoz / Instructora Joan Castillo) | Conectado a PostgreSQL 17
    </div>
</footer>
<?php endif; ?>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="public/js/validaciones.js"></script>
</body>
</html>
