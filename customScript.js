function showDialog(){
    document.getElementById("addBook").style.display="block";
    document.getElementById("addBook").classList.add('container');
}

async function getBooks(){
    var inputValue = document.getElementById("searchBook").value.toLowerCase();

    let fd = new FormData();
    fd.set('value', inputValue);
    const url = "http://localhost/dashboard/Backend/search.php";

    const res = await fetch(url,{
        method:'POST',
        body:fd
    })
    .then((r) => r.text())
    .then((text) => {
        document.getElementById("tableValues").innerHTML = "";
        document.getElementById("tableValues").innerHTML = text;
        console.log(text);
    });
}