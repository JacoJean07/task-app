<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #87cefa;">
    <div class="container-fluid justify-content-center">
        <ul class="nav nav-underline px-3">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">TaskAPP</a>
            </li>
        </ul>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex justify-content-center w-100">
                <ul class="nav nav-underline mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add.php">Nueva Tarea</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="toDoTasks.php">Por Hacer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pastTasks.php">Tareas Atrasadas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="completeTask.php">Tareas Acabadas</a>
                    </li>
                </ul>
                <ul class="nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" ><?= $_SESSION["user"]["userNAme"] ?></a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="logout.php">Cerrar Sesion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<main>
