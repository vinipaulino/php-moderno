"use strict";

const minLoginLength = 6;
const minPasswordLength = 7;

document.querySelector("#frmLogin").addEventListener("submit", (event) => {
    if (document.querySelector("#usuario").value.length < minLoginLength) {
        alert("Usuário inválido.");
        event.preventDefault();
        return;
    }

    if (document.querySelector("#senha").value.length < minPasswordLength) {
        alert("Senha inválida.");
        event.preventDefault();
        return;
    }
});
