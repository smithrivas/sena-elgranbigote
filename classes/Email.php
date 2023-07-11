<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token){
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'b019ff5aee9b07';
        $mail->Password = '60b4ac6bcb069a';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com','AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>".$this->nombre."</strong> has creado tu cuenta en la barberia <strong>El gran bigote,</strong> solo debes confirmarla presionando el siguiente enlace:</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=".$this->token."'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el correo
        $mail->send();
    }

    public function enviarInstrucciones(){
         // Crear el objeto de email
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = 'sandbox.smtp.mailtrap.io';
         $mail->SMTPAuth = true;
         $mail->Port = 2525;
         $mail->Username = 'b019ff5aee9b07';
         $mail->Password = '60b4ac6bcb069a';
 
         $mail->setFrom('cuentas@appsalon.com');
         $mail->addAddress('cuentas@appsalon.com','AppSalon.com');
         $mail->Subject = 'Restablece tu contraseña';
 
         // Set HTML
         $mail->isHTML(TRUE);
         $mail->CharSet = 'UTF-8';
 
         $contenido = "<html>";
         $contenido .= "<p>Hola <strong>".$this->nombre."</strong> has solicitado restablecer tu contraseña, da click en el siguiente enlace para crear una nueva:</p>";
         $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=".$this->token."'>Restablecer contraseña</a></p>";
         $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
         $contenido .= "</html>";
 
         $mail->Body = $contenido;
 
         // Enviar el correo
         $mail->send();
    }
}