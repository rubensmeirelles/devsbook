<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;

class ConfigController extends Controller {

  private $loggedUser;

    public function __construct(){
        $this->loggedUser = UserHandler::checkLogin(true);
        if($this->loggedUser === false) {
          $this->redirect('/login');     
      }
    }

    public function index() {
      $user = UserHandler::getuser($this->loggedUser->id);

      $flash = '';
      if(!empty($_SESSION['flash'])) {
          $flash = $_SESSION['flash'];
          $_SESSION['flash'] = '';
      }
      
      $this->render('profile_config', [
        'loggedUser' => $this->loggedUser,
        'user' => $user,
        'flash' => $flash
    ]);
}

    public function configAction(){
       $loggedUser = UserHandler::getUser($this->loggedUser->id);
       $name = filter_input(INPUT_POST,'name');
       $birthdate = filter_input(INPUT_POST, 'birthdate');
       $email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
       $city = filter_input(INPUT_POST,'city');
       $work = filter_input(INPUT_POST,'work');
       $password = filter_input(INPUT_POST,'password');
       $newPassword = filter_input(INPUT_POST,'confirm-password');

       if($name && $email) {
        $updatedFields = [];
      
        $user = UserHandler::getUser($this->loggedUser->id);

        // E-MAIL
        if ($user->email != $email) {
          if(!UserHandler::emailExists($email)){
            $updatedFields['email'] = $email;
          } else {
            $_SESSION['flash'] = 'E-mail já cadastrado!';
            $this->redirect('/config');
          }
        }

       // BIRTHDATE
        $birthdate = explode('/', $birthdate);
        if(count($birthdate) != 3) {
            $_SESSION['flash'] = 'Data de nascimento inválida!';
            $this->redirect('/config');
        }

        $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
        if(strtotime($birthdate) === false) {
            $_SESSION['flash'] = 'Data de nascimento inválida!';
             $this->redirect('/config');
        }
        $updatedFields['birthdate'] = $birthdate;

        // PASSWORD

        if (!empty($password)){
          if($password === $newPassword){
            $updatedFields['password'] = $password;
          } else {
              $_SESSION['flash'] = 'Senhas não coincidem!';
              $this->redirect('/config');
          }          
        }

        // CAMPOS NORMAIS
        $updatedFields['name'] = $name;
        $updatedFields['city'] = $city;
        $updatedFields['work'] = $work;

        // avatar
        if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
          $newAvatar = $_FILES['avatar'];

          if(in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])){
            $avatarName = $this->cutImage($newAvatar, 200, 200, 'media/avatars');
            $updatedFields['avatar'] = $avatarName;
          }  
        }

        // cover
        if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
          $newCover = $_FILES['cover'];

          if(in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])){
            $coverName = $this->cutImage($newCover, 850, 310, 'media/covers');
            $updatedFields['cover'] = $coverName;
          }       
        }

        UserHandler::updateUser($updatedFields, $this->loggedUser->id);
      }
      
      $this->redirect('/config');
}
  private function cutImage($file, $w, $h, $folder) {
    list($widthOrig, $heightOrig) = getimagesize($file['tmp_name']);
    $ratio = $widthOrig / $heightOrig;

    $newWidth = $w;
    $newHeight = $newWidth / $ratio;

    if($newHeight < $h) {
      $newHeight = $h;
      $newWidth = $newHeight * $ratio;
    }

    $x = $w - $newWidth;
    $y = $h - $newHeight;
    $x = $x < 0 ? $x / 2 : $x;
    $y = $y < 0 ? $y / 2 : $y;

    $finalImage = imagecreatetruecolor($w, $h);
    switch($file['type']) {
      case 'image/jpeg':
      case 'image/jpg':
        $image = imagecreatefromjpeg($file['tmp_name']);
      break;
      case 'image/png':
        $image = imagecreatefrompng($file['tmp_name']);
      break;
    }
    imagecopyresampled(
      $finalImage, $image,
      $x, $y, 0, 0,
      $newWidth, $newHeight, $widthOrig, $heightOrig
    );
    $fileName = md5(time().rand(0,9999)).'jpg';

    imagejpeg($finalImage, $folder.'/'.$fileName);

    return $fileName;
  }
}
