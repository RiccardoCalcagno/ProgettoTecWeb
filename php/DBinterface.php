<?php
    require_once("user_data.php");
    require_once("comments.php");
    require_once("character.php");
    require_once("report_data.php");
    //require_once("card_data.php");
    //require_once("photo_data.php");
    //require_once("report_giocatore_data.php");

    require_once("GeneralPurpose.php");


    class DBinterface {
        private const HOST = "localhost:3306";
        private const USERNAME = "admin";
        private const PASSWORD = "adminPSW3";
        private const DB_NAME = "nsertori";
        
        private $connection;

        public function __constructor() {}

        public function openConnection()
        {
            $this->connection = mysqli_connect( DBinterface::HOST, 
                                                DBinterface::USERNAME, 
                                                DBinterface::PASSWORD, 
                                                DBinterface::DB_NAME);
            
            $query = "SET time_zone = '+01:00';";
            mysqli_query($this->connection, $query);

            return !$this->connection ? false : true;
        }

        public function closeConnection() 
        {
            mysqli_close($this->connection);
        }



        public function escapeReport(ReportData $rep){
            $rep->set_title(mysqli_real_escape_string ( $this->connection , $rep->get_title()));
            $rep->set_subtitle(mysqli_real_escape_string ( $this->connection , $rep->get_subtitle()));
            $rep->set_content(mysqli_real_escape_string ( $this->connection , $rep->get_content()));
            $rep->set_author(mysqli_real_escape_string ( $this->connection , $rep->get_author()));
            return $rep;
        }

        public function escapeCharacter(Character $char){
            $char->set_name(mysqli_real_escape_string ( $this->connection , $char->get_name()));
            $char->set_traits(mysqli_real_escape_string ( $this->connection , $char->get_traits()));
            $char->set_ideals(mysqli_real_escape_string ( $this->connection , $char->get_ideals()));
            $char->set_bonds(mysqli_real_escape_string ( $this->connection , $char->get_bonds()));
            $char->set_flaws(mysqli_real_escape_string ( $this->connection , $char->get_flaws()));
            $char->set_author(mysqli_real_escape_string ( $this->connection , $char->get_author()));
            return $char;
        }

        public function escapeUser(UserData $user){
            $user->set_username(mysqli_real_escape_string ( $this->connection , $user->get_username()));
            $user->set_name_surname(mysqli_real_escape_string ( $this->connection , $user->get_name_surname()));
            $user->set_email(mysqli_real_escape_string ( $this->connection , $user->get_email()));
            $user->set_passwd(mysqli_real_escape_string ( $this->connection , $user->get_passwd()));
            return $user;
        }

        public function escapeComment(Comments $comm){
            $comm->set_author(mysqli_real_escape_string ( $this->connection , $comm->get_author()));
            $comm->set_text(mysqli_real_escape_string ( $this->connection , $comm->get_text()));
            return $comm;
        }



        public function getUser($name, $password) 
        {
            $name = clean_input($name);
            $password = clean_input($password);
            $password=mysqli_real_escape_string ( $this->connection , $password);
            
            $query = "SELECT Users.username, Users.name_surname, Users.email, Users.passwd, Users.birthdate, Users.img_path, Users.id 
                      FROM Users
                      WHERE Users.username = '".$name."' AND Users.passwd = '".$password."';";

            $query_result = mysqli_query($this->connection, $query);

            if(!$query_result) {
                return null;
            }
            else if(mysqli_num_rows($query_result) == 0) 
            {
                echo "Spiacenti! Utente non trovato";
                return null;
            }
            else 
            {
                $user_data = $query_result->fetch_array();
                return new UserData($user_data["username"], 
                                    $user_data["name_surname"], 
                                    $user_data["email"], 
                                    $user_data["passwd"], 
                                    $user_data["birthdate"], 
                                    $user_data["img_path"],
                                    $user_data["id"]);
            }  
        }

        public function existUser($username) 
        {
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT * ". 
                     "FROM Users ". 
                     "WHERE Users.username = '" . $username . "';";

            $exist =   mysqli_query($this->connection, $query);
        if($exist->num_rows > 0)
                return $exist;
        return null;
        }

        public function existMail($email) 
        {
            $email=mysqli_real_escape_string ( $this->connection , $email);
            $query = "SELECT * ". 
                     "FROM Users ". 
                     "WHERE email = '" . $email . "';";

            $exist =   mysqli_query($this->connection, $query);
        if($exist->num_rows > 0) 
               return $exist;
        else
        return null;
        }

        public function setUser(UserData $user_data, $username) 
        {
            $user_data = DBinterface::escapeUser($user_data);

            $username = mysqli_real_escape_string ( $this->connection , $username);
        
            $query = "UPDATE Users ".
                     "SET username = '" . $user_data->get_username() . "', " .
                     "    name_surname = '" . $user_data->get_name_surname() . "', " .
                     "    email = '" . $user_data->get_email() . "', ".
                     "    birthdate = '" . $user_data->get_birthdate() . "', ".
                     "    img_path = '" . $user_data->get_img_path() . "' ".
                     "WHERE username = '" . $username . "';";

            $done = mysqli_query($this->connection, $query);
            return $done;
        }

        public function setPassword(UserData $user)
        {
            $user=DBinterface::escapeUser($user);
            $query = "UPDATE Users ".
                     "SET passwd = '" . $user->get_passwd() . "' ". 
                     "WHERE username = '" . $user->get_username() . "';";

            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        public function addUser(UserData $userdata) 
        {
            $userdata=DBinterface::escapeUser($userdata);
            $query = "INSERT INTO Users (username, name_surname, email, passwd, birthdate, img_path) ". 
                     "VALUES ('" . $userdata->get_username() . "', ".
                              "'" . $userdata->get_name_surname() . "', ".
                              "'" . $userdata->get_email() . "', ".
                              "'" . $userdata->get_passwd() . "', ".
                              "'" . $userdata->get_birthdate() . "', ".
                              "'" . $userdata->get_img_path() . "');";

            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        public function deleteUser($username) 
        {
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "DELETE FROM Users WHERE Users.username = '" . $username . "';";
            
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        //in base allo username restituisce l'immagine di profilo
        public function getUserPic($username)
        {
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT Users.img_path FROM Users WHERE Users.username = '" . $username . "';";
            $user_pic = mysqli_query($this->connection, $query);
            $ritorno=null;
            if(($user_pic)&&($user_pic->num_rows)){
                $row = $user_pic->fetch_assoc();
                $ritorno=$row['img_path'];
            }
            return $ritorno;
        }

        //in base allo username restituisce l'id
        public function getUserId($username)
        {
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT Users.id FROM Users WHERE Users.username = '" . $username . "';";
            $user_id = mysqli_query($this->connection, $query);
            $ritorno=null;
            if(($user_id)&&($user_id->num_rows)){
                $row = $user_id->fetch_assoc();
                $ritorno=$row['id'];
            }
            return $ritorno;
        }



        public function getCharacterOfUser($char_id, $username)
        {
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $character=null;
            $query = "SELECT * ".
                     "FROM Characters ". 
                     "WHERE Characters.author = '" . $username . "' " . 
                     "AND Characters.id = '" . $char_id . "';";

            $query_result = mysqli_query($this->connection, $query);

            if(($query_result)&&($query_result->num_rows)){
                $row = $query_result->fetch_assoc();
                $character = new Character($row["id"], 
                                            $row["name"], 
                                            $row["race"], 
                                            $row["class"], 
                                            $row["background"], 
                                            $row["alignment"], 
                                            $row["traits"], 
                                            $row["ideals"], 
                                            $row["bonds"], 
                                            $row["flaws"], 
                                            $row["author"], 
                                            $row["creation_date"]);
                                            
            }
            return $character;
        }

        public function getCharactersByUser($username)
        {
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT * ".
                     "FROM Characters ". 
                     "WHERE author = '" . $username . "' ". 
                     "ORDER BY creation_date DESC;";

            $query_result = mysqli_query($this->connection, $query);

            if(!$query_result->num_rows) 
            {
                return null;
            }
            else
            {
                $characters = array();
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $character = new Character($row["id"], 
                                                $row["name"], 
                                                $row["race"], 
                                                $row["class"], 
                                                $row["background"], 
                                                $row["alignment"], 
                                                $row["traits"], 
                                                $row["ideals"], 
                                                $row["bonds"], 
                                                $row["flaws"], 
                                                $row["author"], 
                                                $row["creation_date"]);
                    array_push($characters, $character);
                }
            }

            return $characters;
        }

        public function getCharactersById($id)
        {
            $id = clean_input($id);
            $query = "SELECT * ".
                    "FROM Characters ". 
                    "WHERE Characters.id = '" . $id . "' ". 
                    "ORDER BY creation_date DESC;";

            $query_result = mysqli_query($this->connection, $query);

            if(!$query_result->num_rows) 
            {
                return null;
            }
            else
            {
                $row = $query_result->fetch_assoc();
                return new Character($row["id"], 
                                     $row["name"], 
                                     $row["race"], 
                                     $row["class"], 
                                     $row["background"], 
                                     $row["alignment"], 
                                     $row["traits"], 
                                     $row["ideals"], 
                                     $row["bonds"], 
                                     $row["flaws"], 
                                     $row["author"], 
                                     $row["creation_date"]);
            }
        }

        public function deleteCharacter($id)
        {
            $id = clean_input($id);
            $query = "DELETE FROM Characters WHERE Characters.id = '" . $id . "';";

            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        public function setCharacter(Character $character_data, $id) 
        {
            $character_data=DBinterface::escapeCharacter($character_data);
            $id = clean_input($id);
            $query = "UPDATE Characters ".
                     "SET name = '" . $character_data->get_name() . "', " .
                     "    race = '" . $character_data->get_race() . "', ".
                     "    class = '" . $character_data->get_class() . "', ".
                     "    background = '" . $character_data->get_background() . "', ".
                     "    alignment = '" . $character_data->get_alignment() . "', ".
                     "    traits = '" . $character_data->get_traits() . "', ".
                     "    ideals = '" . $character_data->get_ideals() . "', ".
                     "    bonds = '" . $character_data->get_bonds() . "', ".
                     "    flaws = '" . $character_data->get_flaws() . "'".
                     "WHERE id = '" . $id . "';"; //"WHERE author = '" . $id . "';";

            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        public function addCharacter(Character $character_data)
        {
            $character_data=DBinterface::escapeCharacter($character_data);
            $query = "INSERT INTO Characters (name, race, class, background, alignment, traits, ideals, bonds, flaws, author) ". 
                     "VALUES ('" . $character_data->get_name() . "', ".
                              "'" . $character_data->get_race() . "', ".
                              "'" . $character_data->get_class() . "', ".
                              "'" . $character_data->get_background() . "', ".
                              "'" . $character_data->get_alignment() . "', ". 
                              "'" . $character_data->get_traits() . "', ".
                              "'" . $character_data->get_ideals() . "', ".
                              "'" . $character_data->get_bonds() . "', ".
                              "'" . $character_data->get_flaws() . "', ".
                              "'" . $character_data->get_author() . "'".
                              ");";

            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        public function contaPersonaggi($username) 
        {
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT COUNT(Characters.id)". 
                     "FROM Characters ". 
                     "WHERE Characters.author = '".$username."';";

            if($query){
                $query_result = mysqli_query($this->connection, $query)->fetch_array();
                return $query_result["COUNT(Characters.id)"];}
                else{return 0;}
        }

        

        //-----------------------------------------------------------------------------------------------------------------
        // FUNZIONI RELATIVE AI REPORT
        //-----------------------------------------------------------------------------------------------------------------

        public function getReport($id_report) {
            $id_report = clean_input($id_report);    
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, Users.img_path, Report.last_modified ".
                    "FROM Report ". 
                    "INNER JOIN Users ".
                    "ON Users.username = Report.author ".   /// TO FIX
                    "WHERE Report.id = '" . $id_report . "';";

            $query_result = mysqli_query($this->connection, $query);

            if(!$query_result || mysqli_num_rows($query_result) == 0) {
                echo "Spiacenti! Report non trovato";
                return null;
            }
            else {
                $row = $query_result->fetch_assoc();
                return new ReportData($row["id"], 
                                    $row["title"], 
                                    $row["subtitle"], 
                                    $row["content"], 
                                    $row["author"], 
                                    $row["isExplorable"], 
                                    DBinterface::getALLUsernamesForReport($row["id"]),
                                    $row["img_path"], 
                                    $row["last_modified"]);
            }
        }

        
        //restituisce il pi� alto id di report
        public function getHighestRepId()
        {
            $query = "SELECT Report.id FROM Report ORDER BY id DESC;";
            $user_id = mysqli_query($this->connection, $query);
            $ritorno=null;
            if(($user_id)&&($user_id->num_rows)){
                $row = $user_id->fetch_assoc();
                $ritorno=$row['id'];
            }
            return $ritorno;
        }

        // aggiunta di un report
        public function addReport(ReportData $report_data){
            $report_data=DBinterface::escapeReport($report_data);
            $query = "INSERT INTO Report (title,subtitle,content,author,isExplorable) ".
                     "VALUES ('" . $report_data->get_title() . "', ".
                              "'" . $report_data->get_subtitle() . "', ".
                              "'" . $report_data->get_content() . "', ".
                              "'" . $report_data->get_author() . "', ".
                              "'" . $report_data->get_isExplorable() . "');";
            $done =   mysqli_query($this->connection, $query);
            
            if($done){
                $isAdded = true;
                foreach($report_data->get_lista_giocatori() as $singleLinkedUser){
                    if($isAdded){
                        $isAdded = DBinterface::ALUsimplified(DBinterface::getUserId($singleLinkedUser),DBinterface::getHighestRepId());
                    }else{
                        break;
                    }
                }
                
                return $isAdded;
            }else{
                return $done;
            }
            //return $isAdded; //$done && 
        }

        // modifica report
        public function setReport(ReportData $report_data){
            $report_data=DBinterface::escapeReport($report_data);
            $query = "UPDATE Report ".
                     "SET title         = '" . $report_data->get_title() . "', " .
                     "    subtitle         = '" . $report_data->get_subtitle() . "', ".
                     "    content         = '" . $report_data->get_content() . "', ".
                     "    author         = '" . $report_data->get_author() . "', ".
                     "    isExplorable     = '" . $report_data->get_isExplorable() . "' ".
                     "WHERE id = '" . $report_data->get_id() . "';";
            $done =   mysqli_query($this->connection, $query);
            $isCleared = DBinterface::deleteReportMention_by_id($report_data->get_id());
            $isAdded = true;
            foreach($report_data->get_lista_giocatori() as $singleLinkedUser){
                if($isAdded){
                    $isAdded = DBinterface::ALUsimplified(DBinterface::getUserId($singleLinkedUser),$report_data->get_id());
                }
                else{
                    break;
                }
            }
            return ($done && $isCleared && $isAdded);
        }

        // elimina report
        public function deleteReport($id)
        {
            $id = clean_input($id);
            $this->deleteAllComments($id);
            $query = "DELETE FROM Report WHERE Report.id = '" . $id . "';";
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        public function deleteAllComments($id) {
            $query = "DELETE FROM Comments WHERE Comments.report = '" . $id . "';";
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        //funzione per ricavare il numero di utenti linkati ad un report come "partecipanti"
        public function linkedUsersCounter($repo_id) {
          $repo_id = clean_input($repo_id);
            $query = "SELECT * FROM report_giocatore RG WHERE RG.report = '".$repo_id."';";
            $query_result =   mysqli_query($this->connection, $query);
            
            if($query_result)
                return $query_result->num_rows;
            else
                return 0;
        }



        public function getReportExplorable()
        {
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, Users.img_path, Report.last_modified 
                      FROM Report INNER JOIN Users ON Report.author = Users.username WHERE Report.isExplorable = 1 
                      ORDER BY Report.last_modified DESC;";
                      

            $query_result = mysqli_query($this->connection, $query);

            $reports = array();

            if((($query_result)&&($query_result->num_rows)) ){
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $report = new ReportData($row["id"], 
                                            $row["title"], 
                                            $row["subtitle"], 
                                            $row["content"], 
                                            $row["author"], 
                                            $row["isExplorable"], 
                                            DBinterface::getALLUsernamesForReport($row["id"]),
                                            $row["img_path"], 
                                            $row["last_modified"]);
                    array_push($reports, $report);
                }
            }
            return $reports;
        }

        public function getReportList($username) 
        {
            $reports=array();
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, U2.img_path, Report.last_modified 
                FROM Users U1 
                INNER JOIN report_giocatore 
                ON U1.id = report_giocatore.user 
                INNER JOIN Report 
                ON report_giocatore.report = Report.id 
                INNER JOIN Users U2 
                ON U2.username = Report.author 
                WHERE U1.username = '".$username."';";

            $query_result = mysqli_query($this->connection, $query);

            if(($query_result)&&($query_result->num_rows)) {
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $report = new ReportData($row["id"], 
                                             $row["title"], 
                                             $row["subtitle"], 
                                             $row["content"], 
                                             $row["author"], 
                                             $row["isExplorable"], 
                                             DBinterface::getALLUsernamesForReport($row["id"]),
                                             $row["img_path"], 
                                             $row["last_modified"]);
                    array_push($reports, $report);
                }
            }

            return $reports;
            
        }

        public function getReportAuthor($username) 
        {
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, Users.img_path, Report.last_modified ".
                     "FROM Report INNER JOIN Users ON Report.author = Users.username ". 
                     "WHERE Report.author = '" . $username . "';";

            $query_result = mysqli_query($this->connection, $query);
            $reports = array();
            if(($query_result)&&($query_result->num_rows)){
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $report = new ReportData($row["id"], 
                                             $row["title"], 
                                             $row["subtitle"], 
                                             $row["content"], 
                                             $row["author"], 
                                             $row["isExplorable"], 
                                             DBinterface::getALLUsernamesForReport($row["id"]),
                                             $row["img_path"], 
                                             $row["last_modified"]);
                    array_push($reports, $report);
                } 
            }
            return $reports;
        }

        /*
        public function countReportAuthor($username)
        {
            $count=0;
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT Report.id ".
                     "FROM Report ". 
                     "WHERE Report.author = '" . $username . "';";
            $query_result =   mysqli_query($this->connection, $query);
            if(($query_result)&&($query_result->num_rows)) {
                $count=$query_result->num_rows;
            }
            return $count;
        }
        */

        /*
        public function countReportExplorable()
        {
            $count=0;
            $query = "SELECT Report.id ".
            "FROM Report ".  "WHERE Report.isExplorable = 1";
            $query_result = mysqli_query($this->connection, $query);

            if(($query_result)&&($query_result->num_rows)) {
                $count=$query_result->num_rows;
            }

            return $count;
        }
        */

        /*
        public function countReport($username)
        {
            $count=0;
            $username = clean_input($username);
            $username=mysqli_real_escape_string ( $this->connection , $username);
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, U2.img_path, Report.last_modified 
            FROM Users U1 
            INNER JOIN report_giocatore 
            ON U1.id = report_giocatore.user 
            INNER JOIN Report 
            ON report_giocatore.report = Report.id 
            INNER JOIN Users U2 
            ON U2.username = Report.author 
            WHERE U1.username = '".$username."';";

            $query_result = mysqli_query($this->connection, $query);

            if(($query_result)&&($query_result->num_rows)) {
                $count=$query_result->num_rows;
            }

            return $count;
        }
        */

        public function setExplorable($report_id, $isExplorable = 1)
        {
            $isExplorable=(int)$isExplorable;
            $query = "UPDATE Report ". 
                     "SET isExplorable = '" . $isExplorable . "' ".
                     "WHERE id = '" . $report_id . "';";
            $done = mysqli_query($this->connection, $query);
            return $done;
        }

        public function getLatestRep(){
          $query = "SELECT Report.id FROM Report DESC;";
          $queryResult = mysqli_query($this->connection, $query);
          $row = $queryResult->fetch_row();
          return $row[0];
        }

        /**
         * -------------------------------------------------------------------
         * Funzioni relative ai commenti
         * -------------------------------------------------------------------
         */

        public function getComments($id_report)
        {
            $comments = array();
            $id_report = clean_input($id_report);
            $query = "SELECT Comments.id, Comments.testo, Comments.data_ora, Comments.author, Comments.report ".
                     "FROM Comments ". 
                     "WHERE Comments.report = '" . $id_report . "';";

            $query_result = mysqli_query($this->connection, $query);

            if(!$query_result || !$query_result->num_rows) 
            {
                return null;
            }
            else
            {
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $comment = new Comments($row["testo"], $row["author"], $row["report"], $row['id'], $row['data_ora']);          
                    array_push($comments, $comment);
                }
            }

            return $comments;
        }

        public function deleteComments($id_comments) 
        {
            $id_comments = clean_input($id_comments);
            $query = "DELETE FROM Comments WHERE Comments.id = '" . $id_comments . "';";
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        public function addComments(Comments $comments)
        {
            $comments=DBinterface::escapeComment($comments);
            $query = "INSERT INTO Comments (testo, author, report) ". 
                     "VALUES ('" . $comments->get_text() . "', ".  
                             "'" . $comments->get_author() . "', ". 
                             "'" . $comments->get_report() . "');" ;
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        //-----------------------------------------------------------------------------------------------------------------
        // FUNZIONI RELATIVE A PHOTO REPORT
        //-----------------------------------------------------------------------------------------------------------------

        /*public function getMediaGallery ($repo_id){
          $repo_id = clean_input($repo_id);
          $querySelect = "SELECT * FROM Photo WHERE Photo.report = '".$repo_id."' ORDER BY ID ASC;";
          $queryResult = mysqli_query($this->connection, $querySelect);

          if(mysqli_num_rows($queryResult) == 0){
            return null;
          }
          else{
            $PhotoArray = array();
            while($row = mysqli_fetch_assoc($queryResult)){
              $singlePhoto = new PhotoData($row['id'],$row['img_path'],$riga['report']);

              array_push($PhotoArray,$singlePhoto);
            }

            return $PhotoArray;
          }
        }

        public function addPhoto (PhotoData $photo_data){
            $query = "INSERT INTO Photo (id,img_path,report)".
               "VALUES ('" . $photo_data.get_id() . "', ".
                        "'" . $photo_data.get_img_path() . "', ".
                        "'" . $photo_data.get_report() . "');";

            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        // elimina photo
        public function deletePhoto($id)
        {
            $id = clean_input($id);
            $query = "DELETE FROM Photo WHERE id = '" . $id . "';";
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        // funzione per prendere una specifica foto dal db
        public function getSinglePhoto ($photo_id){
          $photo_id = clean_input($photo_id);
            $query = "SELECT * FROM Photo WHERE Photo.id = '".$photo_id."';";
            $query_result = mysqli_query($this->connection, $query);
            if(mysqli_num_rows($query_result) == 0) {
                echo "Spiacenti! Foto non trovata";
                return null;
            }
            else {
                $row_arr = $query->mysqli_assoc(MYSQLI_ASSOC);
                return $row_arr;
            }
        }*/

        //-----------------------------------------------------------------------------------------------------------------
        // FUNZIONI RELATIVE A REPORT_GIOCATORE
        //-----------------------------------------------------------------------------------------------------------------

        // Aggiunge una riga alla tabella report_giocatore
        public function addLinkedUser (UserData $user, ReportData $report){
          $query = "INSERT INTO report_giocatore (user, report) ". 
                     "VALUES ('" . $user->get_id() . "', ".  
                             "'" . $report->get_id() . "');" ;
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        // Aggiunge una riga, ma usa il singolo dato e non l'intero oggetto
        public function ALUsimplified ($username, $report_id){
          $query = "INSERT INTO report_giocatore (user, report) ". 
                     "VALUES ('" . $username . "', ".  
                             "'" . $report_id . "');" ;
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        //elimina un singolo utente da un determinato report
        public function deleteUserFromReport(UserData $user, ReportData $report)
        {
            $query = "DELETE FROM report_giocatore WHERE user = '" . $user->get_id() . "' AND report = '".$report->get_id()."';";
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        //elimina un singolo utente da tutti i report
        public function deleteUserFromALL(UserData $user, ReportData $report)
        {
            $query = "DELETE FROM report_giocatore WHERE user = '" . $user->get_id() . "';";
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        //elimina tutte le mention ad un determinato Report
        public function deleteReportMention(UserData $user, ReportData $report)
        {
            $query = "DELETE FROM report_giocatore WHERE report = '".$report->get_id()."';";
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        //elimina tutte le mention ad un report, semplificata al solo report id
        public function deleteReportMention_by_id($report_id)
        {
            $query = "DELETE FROM report_giocatore WHERE report = '".$report_id."';";
            $done =   mysqli_query($this->connection, $query);
            return $done;
        }

        // Restituisce tutti i report (id) legati ad un utente
        public function getALLForUser($user){
            $reportsWITHuser = array();
          $user = clean_input($user);
          $query = "SELECT * FROM report_giocatore RG WHERE RG.user = '".$user."';";
          $query_result = mysqli_query($this->connection, $query);

            if($query_result->num_rows){
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $report_id = $row["report"];         
                    array_push($reportsWITHuser, $report_id);
                }
            }
            return $reportsWITHuser;
        }

        public function getALLUsernamesForReport($report_id){
            $usersINreport = array();
          $report_id = clean_input($report_id);
          $query = "SELECT Users.username FROM report_giocatore RG JOIN Users ON RG.user = Users.id WHERE RG.report = '".$report_id."';";
          $query_result = mysqli_query($this->connection, $query);

            if($query_result->num_rows){
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $username = $row["username"];         
                    array_push($usersINreport, $username);
                }
            }

            return $usersINreport;
        }

        public function getReportForPertecipant($id_report, $partecipant)
        {
            $id_report = clean_input($id_report);
            $query = $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, Users.img_path, Report.last_modified ".
                            "FROM Report ". 
                            "INNER JOIN report_giocatore ".
                            "ON Report.id = report_giocatore.report ". 
                            "INNER JOIN Users ". 
                            "ON report_giocatore.user = Users.id ". 
                            "WHERE Users.username = '" . $partecipant . "' AND Report.id = '" . $id_report . "';";

            $query_result = mysqli_query($this->connection, $query);

            if(mysqli_num_rows($query_result) == 0) {
                echo "Spiacenti! Report non trovato";
                return null;
            }
            else {
                $report_data = $query->fetch_assoc();
                return new ReportData($row["id"], 
                                        $row["title"], 
                                        $row["subtitle"], 
                                        $row["content"], 
                                        $row["author"], 
                                        $row["isExplorable"], 
                                        DBinterface::getALLUsernamesForReport($row["id"]),
                                        $row["img_path"], 
                                        $row["last_modified"]);
                /*
                return new UserData($user_data["username"], $user_data["name_surname"], $user_data["email"], $user_data["passwd"], $user_data["bithdate"], $user_data["img_path"]);

                $row_arr = $query->mysqli_fetch_assoc(MYSQLI_ASSOC);
                return $row_arr;
                */
            }
        }

    }
?>
