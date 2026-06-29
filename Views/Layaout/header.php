<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensaje = $_SESSION['mensaje'] ?? null;
$tipoMensaje = $_SESSION['tipo_mensaje'] ?? 'exito';
unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Sistema de tareas
    </title>


    <style>

        *{
            box-sizing:border-box;
        }

        body{
            font-family: Arial, sans-serif;
            margin:0;
            background:#f5f7fa;
            color:#222;
        }


        .container{
            width:min(100% - 32px, 1000px);
            margin:auto;
            padding:24px 0;
        }


        input, select, textarea, button{

            width:100%;
            padding:8px;
            margin-bottom:15px;
            border:1px solid #bbb;
            border-radius:4px;
            font:inherit;

        }

        button,
        .boton{
            display:inline-block;
            width:auto;
            min-width:90px;
            padding:9px 14px;
            margin:0;
            border:1px solid #2864c8;
            border-radius:4px;
            background:#2864c8;
            color:white;
            text-decoration:none;
            cursor:pointer;
        }

        .boton-secundario{
            border-color:#777;
            background:white;
            color:#222;
        }

        .boton-peligro{
            border-color:#b42318;
            background:#b42318;
        }

        .acciones a{
            margin-right:8px;
        }


        table{

            width:100%;
            border-collapse: collapse;
            background:white;

        }


        td,th{

            border:1px solid #ccc;
            padding:10px;

        }

        th{
            background:#eef2f7;
            text-align:left;
        }

        .tabla-responsive{
            width:100%;
            overflow-x:auto;
            border:1px solid #ddd;
            background:white;
        }

        nav{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            margin-bottom:20px;
        }

        nav a{
            display:inline-block;
            padding:8px 12px;
            border:1px solid #ccc;
            border-radius:4px;
            background:white;
            text-decoration:none;
            color:#222;
        }

        .paginacion{
            margin-top:20px;
        }

        .paginacion a,
        .paginacion strong{
            display:inline-block;
            padding:8px 10px;
            margin-right:4px;
            border:1px solid #ccc;
            text-decoration:none;
        }

        .paginacion strong{
            background:#eee;
        }

        .paginacion .deshabilitado{
            color:#999;
            border-color:#ddd;
        }

        .mensaje{
            padding:12px 14px;
            margin-bottom:18px;
            border-radius:4px;
            border:1px solid #badbcc;
            background:#d1e7dd;
            color:#0f5132;
        }

        .mensaje.error{
            border-color:#f5c2c7;
            background:#f8d7da;
            color:#842029;
        }

        .modal-fondo{
            position:fixed;
            inset:0;
            display:none;
            align-items:center;
            justify-content:center;
            padding:16px;
            background:rgba(0, 0, 0, 0.45);
            z-index:1000;
        }

        .modal-fondo.activo{
            display:flex;
        }

        .modal{
            width:min(100%, 420px);
            padding:22px;
            border-radius:6px;
            background:white;
            box-shadow:0 10px 30px rgba(0, 0, 0, 0.25);
        }

        .modal h2{
            margin:0 0 10px;
            font-size:22px;
        }

        .modal p{
            margin:0 0 20px;
        }

        .modal-acciones{
            display:flex;
            justify-content:flex-end;
            gap:10px;
            flex-wrap:wrap;
        }

        @media (max-width: 600px){
            .container{
                width:min(100% - 20px, 1000px);
                padding:16px 0;
            }

            h1{
                font-size:26px;
            }

            nav a,
            .boton{
                width:100%;
                text-align:center;
            }

            .acciones a{
                display:block;
                margin:0 0 8px;
            }

            .paginacion a,
            .paginacion strong{
                margin-bottom:6px;
            }
        }


    </style>

</head>


<body>


<div class="container">
<nav>
    <a href="/Tarea2/Views/responsables/index.php">Responsables</a>
    <a href="/Tarea2/Views/tareas/index.php">Tareas</a>
</nav>

<?php if ($mensaje): ?>
    <div class="mensaje <?php echo htmlspecialchars($tipoMensaje); ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>
