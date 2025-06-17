# Backup

## Getting Started
### **WICHTIG** --> damit man unter _http://localhost:80_ die Anwendung erreicht, muss eine _index.php_ existieren. Diese ist die LandingPage! Diese Datei darf in keinen Ordner rein verschoben werden!
XAMPP -> https://www.apachefriends.org/de/download.html
VSCode -> https://code.visualstudio.com/download 
Extention -> https://marketplace.visualstudio.com/items?itemName=cweijan.vscode-mysql-client2 

HTML
Bei Rückgabe von DB immer **htmlspecialchars()** verwenden
``` html
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Page Title</title>
</head>
<body>

    <nav class="navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Buchverleih</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Start</a>
                </li>
            </ul>
        </div>
    </nav>

    <table class="table table-borderless table-striped table-hover">
        <thead>
            <tr>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    
                </td>
                <td>
                    <form method="post" action="Frontend/Edit.php">
                        <input type="hidden" name="Id" value="" />
                        <input type="submit" class="btn btn-warning" value="✏️"/>
                    </form>
                </td>
                <td>
                    <form method="post" action="delete.php">
                        <input type="hidden" name="Id" value="" />
                        <input type="submit" class="btn btn-danger" value="🗑️"/>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
```

PHP ini
```ini
[database]
dbUsername = "root";
dbPassword = "password123";
dbName = "lap";
serverName = "localhost";
port = 3306;
```

PHP mysql Verbindung aufbau
``` php
$ini = parse_ini_file(__DIR__ . '/php.ini');
$GLOBALS['connectionString'] = "mysql:host=" . $ini['serverName'] . ";port=" . $ini['port'] . ";dbname=" . $ini['dbName'] ."";
$GLOBALS['dbUsername'] = $ini['dbUsername'];
$GLOBALS['dbPassword'] = $ini['dbPassword'];
```

Prepared Statements 
```php
$query = "insert into table (col1, col2) values (?,?);";
$response = Operate($query, [$val1, $val2]);

//Funktionsdefinierung
function Operate($query, $parameterArray){
    try{
    $connection = new PDO($GLOBALS['connectionString'], $GLOBALS['dbUsername'], $GLOBALS['dbPassword']);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->beginTransaction();
    $insertStatement = $connection->prepare($query);
    $insertStatement->execute($parameterArray);

    $connection->commit();
    }
    catch(PDOException $e){
        $connection->rollBack();
        return $e->getMessage();
    } finally {
        $connection = null;
    }

    return "Success";
}
```

## PHP Datenbank zugriff
**Auslesen** _gibt immer ein Array zurück weil beim fetchAll(PDO::FETCHASSOC) mitgegeben wird._

**Auslesen mit Parameter**
```php
$query = "select * from table where col1 = ?";
$result = Query($query, [$val]);

function Query($query, $parameterArray){
    $result = [];

    try{
        $connection = new PDO($GLOBALS['connectionString'], $GLOBALS['dbUsername'], $GLOBALS['dbPassword']);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $selectStatement = $connection->prepare($query);
        $selectStatement->execute($parameterArray);
        $result = $selectStatement->fetchAll(PDO::FETCH_ASSOC); //gibt Array zurück
    }
    catch(PDOException $e){
        return $e->getMessage();
    } finally {
        $connection = null;
    }

    return $result[0];
}
```

**Auslesen ohne Paramter**
Like suche muss auch so gemacht werden. 

```php
$query = "select * from table";
$result = QueryAll($query);

function QueryAll($query){
    $result = [];

    try{
        $connection = new PDO($GLOBALS['connectionString'], $GLOBALS['dbUsername'], $GLOBALS['dbPassword']);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $connection->query($query)->fetchAll(PDO::FETCH_ASSOC); //gibt Array zurück
    }
    catch(PDOException $e){
        return $e->getMessage();
    } finally {
        $connection = null;
    }

    return $result;
}
```

## Cookies
**Backend**
'/' ist wichtig zu setzten, damit das von jeder Seite aus zugreifbar ist.
```php
$result = QueryData();
setcookie('data', json_encode($result), time()+3, '/');
```
**Frontend**
```php
if(isset($_COOKIE['cookieName'])){
    $data = json_decode($_COOKIE['cookieName']);
}

setcookie('cookieName',false); //kann man muss man aber nicht, wenn die Zeit richtig eingestellt ist
```

## User Rückmeldung geben
**Funktion**
```php 
function GetCurrentUrl(){
    $protocol = "";
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
        $protocol = "https";
    }
    else{
        $protocol = "http";
    }
    $protocol .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    return $protocol;
}

function CheckErrors($response, $message){
    $url = GetCurrentUrl();
    if(strpos($url, "$response")==true){
        echo '<p class="text-danger">' . $message . '</p>';
    }
}

//Redirect bei Fehler bauen
function CheckParams($baseUrl, $errorName, $errorType, $getParamNAme, $getParamVal){
    $redirect = new Redirect();

    if(!isset($getParamVal) || empty($getParamVal) || $getParamVal == ""){
        $redirect -> Redirect = true;
        $baseUrl = $baseUrl . "$errorName=$errorType&";
    }
    else{
        $baseUrl = $baseUrl ."$getParamNAme=" . $getParamVal . "&";
    }

    $redirect -> Url = $baseUrl;

    return $redirect;
}

class Redirect {
    public $Redirect;
    public $Url;
}
```

**Redirect im Backend**
```php
include_once('Functions.php');
$url = "index.php/?";

$bookIsSet = CheckParams($url, "title", "empty", "bookTitle", $bookTitle);
$bookYearIsSet = CheckParams($bookIsSet -> Url, "year", "empty", "bookYear", $bookYear);
$authorIsSet = CheckParams($bookYearIsSet -> Url, "auth", "empty", "bookAuthor", $author);

if(($bookIsSet -> Redirect) || ($bookYearIsSet -> Redirect) || ($authorIsSet -> Redirect)){
    $url = substr($authorIsSet -> Url, 0, strlen($authorIsSet -> Url) - 1);
    header("Location: $url");
    exit;
}
```

**Frontend Fehler ausgeben** 
```php
    CheckErrors("bookId=empty", "Buch konnte nicht gefunden werden");

    if(isset($_GET['bookTitle'])){
        echo '<input type="text" class="form-control" value="' . $_GET['bookTitle'] . '" name="bookTitle" required/>';
    }
    else{
        echo '<input type="text" class="form-control" placeholder="Titel" name="bookTitle" required/>';
    }
```

## OnSite Search
**Frontend**
OnInput - reagiert auf die Eingaben im Inputfeld ohne auf Enter drücken zu müssen was (onchange) wäre. 
``` html
<div class="container">
        <input type="text" class="form-control" Id="searchBook" oninput="getBook()" placeholder="Hier nach Buchtitel suchen" />
</div>
<div class="container" id="myTable">

</div>
<script type="text/javascript">
       async function getBook(){
            var input = document.getElementById("searchBook");
            input = input.value.toLowerCase();

            const url = "http://localhost/dashboard/...";
            let fd = new FormData();
            fd.set('value', input);

            const resp = await fetch(url,{
                method: 'POST',
                body: fd 
                })
                .then((response) => response.text())
                .then((text) => {
                    console.log(document.getElementById('myTable').innerHTML);
                    document.getElementById('myTable').innerHTML = text;
                    console.log(document.getElementById('myTable').innerHTML);
                });
        }
</script>
```

## PHP Klassen
**Klasse erstellen**
```php
class Car {
    public $Wheels;
    public $Windows;

    function setProperties($wheels, $windows){
        $this -> Wheels = $wheels;
        $this -> $Windows = $windows;
    }
}
```
**Auf Properties in Klasse zugreifen**
```php
include_once 'Car.php';

$car = new Car();
$windows = $car -> Windows;
```
**Auf Funktionen in Klasse zugreifen**
```php
include_once 'Car.php';

$car = new Car();
$car -> setProperties($value1, $value2);
```

### PHP Tricks
Um immer aktuelles Verzeichnis zu bekommen 
```php
 __DIR__ 
 ```

 ## Testprotokoll 
 Testfall_ID  
 Testbeschreibung  
 Annahmen und Vorraussetzugen --> z.B. was gegeben sein muss, damit man das ausführen kann  
 Testdaten --> beschreiben welche Daten benutzt wurden  
 Schritte aus sicht von Endbenutzers z.B.:   
    1. Öffnen von blabla  
    2. Eingeben von blalba  
    3. Klicken auf blabla  
Erwartetes Ergebnis     
Tatsächliches Ergebnis  
bestanden/ ! bestanden  