<?php

require "database.php";

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //validamos que no se manden datos vacios
  if (empty($_POST["user"]) || empty($_POST["email"]) || empty($_POST["password"])) {
    $error = "POR FAVOR RELLENA TODOS LOS CAMPOS";
  } else if (!str_contains($_POST["email"], "@")) {
    $error = "Email format is incorrect."; 
  } else {
    //verificamos que no exista un email igual en la base de datos
    $statement = $conn->prepare("SELECT * FROM users WHERE userEmail = :email");
    $statement->bindParam(":email", $_POST["email"]);
    $statement->execute();
    //COMPROBAMOS QUE EL ID EXISTA, EN CASO DE QUE EL USUARIO NO SEA UN NAVEGADOR, Y SI NO EXISTE EL ID MANDAMOS UN ERROR
    if ($statement->rowCount() > 0) {
      $error = "This email is taken (este correo ya existe).";
    } else {
      //mandar los datos a la base de datos
      $statement = $conn->prepare("INSERT INTO users (userName, userEmail, userPassword) VALUES (:user, :email, :password)");
      //sanitizar valores para inyecciones sql y lo mandamos directo en el execute
      $statement->execute([
        ":user" => $_POST["user"],
        ":email" => $_POST["email"],
        //hash con la funcion password_hash y la libreria PASSWORD_BCRYPT
        ":password" => password_hash($_POST["password"], PASSWORD_BCRYPT),
      ]);

      //iniciamos secion con el usuario ya registrado
      //verificamos que el email ingresado ya existe
      $statement = $conn->prepare("SELECT * FROM users WHERE userEmail = :email LIMIT 1");
      $statement->bindParam(":email", $_POST["email"]);
      $statement->execute();
      //obtenemos los datos de usuario y asignamos a una variable user y lo pedimos en fetch assoc para que lo mande en un formato asociativo
      $user = $statement->fetch(PDO::FETCH_ASSOC);  
      //borramos por asi decir la contrasenia del usuario en la secion para que no almacene ese valor y por seguridad
      unset($user["password"]);
      //iniciamos una sesion la cual es una cookie que es como un hash almacenado en el pc usuario para que almacene compruebe el usuario, asi la manera  de acceder a la sesion es por medio de la cockie y si alguien intenta hackear necesita el hash para poder hacer peticiones al servidor en lugar de solo necesitas el id
      session_start();
      //asignamos el usuario que se logueo a la secion iniciada
      $_SESSION["user"] = $user;

      //redirige al home.php
      header("Location: home.php");
    }
  }
}

?>



<?php require("partials/header.php"); ?>

<div class="container pt-3 p-4">

  <div class="card">
    <div class="card-header text-center" style="background-color: #afeeee;">Register</div>
    <div class="card-body">
      <!-- si hay un error mandar un danger -->
      <?php if ($error): ?> 
        <p class="text-danger">
          <?= $error ?>
        </p>
      <?php endif ?>
      <form method="POST" action="register.php">
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Usuario</span>
          <input type="text" class="form-control" placeholder="Pepito123" id="user" name="user" required autocomplete="user" autofocus>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">Correo</span>
          <input type="email" class="form-control" id="email" name="email" placeholder="test@test.com" required autocomplete="email" autofocus>
        </div>

        <div class="input-group">
          <span class="input-group-text">Contraseña</span>
          <input type="password" class="form-control" aria-label="password" id="password" name="password" required autocomplete="password" autofocus></input>
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
