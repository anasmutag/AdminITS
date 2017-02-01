<?php

class IndexController extends AppController {
    public function index() {
        if(Auth::is_valid()){
            Router::redirect("/management/administracion/");
        }
    }
}
