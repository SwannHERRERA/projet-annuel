<?php

class Form_validation {

  //set_rules
  private $error;
  private $session_key;
  public function __construct($str){
    $this->session_key = $str;
  }
  public function set_rules($field,$message,$rules){
    foreach ($rules as $key => $rule) {
      if (!is_array($rule)){
        if ($rule == 'require'){
          !empty($_POST[$field]) ? '' : $this->error[] = 'Le champ ' . $message . ' doit être remplie';
        }
        if ($rule == 'valid_email'){
          if(!filter_var($_POST[$field], FILTER_VALIDATE_EMAIL)){
            $this->error[] = 'Le champ ' . $message . ' doit être une adresse mail correct';
          }
        }
        if ($rule == 'is_unique'){
          //$result = member_model->is_unique($field);
          if(!empty($result)){
            $this->error[] = "l'" . $message . ' existe déjà';
          }
        }
        if ($rule == 'trim'){
          $_POST[$field] = trim($_POST[$field]);
        }
        if ($rule == 'to_lower'){
          is_string($_POST[$field]) ? $_POST[$field] = strtolower($_POST[$field]) : $this->error[] = 'Erreur';
        }
      } else {
        foreach ($rule as $key => $value) {
          if ($key == 'match'){
            ($_POST[$field] == $_POST[$value]) ? '' : $this->error[] = 'Les champs ' . $message . ' ne corespondent pas';
        }
        if ($key == 'in_list'){

        }
        if ($key == 'regex'){
        }
        if ($key == 'min_length'){
          (strlen($_POST[$field]) < $value) ? $this->error[] = 'La chaine ' . $message . ' est trop courte' : '';
        }
        if ($key == 'max_length'){
          (strlen($_POST[$field]) > $value) ? $this->error[] = 'La chaine ' . $message . ' est trop longue' : '';
        }
        if ($key == 'exact_length'){
          (strlen($_POST[$field]) == $value) ? $this->error[] = 'La chaine ' . $message . ' doit faire ' . $value . ' caractère' : '';
        }
      }
    }
  }
  $_SESSION[$this->session_key] = $this->error;
 }
}
