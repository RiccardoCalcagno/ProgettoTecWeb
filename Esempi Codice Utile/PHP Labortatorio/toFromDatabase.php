<?php
namespace DB;

class DBAccess{

    private const HOST_DB = "localhost";     //siccome è una costante, non ha bisogno del "$" prima
    private const USERNAME = "rcalcagn";
    private const PASSWORD = "<password di phpMyAdmin>";
    private const DATABASE_NAME = "rcalcagn";

    private $connection;

    public function openDBConnection(){

        $this->connection = mysqli_connect(DBAccess:HOST_DB,DBAccess:USERNAME,DBAccess:PASSWORD,DBAccess:DATABASE_NAME);

        if(mysqli_connect_errno($this->connection)){
            return false;       //  mi restituisce un numero che mi indica il tipo di errore diverso, posso comportarmi in maniere differenti
        }else{
            return true;
        }
    }

    public function closeDBConnection(){
        // NELLE SLIDE c'è l'implementazi.
    }


    public function getListaPersonaggi(){

        $querySelect = "SELECT * FROM protagonisti ORDER BY ID ASC";        //ASC in ordine ascendente
        $queryResult = mysqli_query($this->connection, $querySelect);
        //mysqli_query restituisce false se la query non funziona, Restituisce un mysqli_result oggetto

        if(mysqli_num_rows($queryResult)==0){
            return null;        // nel file personaggi mi comporterò addeguatamente sondando se è NULL
        }else{
            //non voglio restituire un msqli_query perchè voglio separazione con il database => voglio Array
            // mysqli_fetch muove l'iteratore (è come un pop) se arriva all'esterno restituisce valore NULL
            // c'è mysqli_fetch_assoc e mysqli_fetch_array  ( array posso accedere con [0] [1] ecc.  mentre con associativa trovo con il nome delle colonne )

            $listaPersonaggi = array();
            while($riga = mysqli_fetch_assoc($queryResult)){

                $singoloPersonaggio = array(
                    "Nome" => $riga['Nome'],     //attenzione alle diverse virgolette
                    "Immagine" => $riga['NomeImmagine'],
                    "AltImmagine" => $riga['AltImmagine'],
                    "Descrizione" => $riga['Descrizione']
                );
                array_push($listaPersonaggi, $singoloPersonaggio);
            }

            return $listaPersonaggi;        // ===>>>> la separazione è Totale
        }
    }

}



?>