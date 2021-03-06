<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Email {

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        // Crear el objeto de email
        $mail = new PHPMailer();

        // Configurar SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'd910a798db45e3';
        $mail->Password = '0909c36e7491be';
        $mail->SMTPSecure = 'tls';
        $mail->Port = '587';

        // Contenido del email
        $mail->setFrom('cuentas@uptask.com', 'UpTask');
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirma tu cuenta';

        // Habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . "</strong> Haz creado tu cuenta en UpTask, solo debes confirmarla presionando el siguiente enlace </p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost/auth/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviamos el email
        $mail->send();
    }

    public function enviarInstrucciones() {
        // Crear el objeto de email
        $mail = new PHPMailer();

        // Configurar SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'd910a798db45e3';
        $mail->Password = '0909c36e7491be';
        $mail->SMTPSecure = 'tls';
        $mail->Port = '587';

        // Contenido del email
        $mail->setFrom('cuentas@uptask.com', 'UpTask');
        $mail->addAddress($this->email);
        $mail->Subject = 'Reestablece tu cuenta';

        // Habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . "</strong> Parece que haz olvidado tu password de <strong>UpTask</strong>, puedes recuperarlo presionando el siguiente enlace </p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost/auth/reestablecer?token=" . $this->token . "'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste reestablecer tu password, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviamos el email
        $mail->send();
    }
}
