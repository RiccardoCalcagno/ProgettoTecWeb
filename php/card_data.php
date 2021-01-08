<?php

    class ReportCard {
        
        private $id;
        private $title;
        private $subtitle;
        private $num_players;
        private $isExplorable;
        private $author;
        private $author_img;
    
        public function __construct($id, $title, $subtitle, $num_players, $isExplorable, $author, $author_img) 
        {
            $this->id=$id;
            $this->title=$title;
            $this->subtitle=$subtitle;
            $this->num_players=$num_players;
            $this->isExplorable=$isExplorable;
            $this->author=$author;
            $this->author_img=$author_img;
            
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

        public function set_num_players($var) 
        {
            $num_players = $var;
        }

        public function set_author($var) 
        {
            $author = $var;
        }

        public function set_author_img($var) 
        {
            $author_img = $var;
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

        public function get_num_players()
        {
            return $this->num_players;
        }

        public function get_author()
        {
            return $this->author;
        }

        public function get_author_img()
        {
            return $this->author_img;
        }

        public function get_isExplorable()
        {
            return $this->isExplorable;
        }
    }

?>