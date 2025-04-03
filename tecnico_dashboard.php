<?php
session_start();
// Verifica que el usuario sea técnico y esté autenticado
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'tecnico') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Técnico </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background-color:   #4CAF50;
            color: black;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px  #4CAF50;
        }
        .header .app-name {
            font-size: 1.5em;
            font-weight: bold;
            text-transform: uppercase;
            background: linear-gradient(to right, #333, #555);
            -webkit-background-clip: text;
            color: transparent;
        }
        .header .user-info {
            font-size: 1em;
            color: black;
            text-transform: uppercase;
        }

        /* Sidebar */
        .sidebar {
            background-color: #e0e0e0;
            width: 200px;
            padding: 20px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            margin-bottom: 20px;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 1.1em;
            display: flex;
            align-items: center;
        }
        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 1.2em;
            color: #555;
        }
        .sidebar ul li a:hover {
            color: #007bff;
        }

        /* Main content area */
        .main-content {
            margin-left: 200px;
            padding: 20px;
            flex: 1;
            background-color: white;
            min-height: calc(100vh - 60px - 50px); /* Resta header y footer */
        }

        /* Footer */
        .footer {
            background-color: #e0e0e0;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            bottom: 0;
            left: 200px;
            right: 0;
            box-shadow: 0 -2px 5px  #4CAF50;
        }
        .footer .icons {
            display: flex;
            gap: 25px;
        }
        .footer .icons a {
            text-decoration: none;
            font-size: 1.5em;
            transition: color 0.3s;
        }
        .footer .icons a:hover {
            color: #007bff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .main-content, .footer {
                margin-left: 0;
                left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="app-name"></div>
        <div class="user-info"><?php echo strtoupper($_SESSION['username']); ?> Tecnico</div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="tecnico_dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="servicio.php"><i class="fas fa-wrench"></i> Servicio</a></li>
            <li><a href="informes.php"><i class="fas fa-file-alt"></i> Informes</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
        </ul>
    </div>

    <!-- Main content area -->
    <div class="main-content">
        <h2>Bienvenido, <?php echo $_SESSION['username']; ?></h2>
        <p>Este es el panel para técnicos. Aquí puedes agregar contenido como listas de tareas, formularios o 
            información relevante.</p>
    </div>


</body>
</html>