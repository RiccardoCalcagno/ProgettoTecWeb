<?php

    require_once "GeneralPurpose.php";

    class ReportData {
        
        private $id;
        private $title;
        private $subtitle;
        private $content;
        private $author;
        private $isExplorable;
    
        public function __constructor($_id, $_title, $_subtitle, $_content, $_author, $_isExplorable) 
        {
            $this->set_id($_id);
            $this->set_title($_title);
            $this->set_subtitle($_subtitle);
            $this->set_content($_content);
            $this->set_author($_author);
            $this->set_isExplorable($_isExplorable);
        }

        public function set_id($var) 
        {
            $id = $var;
        }

        public function set_title($var) 
        {
            $title = $var;
        }

        public function set_subtitle($var) 
        {
            $subtitle = $var;
        }

        public function set_content($var) 
        {
            $content = $var;
        }

        public function set_author($var) 
        {
            $author = $var;
        }

        public function set_isExplorable($var) 
        {
            $isExplorable = $var;
        }

        public function get_id()
        {
            return $this->id;
        }

        public function get_title()
        {
            return $this->title;
        }

        public function get_subtitle()
        {
            return $this->subtitle;
        }

        public function get_content()
        {
            return $this->content;
        }

        public function get_author()
        {
            return $this->author;
        }

        public function get_isExplorable()
        {
            return $this->isExplorable;
        }
    }

?>