<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php
    // include_once __DIR__ . "/views/templates/alertas.php";
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Correo</label>
        <input type="email" id="email" placeholder="Tu correo" name="email" value="<?php echo s($auth->email); ?>">
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" placeholder="Tu contraseña" name="password">
    </div>
    <input type="submit" class="boton" value="Iniciar sesión">
</form>

<div class="acciones">
    <a href='/crear-cuenta'>¿Aún no tienes una cuenta?, Click aquí para crear una!</a>
    <a href='/olvide'>¿Olvidaste tu contraseña?</a>
</div>