<?php
include_once("modules/login/model/model.php");
include_once("modules/login/view/view.php");
class login {
    public $model ;
    public $veiw ;

    public $error_msg = '' ;
    public $requier_captcha = 0;
    private $allow_attemps = 2;

    public function __construct()
    {
        $this->model = new login_model() ;
        $this->veiw = new login_view() ;
    }
    
    public function is_login($u,$id){
        if (isset($u) and isset($id)) {
            $row = $this->model->check_login($u) ;
            if ($row) {
                $ip = $_SERVER['REMOTE_ADDR'] ;
                $browser = get_browser(null,true) ;
                $salt = 'aASDFASDF_dslfa46546546466' ;
                $identifier = md5($ip . $browser['browser'] . $browser['version'] . $salt) ;

                if ($id == $identifier and $id == $row['identifier']) {
                    $_SESSION['display_name'] = $row['last_name'] ;
                    $_SESSION['access_group'] = $row['access_group'];
                    $_SESSION['username'] = $row['username'];
                    return true;
                }
            }
        }

        if (isset($_SESSION['login']) and $_SESSION['login'] == 1) {
            return true ;
        }else{
            return false ;
        }
    }

    public function do_login($u,$p,$r,$s=null){
        $this->error_msg = '' ;
        if ($_SESSION['security_code'] and $s != $_SESSION['security_code']) {
            $this->error_msg = 'کد امنیتی اشتباه است.' ;
            $this->requier_captcha = 1;
            $this->display_login_form() ;
        }else{
            $user_account = $this->model->get_user_account($u,$p) ;
            if ($user_account) {
                $_SESSION['login'] = 1;
                $_SESSION['display_name'] = $user_account['last_name'] ;
                $_SESSION['access_group'] = $user_account['access_group'];
                $_SESSION['username'] = $user_account['username'];

                $ip = $_SERVER['REMOTE_ADDR'] ;
                $browser = get_browser(null,true) ;
                $salt = 'aASDFASDF_dslfa46546546466';
                $identifier = md5($ip.$browser['browser'].$browser['version'].$salt) ;

                setcookie('username',$u,time()+10*60,'/') ;
                setcookie('identifier',$identifier,time()+10*60,'/');

                $this->model->set_identifier($identifier,$u) ;
                header('location: '.ROOT.'index.php');
            }else{
                $ip = $_SERVER['REMOTE_ADDR'] ;
                $browser = get_browser(null,true) ;
                $salt = 'aASDFASDF_dslfa46546546466';
                $identifier = md5($ip.$browser['browser'].$browser['version'].$salt) ;

                $attempts = $this->model->get_attempts($ip,$identifier);
                if (!$attempts) {
                    $this->model->set_attempts($ip,$identifier) ;
                }else{
                    if ($attempts > $this->allow_attemps) {
                        $this->model->update_attempts($ip,$identifier);
                        $this->require_captcha = 1;
                    }else{
                        $this->model->update_attempts($ip,$identifier);
                        $this->require_captcha = 0;
                    }
                }
                $this->error_msg = 'نام کاربری یا کلمه عبور اشتباه است.';
                $this->display_login_form();
            }
        }
    }
    public function do_logout(){
        echo "ccccc" ;
        setcookie("username", null,time()-3600) ;
        setcookie("identifier", null,time()-3600);
        setcookie("username",null,time()-3600,"/");
        setcookie("identifier",null,time()-3600,"/");

        unset($_SESSION['login'],$_SESSION['display_name'],$_SESSION['security_code']);
        unset($_COOKIE['username'],$_COOKIE['identifier']);

        header('location: '.ROOT.'index.php');
    }
    public function display_login_form(){
        $this->view->error_msg = $this->error_msg;
        $this->view->require_captcha = $this->require_captcha;
        $this->view->render();
    }
}
