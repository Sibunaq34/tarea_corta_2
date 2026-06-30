DROP DATABASE IF EXISTS control_tareas;
CREATE DATABASE control_tareas;
USE control_tareas;

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
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
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

DELIMITER $$

DROP PROCEDURE IF EXISTS CambiarEstadoTarea$$
CREATE PROCEDURE CambiarEstadoTarea(
    IN pIdTarea INT,
    IN pNuevoEstado VARCHAR(30)
)
BEGIN
    DECLARE vEstadoActual VARCHAR(30);
    DECLARE vEsValido BOOLEAN DEFAULT FALSE;

    SELECT estado INTO vEstadoActual
    FROM tareas
    WHERE id_tarea = pIdTarea;

    IF (vEstadoActual = 'Pendiente' AND pNuevoEstado = 'En progreso') OR
       (vEstadoActual = 'En progreso' AND pNuevoEstado = 'Pendiente') OR
       (vEstadoActual = 'En progreso' AND pNuevoEstado = 'Bloqueada') OR
       (vEstadoActual = 'Bloqueada' AND pNuevoEstado = 'En progreso') OR
       (vEstadoActual = 'En progreso' AND pNuevoEstado = 'Finalizada') OR
       (vEstadoActual = 'Finalizada' AND pNuevoEstado = 'En progreso') THEN
        SET vEsValido = TRUE;
    END IF;

    IF vEsValido THEN
        IF pNuevoEstado = 'Finalizada' THEN
            UPDATE tareas
            SET estado = pNuevoEstado,
                fecha_finalizacion = NOW()
            WHERE id_tarea = pIdTarea;
        ELSE
            UPDATE tareas
            SET estado = pNuevoEstado,
                fecha_finalizacion = NULL
            WHERE id_tarea = pIdTarea;
        END IF;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Transicion de estado no permitida';
    END IF;
END$$

DROP PROCEDURE IF EXISTS CrearTarea$$
CREATE PROCEDURE CrearTarea(
    IN pDetalle TEXT,
    IN pPrioridad VARCHAR(20),
    IN pFechaLimite DATE,
    IN pIdResponsable INT,
    IN pIdGrupo INT
)
BEGIN
    INSERT INTO tareas (
        detalle,
        prioridad,
        fecha_limite,
        estado,
        id_responsable,
        id_grupo
    )
    VALUES (
        pDetalle,
        pPrioridad,
        pFechaLimite,
        'Pendiente',
        CASE WHEN pIdResponsable = 0 THEN NULL ELSE pIdResponsable END,
        CASE WHEN pIdGrupo = 0 THEN NULL ELSE pIdGrupo END
    );
END$$

DROP PROCEDURE IF EXISTS EditarTarea$$
CREATE PROCEDURE EditarTarea(
    IN pIdTarea INT,
    IN pDetalle TEXT,
    IN pPrioridad VARCHAR(20),
    IN pFechaLimite DATE,
    IN pIdResponsable INT,
    IN pIdGrupo INT
)
BEGIN
    UPDATE tareas
    SET detalle = pDetalle,
        prioridad = pPrioridad,
        fecha_limite = pFechaLimite,
        id_responsable = CASE WHEN pIdResponsable = 0 THEN NULL ELSE pIdResponsable END,
        id_grupo = CASE WHEN pIdGrupo = 0 THEN NULL ELSE pIdGrupo END
    WHERE id_tarea = pIdTarea;
END$$

DROP PROCEDURE IF EXISTS EliminarTarea$$
CREATE PROCEDURE EliminarTarea(
    IN pIdTarea INT
)
BEGIN
    DELETE FROM tareas
    WHERE id_tarea = pIdTarea;
END$$

DROP PROCEDURE IF EXISTS FinalizarTarea$$
CREATE PROCEDURE FinalizarTarea(
    IN pIdTarea INT
)
BEGIN
    DECLARE vEstadoActual VARCHAR(30);

    SELECT estado INTO vEstadoActual
    FROM tareas
    WHERE id_tarea = pIdTarea;

    IF vEstadoActual != 'En progreso' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo las tareas en progreso pueden ser finalizadas';
    END IF;

    UPDATE tareas
    SET estado = 'Finalizada',
        fecha_finalizacion = NOW()
    WHERE id_tarea = pIdTarea;
END$$

DROP PROCEDURE IF EXISTS ListarTareas$$
CREATE PROCEDURE ListarTareas()
BEGIN
    SELECT
        t.id_tarea,
        t.detalle,
        t.prioridad,
        t.fecha_limite,
        t.estado,
        t.fecha_finalizacion,
        CONCAT(r.nombre, ' ', r.apellidos) AS responsable,
        COALESCE(r.id_responsable, 0) AS id_responsable,
        t.id_grupo,
        g.nombre AS grupo_nombre,
        CASE
            WHEN t.estado = 'Finalizada' THEN 2
            WHEN t.estado = 'Bloqueada' THEN 1
            WHEN t.estado = 'En progreso' THEN 1
            ELSE 0
        END AS orden_estado
    FROM tareas t
    LEFT JOIN responsables r
        ON t.id_responsable = r.id_responsable
    LEFT JOIN grupos g
        ON t.id_grupo = g.id_grupo
    ORDER BY
        CASE
            WHEN t.estado = 'Finalizada' THEN 2
            ELSE 1
        END,
        t.id_tarea DESC;
END$$

DROP PROCEDURE IF EXISTS ObtenerResponsables$$
CREATE PROCEDURE ObtenerResponsables()
BEGIN
    SELECT
        id_responsable,
        CONCAT(nombre, ' ', apellidos) AS nombre_completo,
        nombre,
        apellidos
    FROM responsables
    ORDER BY nombre, apellidos;
END$$

DROP PROCEDURE IF EXISTS ObtenerTareaPorId$$
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
        CONCAT(r.nombre, ' ', r.apellidos) AS responsable,
        COALESCE(r.id_responsable, 0) AS id_responsable,
        t.id_grupo,
        g.nombre AS grupo_nombre,
        t.fecha_creacion
    FROM tareas t
    LEFT JOIN responsables r
        ON t.id_responsable = r.id_responsable
    LEFT JOIN grupos g
        ON t.id_grupo = g.id_grupo
    WHERE t.id_tarea = pIdTarea;
END$$

DROP PROCEDURE IF EXISTS ReactivarTarea$$
CREATE PROCEDURE ReactivarTarea(
    IN pIdTarea INT
)
BEGIN
    DECLARE vEstadoActual VARCHAR(30);

    SELECT estado INTO vEstadoActual
    FROM tareas
    WHERE id_tarea = pIdTarea;

    IF vEstadoActual != 'Finalizada' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Solo las tareas finalizadas pueden ser reactivadas';
    END IF;

    UPDATE tareas
    SET estado = 'En progreso',
        fecha_finalizacion = NULL
    WHERE id_tarea = pIdTarea;
END$$

DROP PROCEDURE IF EXISTS sp_obtener_tareas_tablero$$
CREATE PROCEDURE sp_obtener_tareas_tablero(
    IN p_estado VARCHAR(30),
    IN p_prioridad VARCHAR(20),
    IN p_responsable_id INT,
    IN p_fecha_limite DATE
)
BEGIN
    SELECT
        t.id_tarea,
        t.detalle,
        t.prioridad,
        t.estado,
        t.fecha_limite,
        t.fecha_finalizacion,
        t.id_grupo,
        g.nombre AS grupo_nombre,
        t.id_responsable,
        CASE
            WHEN r.id_responsable IS NULL THEN 'Sin responsable asignado'
            ELSE CONCAT(r.nombre, ' ', r.apellidos)
        END AS responsable_nombre
    FROM tareas t
    LEFT JOIN responsables r
        ON r.id_responsable = t.id_responsable
    LEFT JOIN grupos g
        ON g.id_grupo = t.id_grupo
    WHERE (p_estado IS NULL OR t.estado = p_estado)
      AND (p_prioridad IS NULL OR t.prioridad = p_prioridad)
      AND (p_responsable_id IS NULL OR t.id_responsable = p_responsable_id)
      AND (p_fecha_limite IS NULL OR t.fecha_limite = p_fecha_limite)
    ORDER BY
        FIELD(t.estado, 'Pendiente', 'En progreso', 'Bloqueada', 'Finalizada'),
        t.prioridad DESC,
        t.id_tarea ASC;
END$$

DELIMITER ;
