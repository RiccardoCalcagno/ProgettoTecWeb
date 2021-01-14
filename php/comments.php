<?php 

    class Comments 
    {
        private $id;
        private $text;
        private $date;
        private $author;
        private $report;

        public function __construct($text, $author, $report, $id = null, $date = null)
        {
            $this->id = $id;
            $this->author = $author;
            $this->date = substr($date,0,16);
            $this->report = $report;
            $this->text=$text;
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
            $this->text = $var;
        }

        // non ha senso settare la data

        public function set_author($var)
        {
            $this->author = $var;
        }
        // non ha senso cambiare il report
    }

?>