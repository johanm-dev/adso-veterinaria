<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Nueva Consulta Médica</h2>
                <p class="text-muted small">Diligencie el acto médico veterinario (Validación de colisión de horario RN-03 activa)</p>
            </div>
            <a href="index.php?action=consultas" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="index.php?action=consultas_store" class="needs-validation" novalidate>
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
                            <label for="veterinario_id" class="form-label fw-semibold">Médico Veterinario <span class="text-danger">*</span></label>
                            <select class="form-select" id="veterinario_id" name="veterinario_id" required>
                                <option value="">Seleccione al profesional...</option>
                                <?php foreach ($veterinarios as $vet): ?>
                                    <option value="<?= $vet['id'] ?>">
                                        <?= e($vet['nombre']) ?> (Especialidad: <?= e($vet['especialidad'] ?? 'General') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Debe asignar un veterinario.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="fecha" class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="hora" class="form-label fw-semibold">Hora de Cita <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="hora" name="hora" required value="<?= date('H:i') ?>">
                            <div class="form-text small">RN-03: Evita colisiones de agenda.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="costo" class="form-label fw-semibold">Valor Consulta (COP)</label>
                            <input type="number" step="1000" class="form-control" id="costo" name="costo" value="50000">
                        </div>

                        <div class="col-12">
                            <label for="motivo" class="form-label fw-semibold">Motivo de Consulta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="motivo" name="motivo" required placeholder="Ej: Control de rutina, decaimiento, vómito recurrente">
                        </div>

                        <div class="col-md-12">
                            <label for="diagnostico" class="form-label fw-semibold">Diagnóstico Presuntivo / Definitivo</label>
                            <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" placeholder="Juicio clínico del médico tratante..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="proximo_control" class="form-label fw-semibold">Fecha de Próximo Control (Opcional)</label>
                            <input type="date" class="form-control" id="proximo_control" name="proximo_control">
                            <div class="form-text small">Generará alerta automática en el panel de control.</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?action=consultas" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Consulta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
