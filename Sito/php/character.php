<?php

    class Character 
    {
        private $id; 
        private $name;
        private $race; 
        private $class; 
        private $background;
        private $alignment; 
        private $traits; 
        private $ideals; 
        private $bonds; 
        private $flaws; 
        private $author; 
        private $creation_date;

        private $err_race;
        private $err_class;
        private $err_background;
        private $err_alignment;

        private static $race_arr = array('Umano', 'Elfo', 'Nano', 'Halfling', 'Gnome', 'Tiefling', 'Dragonide', 'Mezzelfo', 'Mezzorco');
        private static $class_arr = array('Bardo', 'Barbaro', 'Druido', 'Ladro', 'Chierico', 'Guerriero', 'Monaco', 'Mago', 'Warlock', 'Paladino', 'Ranger', 'Stregone');
        private static $background_arr = array('Accolito', 'Artigiano', 'Ciarlatano', 'Criminale', 'Eremita', 'Eroe', 'Forestiero', 'Intrattenitore', 'Marinaio', 'Monello', 'Nobile', 'Sapiente', 'Soldato');
        private static $alignment_arr = array('Legale Buono', 'Neutrale Buono', 'Caotico Buono', 'Legale Neutrale', 'Neutrale Puro', 'Caotico Neutrale', 'Legale Malvagio', 'Neutrale Malvagio', 'Caotico Malvagio');

        public function __construct($_id, $_name, $_race, $_class, $_background, $_alignment, $_traits, $_ideals, $_bonds, $_flaws, $_author = null)
        {
            $this->id = $_id;
            $this->set_name($_name);
            $this->set_race($_race);
            $this->set_class($_class);
            $this->set_background($_background);
            $this->set_alignment($_alignment);
            $this->set_traits($_traits);
            $this->set_ideals($_ideals);
            $this->set_bonds($_bonds);
            $this->set_flaws($_flaws);
            if ($_author != null) { // Not logged in
                $this->set_author($_author);
            }
                
    //        $this ->set_creation_date($_creation_date);

        }

        public function get_id()
        {
            return $this->id;
        }

        public function get_name()
        {
            return $this->name;
        }

        public function get_race()
        {
            return $this->race;
        }

        public function get_class()
        {
            return $this->class;
        }

        public function get_background()
        {
            return $this->background;
        }

        public function get_alignment()
        {
            return $this->alignment;
        }

        public function get_traits()
        {
            return $this->traits;
        }

        public function get_ideals()
        {
            return $this->ideals;
        }

        public function get_bonds()
        {
            return $this->bonds;
        }

        public function get_flaws()
        {
            return $this->flaws;
        }

        public function get_author()
        {
            return $this->author;
        }

        public function get_creation_date()
        {
            return $this->creation_date;
        }
        /*public function set_id($var)
        {

        }*/

        public function set_name($var)
        {
            $this->name = $var;
        }

        public function set_race($var)
        {
            $err_race = "";

            $pos;

            (($pos = array_search($var, Character::$race_arr)) !== false) ?  $this->race = Character::$race_arr[$pos] : $err_race = "Razza inesistente";
        }

        public function set_class($var)
        {
            $err_class = "";

            $pos;

            (($pos = array_search($var, Character::$class_arr)) !== false) ?  $this->class = Character::$class_arr[$pos] : $err_class = "Classe inesistente";
        }

        public function set_background($var)
        {
            $err_background = "";

            $pos;

            (($pos = array_search($var, Character::$background_arr)) !== false) ?  $this->background = Character::$background_arr[$pos] : $err_background = "Background inesistente";
        }

        public function set_alignment($var)
        {
            $err_alignment = "";

            $pos;

            (($pos = array_search($var, Character::$alignment_arr)) !== false) ?  $this->alignment = Character::$alignment_arr[$pos] : $err_alignment = "Allineamento inesistente";
        }

        public function set_traits($var)
        {
            $this->traits = $var;
        }

        public function set_ideals($var)
        {
            $this->ideals = $var;
        }

        public function set_bonds($var)
        {
            $this->bonds = $var;
        }

        public function set_flaws($var)
        {
            $this->flaws = $var;
        }

        public function set_author($var) {
            $this->author = $var;
        }

        public function set_creation_date($var)
        {
            $this->creation_date = $var;
        }

        public function get_err_race()
        {
            return $this->err_race;
        }

        public function get_err_class()
        {
            return $this->err_class;
        }

        public function get_err_background()
        {
            return $this->err_background;
        }

        public function get_err_alignment()
        {
            return $this->err_alignment;
        }
    }
?>