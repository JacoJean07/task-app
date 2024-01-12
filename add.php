<?php 

require "database.php";

session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

//declaramos la variable error que nos ayudara a mostrar errores, etc.
$error = null;

//verificamos el metodo que usa el form con un if
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //validamos que no se manden datos vacios
  if (empty($_POST["name"]) || empty($_POST["date"]) || empty($_POST["priority"]) || empty($_POST["description"])) {
    $error = "POR FAVOR RELLENA TODOS LOS CAMPOS";
  } else {
    //sdeclaramos variables y las asignamos a los valores recibidos del input
    $name = $_POST["name"];
    $date = $_POST["date"];
    $priority = $_POST["priority"];
    $description = $_POST["description"];
    $estado = $_POST["estado"];

    //preparamos una sentencia SQL
    $statement = $conn->prepare("INSERT INTO tasks (user_id, tasName, tasDate, tasPriority, tasDescription, tasState) VALUES ({$_SESSION['user']['id']}, :name, :date, :priority, :description, :estado)");
    //sanitizamos los datos para evitar inyecciones SQL
    $statement->bindParam(":name", $_POST["name"]);
    $statement->bindParam(":date", $_POST["date"]);
    $statement->bindParam(":priority", $_POST["priority"]);
    $statement->bindParam(":description", $_POST["description"]);
    $statement->bindParam(":estado", $_POST["estado"]);
    //ejecutamos
    $statement->execute();
    //redirigimos a el home.php
    header("Location: home.php");
    return;
  }
}

?>

<?php require("./partials/header.php"); ?>


<div class="container pt-3 p-4">

  <div class="card">
    <div class="card-header text-center" style="background-color: #afeeee;">Nueva tarea</div>
    <div class="card-body">
      <!-- si hay un error mandar un danger -->
      <?php if ($error): ?> 
        <p class="text-danger">
          <?= $error ?>
        </p>
      <?php endif ?>
      <form method="POST" action="add.php">
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Titulo</span>
          <input type="text" class="form-control" placeholder="Tarea" id="name" name="name" required autocomplete="name" autofocus>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Fecha</span>
          <input type="date" class="form-control" id="date" name="date" required autocomplete="date" autofocus>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Prioridad</span>
          <select class="form-select" aria-label="priority" id="priority" name="priority" required autocomplete="priority" autofocus>
            <option value="1">Alta</option>
            <option value="2">Media</option>
            <option value="3">Baja</option>
          </select>
        </div>

        <div class="input-group">
          <span class="input-group-text">Descripcion</span>
          <textarea class="form-control" aria-label="description" id="description" name="description" required autocomplete="description" autofocus></textarea>
        </div>
        <div class="input-group">
          <input type="hidden" value="No Completado" class="form-control" id="estado" name="estado" required autocomplete="estado" autofocus>
        </div>
        <div class="mt-3 row">
          <div class="">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>

<?php require('partials/footer.php'); ?>
