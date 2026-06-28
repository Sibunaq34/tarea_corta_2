-- =====================================================
-- BASE DE DATOS COMPLETA CON PROCEDIMIENTOS
-- =====================================================

DROP DATABASE IF EXISTS control_tareas;
CREATE DATABASE control_tareas;
USE control_tareas;

-- =====================================================
-- TABLAS
-- =====================================================

CREATE TABLE responsables (
    id_responsable INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    identificacion VARCHAR(30) NOT NULL
);

CREATE TABLE grupos (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE tareas (
    id_tarea INT AUTO_INCREMENT PRIMARY KEY,
    detalle TEXT NOT NULL,
    prioridad VARCHAR(20) NOT NULL,
    fecha_limite DATE NULL,
    estado VARCHAR(30) NOT NULL DEFAULT 'Pendiente',
    fecha_finalizacion DATETIME NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_responsable INT NULL,
    id_grupo INT NULL,

    CONSTRAINT fk_tareas_responsables
        FOREIGN KEY (id_responsable)
        REFERENCES responsables(id_responsable)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_tareas_grupos
        FOREIGN KEY (id_grupo)
        REFERENCES grupos(id_grupo)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS
-- =====================================================

DELIMITER //

-- =====================================================
-- 1. LISTAR TAREAS (ordenadas: pendientes primero, finalizadas al final)
-- =====================================================
CREATE PROCEDURE ListarTareas()
BEGIN
    SELECT 
        t.id_tarea,
        t.detalle,
        t.prioridad,
        t.fecha_limite,
        t.estado,
        t.fecha_finalizacion,
        CONCAT(r.nombre, ' ', r.apellidos) as responsable,
        COALESCE(r.id_responsable, 0) as id_responsable,
        CASE 
            WHEN t.estado = 'Finalizada' THEN 2
            WHEN t.estado = 'Bloqueada' THEN 1
            WHEN t.estado = 'En progreso' THEN 1
            ELSE 0
        END as orden_estado
    FROM tareas t
    LEFT JOIN responsables r
        ON t.id_responsable = r.id_responsable
    ORDER BY 
        CASE 
            WHEN t.estado = 'Finalizada' THEN 2
            ELSE 1
        END,
        t.id_tarea DESC;
END //

-- =====================================================
-- 2. OBTENER TAREA POR ID
-- =====================================================
CREATE PROCEDURE ObtenerTareaPorId(
    IN pIdTarea INT
)
BEGIN
    SELECT 
        t.id_tarea,
        t.detalle,
        t.prioridad,
        t.fecha_limite,
        t.estado,
        t.fecha_finalizacion,
        CONCAT(r.nombre, ' ', r.apellidos) as responsable,
        COALESCE(r.id_responsable, 0) as id_responsable,
        t.fecha_creacion
    FROM tareas t
    LEFT JOIN responsables r
        ON t.id_responsable = r.id_responsable
    WHERE t.id_tarea = pIdTarea;
END //

-- =====================================================
-- 3. CREAR TAREA
-- =====================================================
CREATE PROCEDURE CrearTarea(
    IN pDetalle TEXT,
    IN pPrioridad VARCHAR(20),
    IN pFechaLimite DATE,
    IN pIdResponsable INT
)
BEGIN
    INSERT INTO tareas (
        detalle,
        prioridad,
        fecha_limite,
        estado,
        id_responsable
    )
    VALUES (
        pDetalle,
        pPrioridad,
        pFechaLimite,
        'Pendiente',
        CASE WHEN pIdResponsable = 0 THEN NULL ELSE pIdResponsable END
    );
END //

-- =====================================================
-- 4. EDITAR TAREA (detalle, prioridad, fecha límite, responsable)
-- =====================================================
CREATE PROCEDURE EditarTarea(
    IN pIdTarea INT,
    IN pDetalle TEXT,
    IN pPrioridad VARCHAR(20),
    IN pFechaLimite DATE,
    IN pIdResponsable INT
)
BEGIN
    UPDATE tareas
    SET
        detalle = pDetalle,
        prioridad = pPrioridad,
        fecha_limite = pFechaLimite,
        id_responsable = CASE WHEN pIdResponsable = 0 THEN NULL ELSE pIdResponsable END
    WHERE id_tarea = pIdTarea;
END //

-- =====================================================
-- 5. ELIMINAR TAREA
-- =====================================================
CREATE PROCEDURE EliminarTarea(
    IN pIdTarea INT
)
BEGIN
    DELETE FROM tareas
    WHERE id_tarea = pIdTarea;
END //

-- =====================================================
-- 6. CAMBIAR ESTADO DE TAREA (con validación de transiciones)
-- =====================================================
CREATE PROCEDURE CambiarEstadoTarea(
    IN pIdTarea INT,
    IN pNuevoEstado VARCHAR(30)
)
BEGIN
    DECLARE vEstadoActual VARCHAR(30);
    DECLARE vEsValido BOOLEAN DEFAULT FALSE;
    
    -- Obtener estado actual
    SELECT estado INTO vEstadoActual FROM tareas WHERE id_tarea = pIdTarea;
    
    -- Validar transición de estado
    -- Pendiente ↔ En progreso
    IF (vEstadoActual = 'Pendiente' AND pNuevoEstado = 'En progreso') OR
       (vEstadoActual = 'En progreso' AND pNuevoEstado = 'Pendiente') THEN
        SET vEsValido = TRUE;
    -- En progreso ↔ Bloqueada
    ELSEIF (vEstadoActual = 'En progreso' AND pNuevoEstado = 'Bloqueada') OR
           (vEstadoActual = 'Bloqueada' AND pNuevoEstado = 'En progreso') THEN
        SET vEsValido = TRUE;
    -- En progreso → Finalizada
    ELSEIF vEstadoActual = 'En progreso' AND pNuevoEstado = 'Finalizada' THEN
        SET vEsValido = TRUE;
    END IF;
    
    -- Si la transición es válida, actualizar
    IF vEsValido THEN
        IF pNuevoEstado = 'Finalizada' THEN
            UPDATE tareas
            SET estado = pNuevoEstado, fecha_finalizacion = NOW()
            WHERE id_tarea = pIdTarea;
        ELSE
            UPDATE tareas
            SET estado = pNuevoEstado
            WHERE id_tarea = pIdTarea;
        END IF;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Transición de estado no permitida';
    END IF;
END //

-- =====================================================
-- 7. FINALIZAR TAREA (Pendiente/En progreso/Bloqueada → Finalizada)
-- =====================================================
CREATE PROCEDURE FinalizarTarea(
    IN pIdTarea INT
)
BEGIN
    DECLARE vEstadoActual VARCHAR(30);
    
    SELECT estado INTO vEstadoActual FROM tareas WHERE id_tarea = pIdTarea;
    
    -- Solo permite finalizar si está en En progreso
    IF vEstadoActual != 'En progreso' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo las tareas en progreso pueden ser finalizadas';
    END IF;
    
    UPDATE tareas
    SET estado = 'Finalizada', fecha_finalizacion = NOW()
    WHERE id_tarea = pIdTarea;
END //

-- =====================================================
-- 8. REACTIVAR TAREA (Finalizada → En progreso)
-- =====================================================
CREATE PROCEDURE ReactivarTarea(
    IN pIdTarea INT
)
BEGIN
    DECLARE vEstadoActual VARCHAR(30);
    
    SELECT estado INTO vEstadoActual FROM tareas WHERE id_tarea = pIdTarea;
    
    IF vEstadoActual != 'Finalizada' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo las tareas finalizadas pueden ser reactivadas';
    END IF;
    
    UPDATE tareas
    SET estado = 'En progreso', fecha_finalizacion = NULL
    WHERE id_tarea = pIdTarea;
END //

-- =====================================================
-- 9. OBTENER RESPONSABLES
-- =====================================================
CREATE PROCEDURE ObtenerResponsables()
BEGIN
    SELECT 
        id_responsable,
        CONCAT(nombre, ' ', apellidos) as nombre_completo,
        nombre,
        apellidos
    FROM responsables
    ORDER BY nombre, apellidos;
END //

DELIMITER ;

-- =====================================================
-- DATOS DE EJEMPLO
-- =====================================================

INSERT INTO responsables (nombre, apellidos, identificacion) VALUES
('Juan', 'Pérez García', '101020304'),
('María', 'García López', '202030405'),
('Carlos', 'López Martínez', '303040506');

INSERT INTO grupos (nombre) VALUES
('Desarrollo'),
('Diseño'),
('Testing');

INSERT INTO tareas (detalle, prioridad, fecha_limite, estado, id_responsable) VALUES
('Implementar login', 'Alta', '2026-07-01', 'En progreso', 1),
('Diseñar mockups', 'Media', '2026-06-30', 'Pendiente', 2),
('Realizar testing', 'Alta', '2026-07-05', 'Bloqueada', 3),
('Documentación', 'Baja', NULL, 'Pendiente', NULL);
