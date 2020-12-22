<?php

    //Da definire controlli sugli errori di imput

    class ReportGiocData {
        
        private $user;
        private $report;
    
        public function __constructor($_user, $_report) 
        {
            $this->set_user($_user);
            $this->set_report($_report);
        }

        public function set_user($var) 
        {
            $user = $var;
        }

        public function set_report($var) 
        {
            $report = $var;
        }

        public function get_user()
        {
            return $this->user;
        }

        public function get_report()
        {
            return $this->report;
        }

    }

?>