<?php

require "database.php";
//llamar a la funcion sesion para identificar las sesiones
session_start();
//si la sesion no existe, mandar al login.php y dejar de ejecutar el resto; se puede hacer un required para ahorra codigo
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}
// USAREMOS EL METODO GET PARA BUSCAR EL ROW QUE VAMOS A ELIMINAR
$id = $_GET["id"];
//PRIMERO LO SOLICITAMOS A LA BASE DE DATOS
$statement = $conn->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = {$_SESSION['user']['id']}");
$statement->execute([":id" => $id]);
//COMPROBAMOS QUE EL ID EXISTA, EN CASO DE QUE EL USUARIO NO SEA UN NAVEGADOR, Y SI NO EXISTE EL ID MANDAMOS UN ERROR
if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo("HTTP 404 NOT FOUND");
  return;
}

//preparamos una sentencia SQL
$statement = $conn->prepare("UPDATE tasks SET tasState = 'Complete' WHERE id = :id");
//sanitizamos los datos para evitar inyecciones SQL
$statement->execute([":id" => $id]);
//mensaje flash de eliminar
$_SESSION["flash"] = ["message" => "Tarea Completada."];
//REDIRIGIMOS al home
header("Location: home.php");
//acabamos el codigo aqui porque ya nos redirige al home, y si dejamos que el codigo siga ejecutandose entonces no aparecera el mensaje flash
return;
?>
