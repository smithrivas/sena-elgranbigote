<h1 class="nombre-pagina">Recuperar contraseña</h1>
<p class="descripcion-pagina">A continuación escribe tu nueva contraseña:</p>

<?php    
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return; ?>

<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu nueva contraseña">
    </div>
    <input type="submit" class="boton" value="Guardar nueva contraseña">
</form>

<div class="acciones">
    <a href="/">¿Recordaste tu contraseña?, Click aquí para iniciar sesión</a>
    <a href='/crear-cuenta'>¿Aún no tienes una cuenta?, Crear cuenta</a>
</div>