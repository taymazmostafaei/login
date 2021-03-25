<?php
include_once("modules/login/model/model.php");
include_once("modules/login/view/view.php");
class login {
    public $model ;
    public $veiw ;
    public function __construct()
    {
        $this->model = new login_model() ;
        $this->veiw = new login_view() ;
    }
    
    public function is_login(){
        if (isset($_SESSION['login']) and $_SESSION['login'] == 1) {
            return true ;
        }else{
            return false ;
        }

    }
    public function do_login(){
        
    }
    public function display_form(){
        $this->veiw->login_render() ;
    }
}