<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Aplicación de Biológico / Vacuna</h2>
                <p class="text-muted small">Cálculo de próxima dosis automático según intervalo (Regla RN-01)</p>
            </div>
            <a href="index.php?action=vacunas" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="index.php?action=vacunas_store" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="mascota_id" class="form-label fw-semibold">Paciente (Mascota) <span class="text-danger">*</span></label>
                            <select class="form-select" id="mascota_id" name="mascota_id" required>
                                <option value="">Seleccione el paciente...</option>
                                <?php foreach ($mascotas as $m): ?>
                                    <option value="<?= $m['id'] ?>" <?= ($mascotaSeleccionada === (int)$m['id']) ? 'selected' : '' ?>>
                                        <?= e($m['nombre']) ?> (<?= e($m['especie']) ?> - Dueño: <?= e($m['propietario_nombre']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Debe seleccionar una mascota.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="vacuna_id" class="form-label fw-semibold">Biológico / Vacuna <span class="text-danger">*</span></label>
                            <select class="form-select" id="vacuna_id" name="vacuna_id" required>
                                <option value="">Seleccione la vacuna...</option>
                                <?php foreach ($vacunas as $v): ?>
                                    <option value="<?= $v['id'] ?>" data-intervalo="<?= $v['intervalo_dias'] ?>">
                                        <?= e($v['nombre']) ?> (<?= e($v['especie']) ?> - Intervalo: <?= $v['intervalo_dias'] ?> días)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Debe seleccionar una vacuna.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="veterinario_id" class="form-label fw-semibold">Profesional Aplicante <span class="text-danger">*</span></label>
                            <select class="form-select" id="veterinario_id" name="veterinario_id" required>
                                <option value="">Seleccione al veterinario...</option>
                                <?php foreach ($veterinarios as $vet): ?>
                                    <option value="<?= $vet['id'] ?>"><?= e($vet['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Debe seleccionar al veterinario responsable.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_aplicacion" class="form-label fw-semibold">Fecha de Aplicación <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_aplicacion" name="fecha_aplicacion" required value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="lote" class="form-label fw-semibold">Número de Lote</label>
                            <input type="text" class="form-control" id="lote" name="lote" placeholder="Ej: LOT-2026-X8">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Próxima Dosis (Estimada)</label>
                            <input type="text" class="form-control bg-light" id="previewProximaDosis" value="Se calculará automáticamente según la vacuna" readonly>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?action=vacunas" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fa-solid fa-syringe me-1"></i> Aplicar y Programar Refuerzo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const vacunaSelect = document.getElementById('vacuna_id');
    const fechaInput = document.getElementById('fecha_aplicacion');
    const previewInput = document.getElementById('previewProximaDosis');

    function actualizarPreview() {
        const option = vacunaSelect.options[vacunaSelect.selectedIndex];
        const intervalo = parseInt(option.getAttribute('data-intervalo') || '0', 10);
        const fechaBase = fechaInput.value;

        if (intervalo > 0 && fechaBase) {
            const date = new Date(fechaBase + "T00:00:00");
            date.setDate(date.getDate() + intervalo);
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            previewInput.value = `${yyyy}-${mm}-${dd} (en ${intervalo} días - Regla RN-01)`;
        } else {
            previewInput.value = "Se calculará automáticamente según la vacuna";
        }
    }

    vacunaSelect.addEventListener('change', actualizarPreview);
    fechaInput.addEventListener('change', actualizarPreview);
});
</script>
