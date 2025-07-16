# Backup

## Getting Started
### **WICHTIG** --> damit man unter _http://localhost:80_ die Anwendung erreicht, muss eine _index.php_ existieren. Diese ist die LandingPage! Diese Datei darf in keinen Ordner rein verschoben werden!
* XAMPP -> https://www.apachefriends.org/de/download.html
* MySql Workbench -> https://dev.mysql.com/downloads/workbench/
* VSCode -> https://code.visualstudio.com/download 
* Extention -> https://marketplace.visualstudio.com/items?itemName=cweijan.vscode-mysql-client2 

HTML
Bei Rückgabe von DB immer **htmlspecialchars(string, ENT_QUOTES, 'UTF-8')** verwenden, um "bösen Code" vorzubeugen
ENT_QUOTES --> Konvertiert " und ' in html Entities
" --> &quot
' --> &apos

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