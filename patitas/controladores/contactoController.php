<?php

class contactoController extends myController{
    public function index(){
        return myView::render("cita.form");
    }
    
    public function enviarCorreoContacto(){
        $contacto = array();
        $contacto["nombre"] = myApp::getRequest()->getVar("nombre_contacto");
        $contacto["email"] = myApp::getRequest()->getVar("email_contacto");
        $contacto["mensaje"] = myApp::getRequest()->getVar("mensaje_contacto");
        $contacto["asunto"] = 'Solicitud de contacto';
        $contacto["fecha"] = date('Y-m-d H:i:s');
        $contacto["url"] = myApp::getUrlRoot();
        
        if (empty($contacto["nombre"])){
            return "Debe ingresar su nombre.";
        }

        if (empty($contacto["email"])){
            return "Debe ingresar su direccion de correo.";
        }
        
        if (empty($contacto["mensaje"])){
            return "Debe ingresar su un mensaje.";
        }
        
        $mail = new PHPMailer;

        //$mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'desarrollo@encubo.ws';
        $mail->Password = 'marisol2009';
        $mail->SMTPSecure = 'tls';

        $mail->From = 'info@editorialescar.com';
        $mail->FromName = 'Contacto Editorial Escar';
        $mail->addAddress('info@editorialescar.com', 'Contacto Editorial Escar');
        //$mail->addReplyTo('info@example.com', 'Information');
        $mail->addBCC('desarrollo@encubo.ws', 'Desarrollo');
        $mail->addBCC('gerencia@andresmesa.co', 'Andres');

        $mail->WordWrap = 50;
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
        $mail->isHTML(true);

        $mail->Subject = $contacto["asunto"];
        $mail->Body    = myView::render("contacto.correo", array("contacto" => $contacto));
        
        if(!$mail->send()) {
            return 'No se pudo enviar el mensaje. Intente nuevamente.';
        } 
        else {
            return 'Su correo ha sido enviado satisfactoriamente, en breve nos pondremos en contacto';
        }
    }
    
    public function enviarCorreoSuscripcion(){
        $contacto = array();        
        $contacto["email"] = myApp::getRequest()->getVar("email");
        $contacto["asunto"] = 'Suscripcion Comunidad editorial';
        $contacto["fecha"] = date('Y-m-d H:i:s');
        $contacto["url"] = myApp::getUrlRoot();

        if (empty($contacto["email"])){
            return "Debe ingresar su direccion de correo.";
        }

        $mail = new PHPMailer;

        //$mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'desarrollo@encubo.ws';
        $mail->Password = 'marisol2009';
        $mail->SMTPSecure = 'tls';

        $mail->From = 'info@editorialescar.com';
        $mail->FromName = 'Contacto Editorial Escar';
        $mail->addAddress('info@editorialescar.com', 'Contacto Editorial Escar');
        //$mail->addReplyTo('info@example.com', 'Information');
        $mail->addBCC('desarrollo@encubo.ws', 'Desarrollo');
        $mail->addBCC('gerencia@andresmesa.co', 'Andres');

        $mail->WordWrap = 50;
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
        $mail->isHTML(true);

        $mail->Subject = $contacto["asunto"];
        $mail->Body    = myView::render("contacto.correo", array("contacto" => $contacto));
        
        if(!$mail->send()) {
            return 'No se pudo enviar el mensaje. Intente nuevamente.';
        } 
        else {
            return 'Se ha suscrito exitosamente';
        }
    }
}