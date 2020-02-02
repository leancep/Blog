<?php
namespace Library;

class UserService {
  
  public function getAllUsers() {
    $fs = new \Library\FileStore('usuarios.data');
    $usuarios = $fs->read();
    return $usuarios;
  }
  
  public function userExists(string $user) {
    $usuarios = $this->getAllUsers();
    foreach($usuarios as $u) {
      if ($u == $user) {
        return true;
      }
    }
    return false;
  }
  
  public function saveUser(string $user) {
    $usuarios= $this->getAllUsers();
    if ($this->userExists($user)==False){
        $usuarios[]=$user;
        $add= new \Library\FileStore("usuarios.data");
        $add->save($usuarios);
        return True;
    }
        return False;
  }
}
