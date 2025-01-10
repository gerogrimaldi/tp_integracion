

function validar (username,password){
    var user = document.getElementById('username').value;
    var pass = document.getElementById('password').value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../model/UsuarioModel.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");


    if (user == username && pass == password){
        alert ("Validacion exitosa");
        // Envía los datos al PHP
        xhr.send("validacion=" + encodeURIComponent("true"));
    }else{
        alert("Error, usuario o contraseña incorrectos")
        // Envía los datos al PHP
        xhr.send("validacion=" + encodeURIComponent("false"));
    }

}
