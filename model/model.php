<?php
class login_model{
    public function get_user_account($username,$password){
        global $db ;
        $query = $db->prepare("select * from members where username= :username and password= :password") ;
        $query->bindParam(":username",$username,PDO::PARAM_STR) ;
        $query->bindParam(":password",md5($password),PDO::PARAM_STR) ;
       $resualt = $query->execute() ;
       if (!$resualt) {return 'error in exec query' ;}
       if ($query->rowCount() == 1) {
           $row = $query->fetch() ;
           return  array(
            "username"=>$row['username'],
            "last_name"=>$row['last_name'],
            "access_group"=>$row['access_group']
        );
       }else{
           return false ;
       }

    }

    public function set_identifire($identifire,$user){
        global $db;
        $query = $db->prepare("UPDATE members set identifier=:identifier where username= :username ") ;
        $query->bindParam(":identifier",$identifire,PDO::PARAM_STR) ;
        $query->bindParam(":username",$user,PDO::PARAM_STR) ;
        $query->execute() ;
    }

    public function get_attempts($ip,$identifire){
        global $db ;
        $query = $db->prepare("SELECT attempts from login_attempts where ip= :ip and identifier=:identifier ") ;
        $query->bindParam(":ip",$ip,PDO::PARAM_STR) ;
        $query->bindParam(":identifier",$identifire,PDO::PARAM_STR) ;
        $resualt = $query->execute() ;
        if (!$resualt) {return 'false';} ;
        $row = $query->fetch() ;
        return $row['attempts'] ;
    }

    public function set_attempts($ip,$identifire){
        global $db ;
        $query = $db->prepare("INSERT INTO login_attempts VALUES(:ip,:identifier,1,'')") ;
        $query->bindParam(":ip",$ip,PDO::PARAM_STR);
        $query->bindParam(":identifier",$identifire,PDO::PARAM_STR);
        $query->execute() ;
    }

    public function update_attempts($ip,$identifire){
        global $db ;
        $query = $db->prepare("update  login_attempts set attempts = attempts+1 where  ip= :ip and identifier=:identifier") ;
        $query->bindParam(":ip",$ip,PDO::PARAM_STR) ;
        $query->bindParam(":identifier",$identifire,PDO::PARAM_STR) ;
        $query->execute() ;
    }

    public function check_login($user){
        global $db ;
        $query = $db->prepare("select * from members where username= :username") ;
        $query->bindParam(":username",$user,PDO::PARAM_STR) ;
        $resualt = $query->execute() ;
        if($resualt){
            $row = $query->fetch() ;
            return $row ;
        }else{
            return false ;
        }
    }
}
