<?php

class Pdfcontroller extends AppController {
    public function recibopago() {
        if(Auth::is_valid()){
            View::template(NULL);
        }else{
            Router::redirect("/");
        }
    }
}
