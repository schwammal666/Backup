<?php 
class Data {
    public $DbName;
    public $Port;
    public $Host;
    public $Username;
    public $Password;
    private $iniFilePath = __DIR__ . "/php.ini"; //Damit außerhalb der Klasse der Ablageort der Konfig unbekannt bleibt

    //Zum Initialisieren des DB Objektes
    function __construct(){
        $data = parse_ini_file($this -> iniFilePath);
        $this -> DbName = $data['dbName'];
        $this -> Port = $data['port'];
        $this -> Host = $data['server'];
        $this -> Username = $data['username'];
        $this -> Password = $data['password'];
    }

    function __destruct(){;} //Wird automatisch aufgerufen am Ende vom Skript

    //Zum ausführen von DML (insert, update, delete)
    function Operate($query, $parameterArray){
        $connection = new PDO("mysql:host$this->Host;port=$this->Port;dbname=$this->DbName", $this->Username, $this->Password);
        $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try{
            $connection -> beginTransaction(); //Stoppt Autocommit -> macht Änderungen im Fall von Fehler wieder rückgängig

            $statement = $connection -> prepare($query);
            $statement -> execute($parameterArray);
        
            $connection-> commit(); //Wenn bis hier kein Fehler war, dann Änderungen bestätigen
        }catch(PDOException $e){
            $connection -> rollBack(); //Wenn oben Fehler war, Änderungen rückgängig
            return $e -> getMessage();
        }
        finally{
            $connection = null;
        }

        return "Success";
    }

    //Zum ausführen von Selects ohne Parameter und zum ausführen von Like Selects
    function Query($query){
        $c = new PDO("mysql:host$this->Host;port=$this->Port;dbname=$this->DbName", $this->Username, $this->Password);
        $c -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = [];
        
        try{
            $result = $c->query($query)->fetchAll(PDO::FETCH_ASSOC); //Daten als Array
        }catch(PDOException $e){
            return $e -> getMessage();
        }finally{
            $c = null;
        }

        return $result;
    }

    //Zum ausführen von Selects mit Parameter
    function QueryParams($query, $parameterArray){
        $c = new PDO("mysql:host=$this->Host;port=$this->Port;dbname=$this->DbName", $this->Username, $this->Password);
        $c-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $result = [];
        
        try{
            $statement = $c->prepare($query);
            $statement ->execute($parameterArray);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC); //Daten als Array
            
        }catch(PDOException $e){
            return $e->getMessage();
        }finally{
            $c = null;
        }

        return $result;
    }
}

?>