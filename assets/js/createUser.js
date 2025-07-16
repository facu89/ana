document.addEventListener("DOMContentLoaded", function() {
    const btnStartSession = document.getElementById("btnStartSession");
    if (!btnStartSession) return;
    // Hashea la contraseña usando SHA-256
    // Retorna una promesa que resuelve con el hash de la contraseña
    btnStartSession.addEventListener("click", async function(e){
        var passwordInput = document.getElementById("inpPassword");
        var hashInput = document.querySelector("input[name='passwordHashed']");
        if(passwordInput && passwordInput.value !== "" && hashInput){
            e.preventDefault(); // Evita el submit por defecto
            const hashed = await hashPassword(passwordInput.value);
            console.log("Hash generado:", hashed); // DEBUG
            hashInput.value = hashed;
            // Envía el formulario manualmente
            btnStartSession.closest("form").submit();
        } else {
            console.log("No se encontró el campo de contraseña o el campo hash");
        }
    });
    async function hashPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    // Convierte el hash a un string hexadecimal
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}

});

// Hasheo la contrasenia en del lado del cliente para mayor seguridad