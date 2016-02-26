<?php
/*
    Héctor Fabián Morales Ramírez
    Tecnólogo en Ingeniería de Sistemas
    Enero 2011
*/
class myPol extends myPlataformaPago{     
    public function realizarPago($pedido){        
        $cfg = new myConfig();
        $vars["gateway"] = $cfg->pol_gateway;
        $vars["usuario"] = $cfg->pol_id_usuario;
        $vars["numPedido"] = $pedido["id"];
        $vars["descripcion"] = $pedido["nombre"]." - ".$pedido["direccion"];
        $vars["valorPedido"] = sprintf("%01.2f", (double)$pedido["valor_total"]);// + (double)$pedido["cargo_envio"]);
        $vars["valorIva"] = sprintf("%01.2f", $pedido["valor_iva"]);
        $vars["valorSinIva"] = sprintf("%01.2f", $pedido["valor_base"]);
        $vars["firma"] = $this->generarFirma($pedido["id"], $pedido["valor_total"]);
        $vars["prueba"] = $cfg->pol_pruebas;
        $vars["url"] = JUri::root();
        $vars["componente"] = $this->componente;
		$vars["prefijo"] = $cfg->prefijo_pol;
        $this->tmplVars = $vars;
        
        $this->render("pol_envio.twig");
    }
    
    public function respuestaPago($get){        
		$cfg = new myConfig();        
        $ret = array();        
        $idUsuario = $get["usuario_id"];
        $respuesta = $get["estado_pol"];
        $detRespuesta = $get["codigo_respuesta_pol"];
        $idPedido = str_replace($cfg->prefijo_pol, "", $get["ref_venta"]);
        $valorPedido = $get["valor"];
        $trans = $get["ref_pol"];
        $moneda = $get["moneda"];
        $firma = strtolower($get["firma"]);
        
        $confFirma = $this->generarFirma($idPedido, $valorPedido, $idUsuario, $moneda, $respuesta);        
        if ($firma != $confFirma){
            $ret["error"] = "Firma no válida";
        }
        else{
            $ret["id_pedido"] = $idPedido;
            $ret["trans"] = $trans;
            
            $tipo = "";
            switch($respuesta){
                case 4:{
                    $ret["respuesta"] = "A";
                    $tipo = "mensaje";
                    break;
                }
                case 5:{
                    $ret["respuesta"] = "C";
                    $tipo = "error";
                    break;
                }
                case 6:
                case 8:
                case 9:{
                    $ret["respuesta"] = "R";
                    $tipo = "error";
                    break;
                }
                case 7:
                case 10:
                case 12:
                case 13:{
                    $ret["respuesta"] = "P";
                    $tipo = "alert";
                    break;
                }
            }
            
            $ret["mensaje"] = $this->getEstadoRespuesta($respuesta).": ".$this->getDetalleRespuesta($detRespuesta);            
        }
        
        return $ret;
    }
    
    public function confirmacionPago($get){
        return $this->respuestaPago($get);
    }
    
    private function getEstadoRespuesta($codigo){
        $ret = array(1 => "Sin abrir", 
                     2 => "Abierta", 
                     4 => "Pagada y abonada", 
                     5 => "Cancelada", 
                     6 => "Rechazada", 
                     7 => "En validación", 
                     8 => "Reversada", 
                     9 => "Reversada fraudulenta", 
                     10 =>"Enviada ent. Financiera", 
                     11 => "Capturando datos tarjeta de crédito", 
                     12 => "Esperando confirmación sistema PSE", 
                     13 => "Activa Débitos ACH", 
                     14 => "Confirmando pago Efecty", 
                     15 => "Impreso", 
                     16 => "Debito ACH Registrado");

        return $ret[$codigo];
    }
    
    private function getDetalleRespuesta($codigo){
        $ret = array(1 => "Transacción aprobada", 
                     2 => "Pago cancelado por el usuario",
                     3 => "Pago cancelado por el usuario durante validación",
                     4 => "Transacción rechazada por la entidad",
                     5 => "Transacción declinada por la entidad",
                     6 => "Fondos insuficientes",
                     7 => "Tarjeta invalida",
                     8 => "Acuda a su entidad",
                     9 => "Tarjeta vencida",
                     10 => "Tarjeta restringida",
                     11 => "Discrecional POL",
                     12 => "Fecha de expiración o campo seg. Inválidos",
                     13 => "Repita transacción",
                     14 => "Transacción inválida",
                     15 => "Transacción en proceso de validación",
                     16 => "Combinación usuario-contraseña inválidos",
                     17 => "Monto excede máximo permitido por entidad",
                     18 => "Documento de identificación inválido",
                     19 => "Transacción abandonada capturando datos TC",
                     20 => "Transacción abandonada",
                     21 => "Imposible reversar transacción",
                     22 => "Tarjeta no autorizada para realizar compras por internet.",
                     23 => "Transacción rechazada",
                     24 => "Transacción parcial aprobada",
                     25 => "Rechazada por no confirmación",
                     26 => "Comprobante generado, esperando pago en banco",
                     9994 => "Transacción pendiente por confirmar",
                     9995 => "Certificado digital no encontrado",
                     9996 => "Entidad no responde",
                     9997 => "Error de mensajería con la entidad financiera",
                     9998 => "Error en la entidad financiera",
                     9999 => "Error no especificado");
        return $ret[$codigo];
    }
    
    private function generarFirma($idPedido, $valorPedido, $idUsuario="", $moneda="", $estadoPol=""){
        //"llaveEncripcion~usuarioId~refVenta~valor~moneda"
        $cfg = new myConfig();
        
        if (!$idUsuario){
            $idUsuario = $cfg->pol_id_usuario;
        }
        
        if (!$moneda){
            $moneda = $cfg->moneda;
        }
				
        
        $pre = $cfg->pol_llave."~".$idUsuario."~".$cfg->prefijo_pol.$idPedido."~".sprintf("%01.2f", $valorPedido)."~".$moneda;
		if ($estadoPol){
			$pre .= "~".$estadoPol;
		}
		
        return md5($pre);
    }
}
?>