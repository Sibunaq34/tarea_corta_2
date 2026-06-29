<?php
// tablero.php — Vista Kanban principal con filtros (HU4 · Persona E)

require_once __DIR__ . '/app/Config/ConexionBD.php';

// ── Paso 1: Leer filtros desde $_GET ──────────────────────────
$filtro_estado       = trim($_GET['estado']        ?? '');
$filtro_prioridad    = trim($_GET['prioridad']     ?? '');
$filtro_responsable  = (int)($_GET['responsable_id'] ?? 0);
$filtro_fecha_limite = trim($_GET['fecha_limite']  ?? '');

// Convertir a NULL si vacío / cero (el SP acepta NULL como "sin filtro")
$p_estado       = $filtro_estado       ?: null;
$p_prioridad    = $filtro_prioridad    ?: null;
$p_responsable  = $filtro_responsable  ?: null;
$p_fecha_limite = $filtro_fecha_limite ?: null;

// ── Paso 2: Obtener tareas con sp_obtener_tareas_tablero ──────
$tareas      = [];
$error_tareas = '';
try {


    $conexion = ConexionBD::conectar();
    $stmt = $conexion->prepare('CALL sp_obtener_tareas_tablero(?, ?, ?, ?)');

    $stmt->bind_param("ssis", $p_estado, $p_prioridad, $p_responsable, $p_fecha_limite);

    $stmt->execute();
    $resultado = $stmt->get_result();
    $tareas = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

}
catch(Exception $e){

    $error_tareas = 'Error al cargar las tareas: '.$e->getMessage();

}

// ── Paso 4: Agrupar tareas por estado ─────────────────────────
$columnas = [
    'Pendiente'   => [],
    'En progreso' => [],
    'Bloqueada'   => [],
    'Finalizada'  => [],
];
foreach ($tareas as $tarea) {
    $est = $tarea['estado'] ?? 'Pendiente';
    if (array_key_exists($est, $columnas)) {
        $columnas[$est][] = $tarea;
    }
}

// Transiciones válidas por estado (sección 9 del CLAUDE.md)
$transiciones = [
    'Pendiente'   => ['En progreso'],
    'En progreso' => ['Pendiente', 'Bloqueada', 'Finalizada'],
    'Bloqueada'   => ['En progreso'],
    'Finalizada'  => ['En progreso'],
];

$hoy = date('Y-m-d');
?>

    
<?php require __DIR__ . "/app/Views/layout/header.php"; ?>



<main class="contenedor">

    <div class="pagina-header">
        <h1>Tablero de Tareas</h1>
    </div>

    <!-- ── Formulario de filtros (Paso 11) ── -->
    <form method="GET" action="tablero.php" class="formulario-filtros">

        <div class="filtros-grupo">
            <label for="estado">Estado</label>
            <select name="estado" id="estado">
                <option value="">Todos</option>
                <?php foreach (['Pendiente', 'En progreso', 'Bloqueada', 'Finalizada'] as $est): ?>
                <option value="<?= htmlspecialchars($est) ?>"
                    <?= $filtro_estado === $est ? 'selected' : '' ?>>
                    <?= htmlspecialchars($est) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filtros-grupo">
            <label for="prioridad">Prioridad</label>
            <select name="prioridad" id="prioridad">
                <option value="">Todas</option>
                <?php foreach (['Alta', 'Media', 'Baja'] as $pri): ?>
                <option value="<?= htmlspecialchars($pri) ?>"
                    <?= $filtro_prioridad === $pri ? 'selected' : '' ?>>
                    <?= htmlspecialchars($pri) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filtros-grupo">
            <label for="responsable_id">Responsable</label>
            <select name="responsable_id" id="responsable_id">
                <option value="">Todos</option>
                <?php foreach ($responsables as $resp): ?>
                <option value="<?= (int)$resp['id'] ?>"
                    <?= $filtro_responsable === (int)$resp['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($resp['nombre_completo'] ?? '') ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filtros-grupo">
            <label for="fecha_limite">Fecha límite</label>
            <input type="date" name="fecha_limite" id="fecha_limite"
                   value="<?= htmlspecialchars($filtro_fecha_limite) ?>">
        </div>

        <div class="filtros-acciones">
            <button type="submit" class="btn btn-primario">Filtrar</button>
            <a href="tablero.php" class="btn btn-secundario">Limpiar</a>
        </div>

    </form>

    <!-- Error de BD -->
    <?php if ($error_tareas): ?>
    <div class="alerta alerta-error"><?= htmlspecialchars($error_tareas) ?></div>
    <?php endif; ?>

    <!-- Notificación AJAX -->
    <div id="notificacion" class="notificacion oculto" aria-live="polite"></div>

    <!-- ── Tablero Kanban (Paso 7 → 8) ── -->
    <div class="kanban-tablero">

        <?php foreach ($columnas as $estado => $tarjetas): ?>
        <?php $clase = strtolower(str_replace(' ', '-', $estado)); ?>

        <div class="kanban-columna kanban-columna--<?= $clase ?>">

            <div class="kanban-columna-header">
                <span class="kanban-columna-titulo"><?= htmlspecialchars($estado) ?></span>
                <span class="kanban-columna-contador"><?= count($tarjetas) ?></span>
            </div>

            <div class="kanban-tarjetas">

                <?php if (empty($tarjetas)): ?>
                <div class="kanban-vacio">Sin tareas</div>
                <?php endif; ?>

                <?php foreach ($tarjetas as $t): ?>
                <?php
                    $prioridad_clase = strtolower($t['prioridad'] ?? 'baja');
                    $es_finalizada   = ($t['estado'] === 'Finalizada');
                    $vencida         = !$es_finalizada
                                       && !empty($t['fecha_limite'])
                                       && $t['fecha_limite'] < $hoy;
                    $botones         = $transiciones[$t['estado']] ?? [];
                ?>

                <div class="kanban-tarjeta kanban-tarjeta--<?= $prioridad_clase ?>">
                    <div class="tarjeta-cuerpo">

                        <!-- Detalle (tachado si Finalizada) -->
                        <p class="tarjeta-detalle <?= $es_finalizada ? 'tachado' : '' ?>">
                            <?= htmlspecialchars($t['detalle']) ?>
                        </p>

                        <!-- Metadatos -->
                        <div class="tarjeta-meta">

                            <span class="badge badge-<?= $prioridad_clase ?>">
                                <?= htmlspecialchars($t['prioridad']) ?>
                            </span>

                            <?php if (!empty($t['responsable_nombre'])): ?>
                            <span class="tarjeta-responsable" title="Responsable">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <?= htmlspecialchars($t['responsable_nombre']) ?>
                            </span>
                            <?php endif; ?>

                            <?php if (!empty($t['fecha_limite'])): ?>
                            <span class="tarjeta-fecha <?= $vencida ? 'fecha-vencida' : '' ?>"
                                  title="Fecha límite">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8"  y1="2" x2="8"  y2="6"/>
                                    <line x1="3"  y1="10" x2="21" y2="10"/>
                                </svg>
                                <?= htmlspecialchars($t['fecha_limite']) ?>
                            </span>
                            <?php endif; ?>

                            <?php if (!empty($t['grupo_nombre'])): ?>
                            <span class="tarjeta-grupo" title="Grupo">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                                <?= htmlspecialchars($t['grupo_nombre']) ?>
                            </span>
                            <?php endif; ?>

                        </div>

                        <!-- Botones de transición válidos (sección 9 CLAUDE.md) -->
                        <?php if (!empty($botones)): ?>
                        <div class="tarjeta-acciones">
                            <?php foreach ($botones as $nuevo_estado): ?>

                            <?php
                            $clase_btn = 'btn-estado--' . strtolower(str_replace(' ', '-', $nuevo_estado));

                            $label = ($t['estado'] === 'Finalizada')
                                ? 'Reactivar'
                                : '→ ' . $nuevo_estado;
                            ?>

                            <button 
                                type="button"
                                class="btn-mover <?= $clase_btn ?>"
                                data-tarea-id="<?= (int)$t['id_tarea'] ?>"
                                data-nuevo-estado="<?= htmlspecialchars($nuevo_estado) ?>">

                                <?= htmlspecialchars($label) ?>

                            </button>

                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>

                <?php endforeach; ?>

            </div>
        </div>

        <?php endforeach; ?>

    </div>
</main>

<!-- ── JS: fetch() al endpoint AJAX (Paso 10) ── -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const notificacion = document.getElementById('notificacion');

    function mostrarNotificacion(mensaje, tipo) {
        notificacion.textContent = mensaje;
        notificacion.className   = 'notificacion notificacion--' + tipo;
        setTimeout(function () {
            notificacion.className = 'notificacion oculto';
        }, 3000);
    }

    document.querySelectorAll('.btn-mover').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tareaId     = this.dataset.tareaId;
            var nuevoEstado = this.dataset.nuevoEstado;

            btn.disabled = true;

            fetch('ajax/cambiar_estado.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    'id_tarea='     + encodeURIComponent(tareaId)
                       + '&nuevo_estado=' + encodeURIComponent(nuevoEstado)
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.ok) {
                    mostrarNotificacion(data.mensaje, 'exito');
                    setTimeout(function () { location.reload(); }, 800);
                } else {
                    mostrarNotificacion(data.mensaje, 'error');
                    btn.disabled = false;
                }
            })
            .catch(function () {
                mostrarNotificacion('Error de conexión', 'error');
                btn.disabled = false;
            });
        });
    });

});
</script>

