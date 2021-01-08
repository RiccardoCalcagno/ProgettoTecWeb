<?php

    //Da definire controlli sugli errori di imput

    class ReportGiocData {
        
        private $user;
        private $report;
    
        public function __construct($user, $report) 
        {
            $this->user=$user;
            $this->report=$report;
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