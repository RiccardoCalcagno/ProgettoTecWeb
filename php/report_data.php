<?php

    require_once("GeneralPurpose.php");

    class ReportData {
        
        private $id;
        private $title;
        private $subtitle;
        private $content;
        private $author;
        private $isExplorable;
        private $author_img;

        /*HO INSERITO QUESTO CAMPO*/
        private $lista_giocatori;                          

        private $last_modified;
    
        public function __construct($id, $title, $subtitle, $content, $author, $isExplorable, $lista_giocatori,$author_img=null, $last_modified = null)
        {
            $this->id=$id;
            $this->title=$title;
            $this->subtitle=$subtitle;
            $this->content=$content;
            $this->author=$author;
            $this->isExplorable=$isExplorable;
            $this->author_img=$author_img;
            $this->lista_giocatori=$lista_giocatori;

            $this->last_modified=$last_modified;
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
            $author =  "user";  //$var;
        }

        public function set_isExplorable($var) 
        {
            $isExplorable = $var;
        }
        
        public function set_lista_giocatori($var) 
        {
            $lista_giocatori = $var;
        }

        public function set_author_img($var)
        {
            $author_img = $var;
        }

        public function set_last_modified($var) 
        {
            $last_modified = $var;
        }

        function get_id()
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
        
        public function get_lista_giocatori()
        {
            return $this->lista_giocatori;
        }

        public function get_author_img()
        {
            return $this->author_img;
        }

        public function get_last_modified() 
        {
            return $this->last_modified;
        }
    }

?>