<?php
/*
    Héctor Fabián Morales Ramírez
    Tecnólogo en Ingeniería de Sistemas
    Enero 2011
*/
    class myConfig{
        var $driver = 'mysql';
        var $host = 'internal-db.s193808.gridserver.com';
        var $database = 'db193808_editorial_escar';
        var $username = 'db193808';
        var $password = 'W¿h4T$Up@?';
        var $charset = 'utf8';
        var $collation = 'utf8_unicode_ci';
        var $prefix = "sis_";
        
        var $urlSitio = '/patitas_corazon/';

        var $componenteCarrito = "carrito";
        var $componenteUsuarios = "com_my_users";        
        var $tmplColorBox = "colorbox02";
        var $redondeo = 0;
        var $moneda = "COP";
        var $vigenciaCarrito = 1440;
        var $correoAdmin = "fabian.morales@outlook.com";

        var $plataformaPagos = "myPol";		

        /* Configuración para pagos Online */		

        var $pol_id_usuario = "2";
        var $pol_llave = "1111111111111111";
        var $pol_gateway = "https://gateway2.pagosonline.net/apps/gateway/index.html";
        var $pol_pruebas = "1";
        var $prefijo_pol = "fabian831014_mmarin1_";

        var $porcIva = 16;
        var $gastosEnvio = 10000;
        var $valorProducto = 2000000;
        
        var $idClienteInstagram = '5db18bf1745341b1a46cd2827a149e3c';
    }
?>