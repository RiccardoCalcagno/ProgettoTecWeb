<?php

    require_once "GeneralPurpose.php";

    class UserData {
        
        private $username;
        private $name_surname;
        private $email;
        private $passwd;
        private $birthdate;
        private $img_path;
    
        public function __constructor($_username, $_name_surname, $_email, $_passwd, $_birthdate, $_img_path = null) 
        {
            $this->set_username($_username);
            $this->set_name_surname($_name_surname);
            $this->set_email($_email);
            $this->set_password($_passwd);
            $this->set_birthdate($_birthdate);
            $this->set_img_path($_img_path);
        }

        public function set_username($var) 
        {
            $username = clean_input($var);
        }

        public function set_name_surname($var) 
        {

            $name_surname = $var;
        }

        public function set_email($var) 
        {
            $var = clean_input($var);
            $email = $var;
        }

        public function set_password($var) 
        {
            $password = $var;
        }

        public function set_birthdate($var) 
        {
            $birthdate = $var;
        }

        public function set_img_path($var) 
        {
            $img_path = $var;
        }

        public function get_username()
        {
            return $this->username;
        }

        public function get_name_surname()
        {
            return $this->name_surname;
        }

        public function get_email()
        {
            return $this->email;
        }

        public function get_passwd()
        {
            return $this->passwd;
        }

        public function get_birthdate()
        {
            return $this->birthdate;
        }

        public function get_img_path()
        {
            return $this->img_path;
        }

        public function get_err_name_surname()
        {
            return $this->err_name_surname;
        }

        public function get_err_email()
        {
            return $this->err_email;
        }

        public function get_err_passwd()
        {
            return $this->err_passwd;
        }

        public function get_err_img_path()
        {
            return $this->err_img_path;
        }

    }

?>