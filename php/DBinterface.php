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
        private const HOST = "localhost";
        private const USERNAME = "rcalcagn";
        private const PASSWORD = "ein2neipieShahpe";
        private const DB_NAME = "rcalcagn";
        
        private $connection;

        public function __constructor() {}

        public function openConnection()
        {
            $this->connection = mysqli_connect( DBinterface::HOST, 
                                                DBinterface::USERNAME, 
                                                DBinterface::PASSWORD, 
                                                DBinterface::DB_NAME);

            return !$this->connection ? false : true;
        }

        public function closeConnection() 
        {
            mysqli_close($this->connection);
        }

        public function getUser($name, $password) 
        {
            $name = clean_input($name);
            $password = clean_input($password);

            $query = "SELECT username, name_surname, email, passwd, birthdate, img_path
                      FROM Users
                      WHERE Users.username = '".$name."' AND Users.passwd = '".$password."';";

            $query_result = mysqli_query($this->connection, $query);

            if(!$query_result) {
                // TO FIX ERROR
            }
            else if(mysqli_num_rows($query_result) == 0) 
            {
                echo "Spiacenti! Utente non trovato";
                return null;
            }
            else 
            {
                $user_data = $query_result->fetch_array();
                return new UserData($user_data["username"], $user_data["name_surname"], $user_data["email"], $user_data["passwd"], $user_data["birthdate"], $user_data["img_path"]);
            }  
        }

        public function existUser($username) 
        {
            $query = "SELECT * ". 
                     "FROM Users ". 
                     "WHERE username = '" . $username . "';";

            $exist = (bool) mysqli_query($this->connection, $query);
            return $exist;
        }

        public function existMail($email) 
        {
            $query = "SELECT * ". 
                     "FROM Users ". 
                     "WHERE email = '" . $email . "';";

            $exist = (bool) mysqli_query($this->connection, $query);
            return $exist;
        }

        public function setUser(UserData $user_data, $username) 
        {
            $query = "UPDATE Users ".
                     "SET username = '" . $user_data->get_username() . "', " .
                     "    name_surname = '" . $user_data->get_name_surname() . "', " .
                     "    email = '" . $user_data->get_email() . "', ".
                     "    birthdate = '" . $user_data->get_birthdate() . "', ".
                     "    img_path = '" . $user_data->get_img_path() . "', ".
                     "WHERE username = '" . $username . "';";

            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        public function setPassword(UserData $user)
        {
            $query = "UPDATE Users ".
                     "SET passwd = '" . $user->get_passwd() . "', ". 
                     "WHERE username = '" . $user->get_username() . "';";

            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        public function addUser(UserData $userdata) 
        {
            $query = "INSERT INTO Users (username, name_surname, email, passwd, birthdate, img_path) ". 
                     "VALUES ('" . $userdata->get_username() . "', ".
                              "'" . $userdata->get_name_surname() . "', ".
                              "'" . $userdata->get_email() . "', ".
                              "'" . $userdata->get_password() . "', ".
                              "'" . $userdata->get_birthdate() . "', ".
                              "'" . $userdata->get_img_path() . "');";

            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        public function deleteUser($username) 
        {
            $username = clean_input($username);
            $query = "DELETE FROM User WHERE username = '" . $username . "';";
            
            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        //in base allo username restituisce l'immagine di profilo
        public function getUserPic($username)
        {
            $username = clean_input($username);
            $query = "SELECT User.img_path FROM User WHERE username = '" . $username . "';";
            $user_pic = mysqli_query($this->connection, $query);
            return $user_pic;
        }

        public function getCharacterOfUser($char_id, $username)
        {
            $username = clean_input($username);

            $query = "SELECT * ".
                     "FROM Characters ". 
                     "WHERE author = '" . $username . "' " . 
                     "AND id = '" . $char_id . "';";

            $query_result = mysqli_query($this->connection, $query);

            if(!$query_result) {
                // ERROR?
            }
            else if(!$query_result->num_rows) {
                return null;
            }
            else {
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
                                            
                return $character;
            }
        }

        public function getCharactersByUser($username)
        {
            $username = clean_input($username);
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
                    "WHERE id = '" . $id . "' ". 
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
            $query = "DELETE FROM Characters WHERE id = '" . $id . "';";

            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        public function setCharacter(Character $character_data, $id) 
        {
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

            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        public function addCharacter(Character $character_data)
        {
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

            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        public function contaPersonaggi($username) 
        {
            $username = clean_input($username);
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
        public function getReport($id_report, $username) {
            $id_report = clean_input($id_report);
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, Users.img_path, Report.last_modified  
                      FROM Report INNER JOIN Users ON Report.author = Users.username
                      WHERE Report.id = '".$id_report."' AND Report.author = '" . $username . "';";

            $query_result = mysqli_query($this->connection, $query);

            if(mysqli_num_rows($query_result) == 0) {
                echo "Spiacenti! Report non trovato";
                return null;
            }
            else {
                $report_data = $query->mysqli_assoc(MYSQLI_ASSOC);
                return new ReportData($row["Report.id"], 
                                        $row["Report.title"], 
                                        $row["Report.subtitle"], 
                                        $row["Report.content"], 
                                        $row["Report.author"], 
                                        $row["Report.isExplorable"], 
                                        $row["Users.img_path"], 
                                        $row["Report.last_modified"]);
                /*
                return new UserData($user_data["username"], $user_data["name_surname"], $user_data["email"], $user_data["passwd"], $user_data["bithdate"], $user_data["img_path"]);

                $row_arr = $query->mysqli_fetch_assoc(MYSQLI_ASSOC);
                return $row_arr;
                */
            }
        }

        // aggiunta di un report
        public function addReport(ReprotData $report_data){
            $query = "INSERT INTO Report (title,subtitle,content,author,isExplorable,last_modified)".
                     "VALUES ('" . $report_data->get_title() . "', ".
                              "'" . $report_data->get_subtitle() . "', ".
                              "'" . $report_data->get_content() . "', ".
                              "'" . $report_data->get_author() . "', ".
                              "'" . $report_data->get_isExplorable() . "', ".
                              "'" . $report_data->get_last_modified() . "');";
            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        // modifica report
        public function setReport(ReportData $report_data){
            $query = "UPDATE Report ".
                     "SET title         = '" . $report_data->get_title() . "', " .
                     "    subtitle         = '" . $report_data->get_subtitle() . "', ".
                     "    content         = '" . $report_data->get_content() . "', ".
                     "    author         = '" . $report_data->get_author() . "', ".
                     "    isExplorable     = '" . $report_data->get_isExplorable() . "', ".
                     "    author_img     = '" . $report_data->get_isExplorable() . "', ".
                     "    last_modified = '" . $report_data->get_last_modified() . "' ";
                     "WHERE id = '" . $report_data->get_id() . "';";

            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        // elimina report
        public function deleteReport($id)
        {
            $id = clean_input($id);
            $query = "DELETE FROM Report WHERE id = '" . $id . "';";
            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        //funzione per ricavare il numero di utenti linkati ad un report come "partecipanti"
        public function linkedUsersCounter($repo_id) {
          $repo_id = clean_input($repo_id);
            $num_query = "SELECT count(*) FROM report_giocatore RG WHERE RG.report = '".$repo_id."';";
            $num_query_Result = (int) mysqli_query($this->connection, $num_query);
            return $num_query_Result;
        }

        //funzione per ricavare i dati di un determinato numero di Cards, in base ad una lista di report.id
        /*public function getReportCard($IDs_arr) {
          $IDs_arr = clean_input($IDs_arr);
            $CardData_List = array();
            foreach($IDs_arr as $repo_id){
                $query = "SELECT * FROM Report WHERE Report.id = '".$repo_id."';";
                $queryResult = mysqli_query($this->connection, $query);

                if(!$row = mysqli_fetch_assoc($queryResult)){
                    echo "Spiacenti! Report n.".$repo_id."non trovato";
                }
                else{
                    // Cerco l'immagine dell'autore
                    $img_query = "SELECT User.imgpath FROM Users, Report WHERE Users.username = Report.author AND Report.id ='".$row['id']."';";
                    $img_query_Result = mysqli_query($this->connection, $img_query);

                    // Compongo l'array della singola card
                  $singleCard = new ReportCard($row['id'], $row['title'], $row['subtitle'], linkedUsersCounter($row['id']), $row['isExplorable'], $row['author'], $img_query_Result);

                    array_push($CardData_List,$singleCard);
                }
            }
               return $CardData_List;
        }*/


        // funzione che estrae gli esatti report.id per le card desiderate, in base alla pagina
        // temporaneamente uso un numero per identificare i tipi CardType : MyDashboard=0, ImPlayer=1, Esplora=2
        /*public function getIDsReport($CardType,$currentUser){
          $CardType = clean_input($CardType);
          $currentUser = clean_input($currentUser);
            //se CardType = 0, MyDashboard
            if($CardType == 0 && $currentUser){
                $query = "SELECT Report.id FROM Report WHERE Report.author = '".$currentUser."' ORDER BY Report.creation_date DESC;";
                $queryresult = mysqli_query($this->connection, $query);
                return $queryResult;
            }
            //se CardType = 1, ImPlayer
            else if($CardType == 1 && $currentUser){
                $query = "SELECT RG.report FROM report_giocatore RG, Report WHERE RG.user = '".$currentUser."' AND RG.report = Report.id ORDER BY Report.creation_date DESC;";
                $queryresult = mysqli_query($this->connection, $query);
                return $queryResult;
            }
            //se CardType = 2, Esplora
            else if($CardType == 2){
                $query = "SELECT Report.id FROM Report WHERE Report.isExplorable = 1 ORDER BY Report.creation_date DESC;";
                $queryresult = mysqli_query($this->connection, $query);
                return $queryResult;
            }
            else{
                echo "Qualcosa è andato storto! Anteprima non disponibile";
            }

        }*/

        public function getReportExplorable()
        {
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, Users.img_path, Report.last_modified  
                      FROM Report INNER JOIN Users ON Report.author = Users.username WHERE Report.isExplorable = true
                      ORDER BY Report.last_modified DESC;";

            $query_result = $db->mysqli_query($this->connection, $query);

            $reports = array();

            if(!$query_result->num_rows)
            {
                return false;
            }
            else
            {
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $report = new ReportData($row["Report.id"], 
                                             $row["Report.titolo"], 
                                             $row["Report.sottotitolo"], 
                                             $row["Report.contenuto"], 
                                             $row["Report.autore"], 
                                             $row["Report.isExplorable"], 
                                             DBinterface::getALLForReport($row["Report.id"]),
                                             $row["Users.img_path"], 
                                             $row["Report.last_modified"]);
                    array_push($reports, $report);
                }
            }
            return $reports;
        }

        public function getReportList($username) 
        {
            $reports=array();
            $username = clean_input($username);
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, Users.img_path, Report.last_modified ".
                     "FROM Report ". 
                     "INNER JOIN report_giocatore ".
                     "ON Report.id = report_giocatore.report ". 
                     "INNER JOIN Users ". 
                     "ON report_giocatore.user = Users.id ". 
                     "WHERE Users.username = '" . $username . "';";

            $query_result = mysqli_query($this->connection, $query);

            $reports = array();

            if(!$query_result) {
                // TO FIX ERROR
            }
            else if(!$query_result->num_rows) 
            {
                return null;
            }
            else
            {
                
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $report = new ReportData($row["Report.id"], 
                                             $row["Report.title"], 
                                             $row["Report.subtitle"], 
                                             $row["Report.content"], 
                                             $row["Report.author"], 
                                             $row["Report.isExplorable"], 
                                             DBinterface::getALLForReport($row["Report.id"]),
                                             $row["Users.img_path"], 
                                             $row["Report.last_modified"]);
                    array_push($reports, $report);
                }
            }

            return $reports;
            
        }

        public function getReportAuthor($username) 
        {
            $username = clean_input($username);
            $query = "SELECT Report.id, Report.title, Report.subtitle, Report.content, Report.author, Report.isExplorable, Users.img_path Report.last_modified ".
                     "FROM Report INNER JOIN Users ON Report.author = Users.username". 
                     "WHERE Report.author = '" . $username . "';";

            $query_result = mysqli_query($this->connection, $query);

            if(!$query_result) {
                // TO FIX ERROR
            }
            else if(!$query_result->num_rows) 
            {
                return null;
            }
            else
            {
                $reports = array();
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $report = new ReportData($row["Report.id"], 
                                             $row["Report.title"], 
                                             $row["Report.subtitle"], 
                                             $row["Report.content"], 
                                             $row["Report.author"], 
                                             $row["Report.isExplorable"], 
                                             DBinterface::getALLForReport($row["Report.id"]),
                                             $row["Users.img_path"], 
                                             $row["Report.last_modified"]);
                    array_push($reports, $report);
                } 
                return $reports;
            }
            
        }

        public function countReportAuthor($username)
        {
            $username = clean_input($username);
            $query = "SELECT COUNT(Report.id) ".
                     "FROM Report ". 
                     "WHERE Report.author = '" . $username . "';";

            $count = (int) mysqli_query($this->connection, $query);
            return $count;
        }

        public function countReport($username)
        {
            $username = clean_input($username);
            $query = "SELECT COUNT(Report.id) ".
                     "FROM Report ". 
                     "INNER JOIN report_giocatore ".
                     "ON Report.id = report_giocatore.report ". 
                     "INNER JOIN Users ". 
                     "ON report_giocatore.author = Users.id ". 
                     "WHERE Users.username = '" . $username . "';";

            $query_result = mysqli_query($this->connection, $query);
            if($query_result) {
                return $query_result->fetch_array()['COUNT(Report.id)'];
            }
            else {
                // TO FIX ERROR
            }
        }

        public function setExplorable($report_id, $isExplorable = true)
        {
            $query = "UPDATE Report ". 
                     "SET isExplorable = '" . $isExplorable . "' ".
                     "WHERE id = '" . $report_id . "';";

            $done = (bool) mysqli_query($this->connection, $query);
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
                     "FROM Comments". 
                     "WHERE Comments.report = '" . $id_report . "';";

            $query_result = mysql_query($this->connection, $query);

            if(!$query_result->num_rows) 
            {
                return null;
            }
            else
            {
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $comment = new Comments($row["Comments.id"], $row["Comments.testo"], $row["Comments.data_ora"], $row["Comments.author"], $row["Comments.report"]);          
                    array_push($comments, $comment);
                }
            }

            return $comments;
        }

        public function deleteComments($id_comments) 
        {
            $id_comments = clean_input($id_comments);
            $query = "DELETE FROM Comments WHERE Comments.id = '" . $id_comments . "';";
            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        public function addComments(Comments $comments)
        {
            $query = "INSERT INTO Comments (testo, author, report) ". 
                     "VALUES ('" . $comments->get_text() . "', ".  
                             "'" . $comments->get_author() . "', ". 
                             "'" . $comments->get_report() . "');" ;
            $done = (bool) mysqli_query($this->connection, $query);
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

            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        // elimina photo
        public function deletePhoto($id)
        {
            $id = clean_input($id);
            $query = "DELETE FROM Photo WHERE id = '" . $id . "';";
            $done = (bool) mysqli_query($this->connection, $query);
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
            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        //elimina un singolo utente da un determinato report
        public function deleteUserFromReport(UserData $user, ReportData $report)
        {
            $query = "DELETE FROM report_giocatore WHERE user = '" . $user->get_id() . "' AND report = '".$report->get_id()."';";
            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        //elimina un singolo utente da un determinato report
        public function deleteUserFromALL(UserData $user, ReportData $report)
        {
            $query = "DELETE FROM report_giocatore WHERE user = '" . $user->get_id() . "';";
            return mysqli_query($this->connection, $query);
        }

        //elimina tutte le mention ad un determinato Report
        public function deleteReportMention(UserData $user, ReportData $report)
        {
            $query = "DELETE FROM report_giocatore WHERE report = '".$report->get_id()."';";
            $done = (bool) mysqli_query($this->connection, $query);
            return $done;
        }

        // Restituisce tutti i report (id) legati ad un utente
        public function getALLForUser($user){
            $reportsWITHuser = array();
          $user = clean_input($user);
          $query = "SELECT * FROM report_giocatore RG WHERE RG.user = '".$user."';";
          $query_result = mysql_query($this->connection, $query);

            if($query_result->num_rows){
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $report_id = $row["report_giocatore.report"];         
                    array_push($reportsWITHuser, $report_id);
                }
            }
            return $reportsWITHuser;
        }

        // Restituisce tutti gli utenti (user) legati ad un report
        public function getALLForReport($report){
            $usersINreport = array();
          $report = clean_input($report);
          $query = "SELECT * FROM report_giocatore RG WHERE RG.report = '".$report."';";
          $query_result = mysql_query($this->connection, $query);

            if(!$query_result->num_rows){
                while($row = mysqli_fetch_assoc($query_result))
                {
                    $user_id = $row["report_giocatore.user"];         
                    array_push($usersINreport, $user_id);
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
                $report_data = $query->mysqli_assoc(MYSQLI_ASSOC);
                return new ReportData($row["Report.id"], 
                                        $row["Report.titolo"], 
                                        $row["Report.sottotitolo"], 
                                        $row["Report.contenuto"], 
                                        $row["Report.autore"], 
                                        $row["Report.isExplorable"], 
                                        $row["Users.img_path"], 
                                        $row["Report.last_modified"]);
                /*
                return new UserData($user_data["username"], $user_data["name_surname"], $user_data["email"], $user_data["passwd"], $user_data["bithdate"], $user_data["img_path"]);

                $row_arr = $query->mysqli_fetch_assoc(MYSQLI_ASSOC);
                return $row_arr;
                */
            }
        }

    }
?>