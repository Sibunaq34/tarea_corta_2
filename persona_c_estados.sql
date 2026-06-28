USE control_tareas;

DELIMITER $$

CREATE PROCEDURE CambiarEstadoTarea(
    IN p_id_tarea INT,
    IN p_nuevo_estado VARCHAR(30)
)
BEGIN
    IF p_nuevo_estado = 'Finalizada' THEN
        UPDATE tareas
        SET estado = p_nuevo_estado,
            fecha_finalizacion = NOW()
        WHERE id_tarea = p_id_tarea;
    ELSE
        UPDATE tareas
        SET estado = p_nuevo_estado,
            fecha_finalizacion = NULL
        WHERE id_tarea = p_id_tarea;
    END IF;
END$$

DELIMITER ;
