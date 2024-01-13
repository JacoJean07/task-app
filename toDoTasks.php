<?php

require "database.php";
//usamos la funcion sesion start para iniciar sesion
session_start();

//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

//llamar los contactos de la base de datos y especificar que sean los que tengan el user_id de la funcion sesion_start
$tasks = $conn->query("SELECT * FROM tasks WHERE user_id = {$_SESSION['user']['id']} AND tasState = 'Uncomplete' AND DATE(tasDate) > CURDATE() ORDER BY tasPriority ASC");

?>



<?php require ('partials/header.php');?>


<div class="container pt-4 p-3">
  <div class="row">

    <!-- si el array asociativo $tasks no tiene nada dentro, entonces imprimir el siguiente div -->
    <?php if ($tasks->rowCount() == 0): ?>
      <div class= "col-md-4 mx-auto">
        <div class= "card card-body text-center">
          <p>No hay tareas a futuro por el momento</p>
          <a href="add.php">Agrega una!</a>
        </div>
      </div>
    <?php endif ?>


    <!-- sirve para hacer una targeta por cada valor que tenga el array asociativo $tasks -->
    <?php foreach ($tasks as $task): ?>
    <div class="col-md-4 mb-3">
      <div class="card">
      <div class="card-header d-flex" style="background-color: #afeeee;">
        <h5 class="me-auto"> <?= $task["tasName"]?> </h5>
        <a href="delete.php?id=<?= $task["id"]?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
          </svg>
        </a>
      </div>
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-body-secondary"> <?= $task["tasDate"]?> </h6>
          <p class="card-subtitle mb-2 text-body-secondary">Prioridad: 
            <!-- determinar la prioridad segun el valor -->
            <?php if ($task["tasPriority"] == 1) : ?>
              Alta
            <?php elseif ($task["tasPriority"] == 2) : ?>
              Media
            <?php else : ?>
              Baja
            <?php endif ?>
          </p>
          <h4 class="card-text"> <?= $task["tasDescription"]?></h4>
          <a href="edit.php?id=<?= $task["id"]?>" class="btn btn-primary">Editar Tarea</a>
          <a href="complete.php?id=<?= $task["id"]?>" class="btn btn-info">Completada!</a>
        </div>
      </div>
    </div>
    <?php endforeach ?>

  </div>
</div>




<?php
require ('partials/footer.php');
?>
