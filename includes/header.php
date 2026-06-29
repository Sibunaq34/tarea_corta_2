<?php

$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<header class="nav-principal">
    <div class="nav-logo">
        <a href="index.php">Control de Tareas</a>
    </div>
    <nav>
        <ul class="nav-links">
            <li>
                <a href="tablero.php"
                   class="<?= $pagina_actual === 'tablero.php' ? 'activo' : '' ?>">
                    Tablero
                </a>
            </li>
            <li>
                <a href="tareas.php"
                   class="<?= $pagina_actual === 'tareas.php' ? 'activo' : '' ?>">
                    Tareas
                </a>
            </li>
            <li>
                <a href="responsables.php"
                   class="<?= $pagina_actual === 'responsables.php' ? 'activo' : '' ?>">
                    Responsables
                </a>
            </li>
            <li>
                <a href="grupos.php"
                   class="<?= $pagina_actual === 'grupos.php' ? 'activo' : '' ?>">
                    Grupos
                </a>
            </li>
        </ul>
    </nav>
</header>
