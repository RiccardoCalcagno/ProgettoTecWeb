<?php 

    class Comments 
    {
        private $id;
        private $text;
        private $date;
        private $author;
        private $report;

        public function __constructor($_id, $_text, $_date, $_author, $_report)
        {
            $this->id = $_id;
            $this->author = $_author;
            $this->date = $_date;
            $this->report = $_report;
            $this->set_text($_text);
        }


        public function get_id()
        {
            return $this->id;
        }

        public function get_text()
        {
            return $this->text;
        }

        public function get_date()
        {
            return $this->date;
        }

        public function get_author()
        {
            return $this->author;
        }

        public function get_report()
        {
            return $this->report;
        }


        // non ha senso settare l'id

        public function set_text($var)
        {
            $text = $var;
        }

        // non ha senso settare la data

        // non ha senso cambiare l'autore o il report
    }

?>