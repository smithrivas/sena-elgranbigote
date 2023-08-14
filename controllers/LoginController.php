<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){

        $alertas = [];

        $auth = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                
                if ($usuario) {
                    // Validar la contraseña
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre." ".$usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento según su rol
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                        
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }
    public static function logout(){
        session_start();

        $_SESSION = [];

        header('Location: /');
    }
    public static function olvide(Router $router){

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                if ($usuario && $usuario->confirmado === "1") {
                    // Generar token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Se han enviado instrucciones para restablecer la contraseña al correo registrado');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer la nueva contraseña y guardarla

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('Location: /');
                }                
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crear(Router $router){

        $usuario = new Usuario;

        // Alertas vacias
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que las alertas esten vacias
            if(empty($alertas)){
                // Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear la contraseña
                    $usuario->hashPassword();

                    // generar token de usuario
                    $usuario->crearToken();

                    // Enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crea el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('location: /mensaje');
                    }
                    
                    // debuguear($usuario);
                }
            }

        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::whereToken('token', $token);
        
        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Código de activación invalido');
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta activada exitosamente');
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}