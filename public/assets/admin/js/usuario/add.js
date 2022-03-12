"use strict";

document.querySelector("#frmNovo").addEventListener("submit", (e) => {
    if (!validate()) {
        e.preventDefault();
    }
});

function validate() {
    console.log("AQUI");
    return true;
}
