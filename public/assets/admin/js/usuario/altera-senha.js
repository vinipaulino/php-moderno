"use strict";

document.querySelector("#frmAlterarSenha").addEventListener("submit", (e) => {
    let pass = document.querySelector("#senha").value;
    let pass2 = document.querySelector("#confirmaSenha").value;

    if (pass.length < 7) {
        e.preventDefault();
        document.querySelector("#dvAlert").innerHTML = `
        <div class="alert alert-warning">
            As senhas dever ter ao menos sete caracteres.
        </div>`;
    }

    if (pass !== pass2) {
        e.preventDefault();
        document.querySelector("#dvAlert").innerHTML = `
        <div class="alert alert-warning">
            As senhas não correspodem.
        </div>`;
    }
});
