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
//obtenemos el id para trabajar con ese row
$id = $_GET["id"];
//preparamos la sentencia SQL
$statement = $conn->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = {$_SESSION['user']['id']} LIMIT 1");
$statement->execute([":id"=>$id]);

//COMPROBAMOS QUE EL ID EXISTA, EN CASO DE QUE EL USUARIO NO SEA UN NAVEGADOR, Y SI NO EXISTE EL ID MANDAMOS UN ERROR
if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo("HTTP 404 NOT FOUND");
  return;
}

//asignamos la tarea a una variable y usamos el metodo fetch para que se pueda leer en formato de array asociativo
$task = $statement->fetch(PDO::FETCH_ASSOC);

$error = null;  

//verificamos el metodo por el cual obtenemos los datos
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
    $statement = $conn->prepare("UPDATE tasks SET tasName = :name, tasDate = :date, tasPriority = :priority, tasDescription = :description, tasState = :estado WHERE id = :id");
    //sanitizamos los datos para evitar inyecciones SQL
    $statement->execute([
      ":id" => $id,
      ":name" => $_POST["name"],
      ":date" => $_POST["date"],
      ":priority" => $_POST["priority"],
      ":description" => $_POST["description"],
      ":estado" => $_POST["estado"],
    ]);
    //mensaje flash par edit
    $_SESSION["flash"] = ["message" => "Tarea: {$_POST['name']} edit."];

    //redirigimos a el index.php
    header("Location: index.php");
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
      <form method="POST" action="edit.php?id=<?= $task["id"]?>">
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Titulo</span>
          <input value="<?= $task["tasName"] ?>" type="text" class="form-control" placeholder="Tarea" id="name" name="name" required autocomplete="name" autofocus>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Fecha</span>
          <input value="<?= $task["tasDate"] ?>" type="date" class="form-control" id="date" name="date" required autocomplete="date" autofocus>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Prioridad</span>
          <select class="form-select" aria-label="priority" id="priority" name="priority" required autocomplete="priority" autofocus>
            <option value="1" <?= $task["tasPriority"] == 1 ? 'selected' : '' ?>>Alta</option>
            <option value="2" <?= $task["tasPriority"] == 2 ? 'selected' : '' ?>>Media</option>
            <option value="3" <?= $task["tasPriority"] == 3 ? 'selected' : '' ?>>Baja</option>
          </select>
        </div>


        <div class="input-group">
          <span class="input-group-text">Descripcion</span>
          <input value="<?= $task["tasDescription"] ?>" class="form-control" aria-label="description" id="description" name="description" required autocomplete="description" autofocus></input>
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
