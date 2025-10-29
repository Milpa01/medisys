<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-pencil-square text-primary me-2"></i>
        Editar Expediente Médico
    </h2>
    <a href="<?= Router::url('expedientes/ver?id=' . ($expediente['id'] ?? '')) ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Cancelar
    </a>
</div>

<!-- Información del Paciente (Solo lectura) -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-vcard me-2"></i>Información del Paciente
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <?php if (!empty($expediente['imagen'])): ?>
                    <img src="<?= htmlspecialchars($expediente['imagen']) ?>" 
                         alt="Foto del paciente" 
                         class="rounded-circle mb-2" 
                         style="width: 120px; height: 120px; object-fit: cover;">
                <?php else: ?>
                    <div class="avatar-md bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <label class="text-muted small">Paciente</label>
                        <p class="mb-2">
                            <strong><?= htmlspecialchars(($expediente['nombre'] ?? '') . ' ' . ($expediente['apellidos'] ?? '')) ?></strong>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Código</label>
                        <p class="mb-2"><?= htmlspecialchars($expediente['codigo_paciente'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">N° Expediente</label>
                        <p class="mb-2"><strong class="text-primary"><?= htmlspecialchars($expediente['numero_expediente'] ?? 'N/A') ?></strong></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Edad</label>
                        <p class="mb-0"><?= $expediente['edad'] ?? 'N/A' ?> años</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Género</label>
                        <p class="mb-0">
                            <?php 
                            $generos = ['M' => 'Masculino', 'F' => 'Femenino', 'Otro' => 'Otro'];
                            echo $generos[$expediente['genero'] ?? 'Otro'] ?? 'No especificado';
                            ?>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Teléfono</label>
                        <p class="mb-0"><?= htmlspecialchars($expediente['telefono'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0"><?= htmlspecialchars($expediente['email'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulario de Edición -->
<form action="<?= Router::url('expedientes/actualizar') ?>" method="POST">
    <input type="hidden" name="id" value="<?= $expediente['id'] ?? '' ?>">
    
    <div class="row">
        <!-- Antecedentes -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-data me-2"></i>Antecedentes Médicos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="antecedentes_familiares" class="form-label">
                            <i class="bi bi-people me-1"></i>Antecedentes Familiares
                        </label>
                        <textarea class="form-control" id="antecedentes_familiares" name="antecedentes_familiares" 
                                  rows="4" placeholder="Enfermedades hereditarias, problemas de salud en la familia..."><?= htmlspecialchars($expediente['antecedentes_familiares'] ?? '') ?></textarea>
                        <div class="form-text">Ejemplo: Padre diabético, madre hipertensa, abuelo con problemas cardíacos</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="antecedentes_personales" class="form-label">
                            <i class="bi bi-person me-1"></i>Antecedentes Personales
                        </label>
                        <textarea class="form-control" id="antecedentes_personales" name="antecedentes_personales" 
                                  rows="4" placeholder="Enfermedades previas, hospitalizaciones, tratamientos..."><?= htmlspecialchars($expediente['antecedentes_personales'] ?? '') ?></textarea>
                        <div class="form-text">Ejemplo: Cirugía de apendicitis en 2015, asma desde la infancia</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="antecedentes_quirurgicos" class="form-label">
                            <i class="bi bi-bandaid me-1"></i>Antecedentes Quirúrgicos
                        </label>
                        <textarea class="form-control" id="antecedentes_quirurgicos" name="antecedentes_quirurgicos" 
                                  rows="4" placeholder="Cirugías realizadas, fecha y motivo..."><?= htmlspecialchars($expediente['antecedentes_quirurgicos'] ?? '') ?></textarea>
                        <div class="form-text">Ejemplo: Cesárea 2020, Apendicectomía 2015</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="antecedentes_alergicos" class="form-label">
                            <i class="bi bi-exclamation-triangle me-1 text-warning"></i>Antecedentes Alérgicos
                        </label>
                        <textarea class="form-control border-warning" id="antecedentes_alergicos" 
                                  name="antecedentes_alergicos" rows="3" 
                                  placeholder="Alergias a medicamentos, alimentos, u otras sustancias..."><?= htmlspecialchars($expediente['antecedentes_alergicos'] ?? '') ?></textarea>
                        <div class="form-text text-warning">
                            <strong>IMPORTANTE:</strong> Especificar claramente todas las alergias conocidas
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="vacunas" class="form-label">
                            <i class="bi bi-shield-check me-1"></i>Vacunas
                        </label>
                        <textarea class="form-control" id="vacunas" name="vacunas" rows="3" 
                                  placeholder="Esquema de vacunación, vacunas recibidas..."><?= htmlspecialchars($expediente['vacunas'] ?? '') ?></textarea>
                        <div class="form-text">Ejemplo: Esquema completo de vacunación infantil, Influenza 2024</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Datos Clínicos y Sociales -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse me-2"></i>Datos Clínicos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="grupo_sanguineo" class="form-label">
                                <i class="bi bi-droplet-fill text-danger me-1"></i>Grupo Sanguíneo
                            </label>
                            <select class="form-select" id="grupo_sanguineo" name="grupo_sanguineo">
                                <option value="">Seleccionar...</option>
                                <option value="A" <?= ($expediente['grupo_sanguineo'] ?? '') == 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= ($expediente['grupo_sanguineo'] ?? '') == 'B' ? 'selected' : '' ?>>B</option>
                                <option value="AB" <?= ($expediente['grupo_sanguineo'] ?? '') == 'AB' ? 'selected' : '' ?>>AB</option>
                                <option value="O" <?= ($expediente['grupo_sanguineo'] ?? '') == 'O' ? 'selected' : '' ?>>O</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="factor_rh" class="form-label">Factor RH</label>
                            <select class="form-select" id="factor_rh" name="factor_rh">
                                <option value="">Seleccionar...</option>
                                <option value="+" <?= ($expediente['factor_rh'] ?? '') == '+' ? 'selected' : '' ?>>Positivo (+)</option>
                                <option value="-" <?= ($expediente['factor_rh'] ?? '') == '-' ? 'selected' : '' ?>>Negativo (-)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="peso_actual" class="form-label">
                                <i class="bi bi-speedometer me-1"></i>Peso Actual (kg)
                            </label>
                            <input type="number" class="form-control" id="peso_actual" name="peso_actual" 
                                   step="0.01" min="0" max="500" placeholder="Ej: 70.5"
                                   value="<?= $expediente['peso_actual'] ?? '' ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="altura_actual" class="form-label">
                                <i class="bi bi-rulers me-1"></i>Altura Actual (cm)
                            </label>
                            <input type="number" class="form-control" id="altura_actual" name="altura_actual" 
                                   step="0.01" min="0" max="300" placeholder="Ej: 170"
                                   value="<?= $expediente['altura_actual'] ?? '' ?>">
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="alert alert-info" id="imcDisplay" style="display: none;">
                                <strong>IMC Calculado:</strong> <span id="imcValue"></span>
                                <span class="badge bg-primary ms-2" id="imcClasificacion"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Datos Sociales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="estado_civil" class="form-label">Estado Civil</label>
                        <select class="form-select" id="estado_civil" name="estado_civil">
                            <option value="">Seleccionar...</option>
                            <option value="soltero" <?= ($expediente['estado_civil'] ?? '') == 'soltero' ? 'selected' : '' ?>>Soltero(a)</option>
                            <option value="casado" <?= ($expediente['estado_civil'] ?? '') == 'casado' ? 'selected' : '' ?>>Casado(a)</option>
                            <option value="divorciado" <?= ($expediente['estado_civil'] ?? '') == 'divorciado' ? 'selected' : '' ?>>Divorciado(a)</option>
                            <option value="viudo" <?= ($expediente['estado_civil'] ?? '') == 'viudo' ? 'selected' : '' ?>>Viudo(a)</option>
                            <option value="union_libre" <?= ($expediente['estado_civil'] ?? '') == 'union_libre' ? 'selected' : '' ?>>Unión Libre</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ocupacion" class="form-label">Ocupación</label>
                        <input type="text" class="form-control" id="ocupacion" name="ocupacion" 
                               placeholder="Ej: Ingeniero, Profesor, Estudiante..."
                               value="<?= htmlspecialchars($expediente['ocupacion'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="escolaridad" class="form-label">Escolaridad</label>
                        <select class="form-select" id="escolaridad" name="escolaridad">
                            <option value="">Seleccionar...</option>
                            <option value="Ninguna" <?= ($expediente['escolaridad'] ?? '') == 'Ninguna' ? 'selected' : '' ?>>Ninguna</option>
                            <option value="Primaria" <?= ($expediente['escolaridad'] ?? '') == 'Primaria' ? 'selected' : '' ?>>Primaria</option>
                            <option value="Secundaria" <?= ($expediente['escolaridad'] ?? '') == 'Secundaria' ? 'selected' : '' ?>>Secundaria</option>
                            <option value="Bachillerato" <?= ($expediente['escolaridad'] ?? '') == 'Bachillerato' ? 'selected' : '' ?>>Bachillerato</option>
                            <option value="Técnico" <?= ($expediente['escolaridad'] ?? '') == 'Técnico' ? 'selected' : '' ?>>Técnico</option>
                            <option value="Universitaria" <?= ($expediente['escolaridad'] ?? '') == 'Universitaria' ? 'selected' : '' ?>>Universitaria</option>
                            <option value="Posgrado" <?= ($expediente['escolaridad'] ?? '') == 'Posgrado' ? 'selected' : '' ?>>Posgrado</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-left-text me-2"></i>Observaciones Generales
                    </h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="observaciones_generales" name="observaciones_generales" 
                              rows="5" placeholder="Cualquier información adicional relevante sobre el paciente..."><?= htmlspecialchars($expediente['observaciones_generales'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botones de Acción -->
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="<?= Router::url('expedientes/ver?id=' . ($expediente['id'] ?? '')) ?>" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="reset" class="btn btn-outline-warning">
            <i class="bi bi-arrow-clockwise me-2"></i>Restablecer
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Guardar Cambios
        </button>
    </div>
</form>

<script>
// Calcular IMC en tiempo real
function calcularIMC() {
    const peso = parseFloat(document.getElementById('peso_actual').value);
    const altura = parseFloat(document.getElementById('altura_actual').value);
    
    if (peso > 0 && altura > 0) {
        const alturaMetros = altura / 100;
        const imc = peso / (alturaMetros * alturaMetros);
        
        document.getElementById('imcValue').textContent = imc.toFixed(2);
        document.getElementById('imcDisplay').style.display = 'block';
        
        // Clasificación
        let clasificacion = '';
        let colorClase = 'bg-secondary';
        
        if (imc < 18.5) {
            clasificacion = 'Bajo peso';
            colorClase = 'bg-warning';
        } else if (imc < 25) {
            clasificacion = 'Peso normal';
            colorClase = 'bg-success';
        } else if (imc < 30) {
            clasificacion = 'Sobrepeso';
            colorClase = 'bg-warning';
        } else if (imc < 35) {
            clasificacion = 'Obesidad I';
            colorClase = 'bg-danger';
        } else if (imc < 40) {
            clasificacion = 'Obesidad II';
            colorClase = 'bg-danger';
        } else {
            clasificacion = 'Obesidad III';
            colorClase = 'bg-danger';
        }
        
        const badge = document.getElementById('imcClasificacion');
        badge.textContent = clasificacion;
        badge.className = 'badge ms-2 ' + colorClase;
    } else {
        document.getElementById('imcDisplay').style.display = 'none';
    }
}

document.getElementById('peso_actual').addEventListener('input', calcularIMC);
document.getElementById('altura_actual').addEventListener('input', calcularIMC);

// Calcular al cargar si hay valores
window.addEventListener('load', calcularIMC);

// Validación del formulario
document.querySelector('form').addEventListener('submit', function(e) {
    const peso = parseFloat(document.getElementById('peso_actual').value);
    const altura = parseFloat(document.getElementById('altura_actual').value);
    
    if (peso && (peso < 0 || peso > 500)) {
        e.preventDefault();
        alert('El peso debe estar entre 0 y 500 kg');
        return false;
    }
    
    if (altura && (altura < 0 || altura > 300)) {
        e.preventDefault();
        alert('La altura debe estar entre 0 y 300 cm');
        return false;
    }
});

// Confirmación de cambios
let formModificado = false;
document.querySelectorAll('input, textarea, select').forEach(function(elemento) {
    elemento.addEventListener('change', function() {
        formModificado = true;
    });
});

window.addEventListener('beforeunload', function(e) {
    if (formModificado) {
        e.preventDefault();
        e.returnValue = '';
    }
});

document.querySelector('form').addEventListener('submit', function() {
    formModificado = false;
});
</script>

<style>
.avatar-md {
    width: 120px;
    height: 120px;
}
</style>