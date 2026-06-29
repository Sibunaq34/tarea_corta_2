<div class="modal-fondo" id="modalEliminar" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalEliminarTitulo">
        <h2 id="modalEliminarTitulo">Confirmar eliminacion</h2>
        <p id="modalEliminarMensaje">Seguro que desea eliminarlo?</p>
        <div class="modal-acciones">
            <button type="button" class="boton boton-secundario" id="cancelarEliminar">No</button>
            <a href="#" class="boton boton-peligro" id="confirmarEliminar">Si</a>
        </div>
    </div>
</div>

<script>
    const modalEliminar = document.getElementById('modalEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const modalEliminarMensaje = document.getElementById('modalEliminarMensaje');

    document.querySelectorAll('[data-modal-eliminar]').forEach((enlace) => {
        enlace.addEventListener('click', (evento) => {
            evento.preventDefault();
            confirmarEliminar.href = enlace.href;
            modalEliminarMensaje.textContent = enlace.dataset.mensaje || 'Seguro que desea eliminarlo?';
            modalEliminar.classList.add('activo');
            modalEliminar.setAttribute('aria-hidden', 'false');
            cancelarEliminar.focus();
        });
    });

    function cerrarModalEliminar() {
        modalEliminar.classList.remove('activo');
        modalEliminar.setAttribute('aria-hidden', 'true');
        confirmarEliminar.href = '#';
    }

    cancelarEliminar.addEventListener('click', cerrarModalEliminar);

    modalEliminar.addEventListener('click', (evento) => {
        if (evento.target === modalEliminar) {
            cerrarModalEliminar();
        }
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && modalEliminar.classList.contains('activo')) {
            cerrarModalEliminar();
        }
    });
</script>

</div>


</body>

</html>
