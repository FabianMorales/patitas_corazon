<?php

class homeController extends myController {

    public function index() {
        return myView::render("home.index", ["activo" => "inicio"]);
    }

    public function mision() {
        return myView::render("home.mision", ["activo" => "mision"]);
    }

    public function vision() {
        return myView::render("home.vision", ["activo" => "vision"]);
    }

    public function nosotros() {
        return myView::render("home.nosotros", ["activo" => "nosotros"]);
    }

    public function adoptaGato() {
        return myView::render("home.adopta_gato", ["activo" => "adopta_gato"]);
    }

    public function adoptaPerro() {
        return myView::render("home.adopta_perro", ["activo" => "adopta_perro"]);
    }

    public function contacto() {
        return myView::render("home.contacto", ["activo" => "contacto"]);
    }

}
