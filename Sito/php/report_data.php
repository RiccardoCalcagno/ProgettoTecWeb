<?php

    require_once "GeneralPurpose.php";

    class ReportData {
        
        private $id;
        private $title;
        private $subtitle;
        private $content;
        private $author;
        private $isExplorable;

        /*HO INSERITO QUESTO CAMPO*/
        private $lista_giocatori;                          

        private $last_modified;
    
        public function __constructor($_id, $_title, $_subtitle, $_content, $_author, $_isExplorable, $_lista_giocatori, $_last_modified = null)
        {
            $this->set_id($_id);
            $this->set_title($_title);
            $this->set_subtitle($_subtitle);
            $this->set_content($_content);
            $this->set_author($_author);
            $this->set_isExplorable($_isExplorable);
            $this->set_lista_giocatori($_lista_giocatori);
            if($_last_modified)
                $this->set_last_modified($_last_modified);
                else $this->set_last_modified(data());  // inserire data odierna
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