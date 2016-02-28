<?php

class mySession {

    public static function boot() {
        session_start();
    }

    public static function get($var, $default) {
        return !empty($_SESSION[$var]) ? $_SESSION[$var] : $default;
    }

    public static function set($var, $valor) {
        $_SESSION[$var] = $valor;
    }

    public static function clear($var) {
        unset($_SESSION[$var]);
    }

}
