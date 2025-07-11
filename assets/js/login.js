
const btnStartSession= document.getElementById("btnStartSession");
// Hashea la contraseña usando SHA-256
// Retorna una promesa que resuelve con el hash de la contraseña
btnStartSession.addEventListener("click",function(){
    var password =  document.getElementById("inpPassword");
    if(password != ""){
        
    }
})
async function hashPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    // Convierte el hash a un string hexadecimal
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}

// Hasheo la contrasenia en del lado del cliente para mayor seguridad