<?php

    class PhotoData {
        
        private $id;
        private $img_path;
        private $report;
        private $err;
    
        public function __constructor($_id, $_img_path, $_report) 
        {
            $this->set_id($_id);
            $this->$err = $this->set_img_path($_img_path);
            $this->set_report($_report);

            $this->err = $this->err ? "<p>" . $this->err . "</p>" : "";
        }

        public function set_id($var) 
        {
            $id = $var;
        }

        public function set_img_path($var) 
        {
            $err = "";
            filter_var($var, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) ? $img_path = $var : $err = "Percorso non valido";
            return $err;
        }

        public function set_report($var) 
        {
            $report = $var;
        }

        public function get_id()
        {
            return $this->id;
        }

        public function get_img_path()
        {
            return $this->img_path;
        }

        public function get_report()
        {
            return $this->report;
        }

    }

?>