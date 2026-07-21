
/*Elküld egy kérést az ajax.php fájlnak,
majd a választ beírja az admin_box3 div-be.*/
console.log("JS betöltve!");


document.addEventListener("DOMContentLoaded", () => { /* A html betöltődése után fut le a kód */

// Megkeressük az összes menü linket, és eseményfigyelőt adunk hozzá
    document.querySelectorAll(".menu_link").forEach(link => {
        link.addEventListener("click", (event) => {
            event.preventDefault();

            let action = link.dataset.action;

            /* AJAX kérést küldünk a szervernek */
            fetch("ajax.php", {
                method: 'POST',
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=" + action
            })
            .then(response => response.text()) /* A szerver válaszát szövegként olvassuk be */
            .then(data => {


                /* Megkeressük az admin_box3 vagy operator_box3 div-et, és beírjuk a választ */
                const targetBox = document.querySelector(".admin_box3") 
                               || document.querySelector(".operator_box3");

                if (targetBox) {
                    targetBox.innerHTML = data;
                }

            });
        });
    });

});


// FORM-ok megjelenítése az admin_box3 div-ben
function ujDolgozo() {
    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=uj_dolgozo_form"
    })
    .then(r => r.text())
    .then(html => {
        document.querySelector(".admin_box3").innerHTML = html;
    });
}

function ujFelhasznalo() {
    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=uj_felhasznalo_form"
    })
    .then(r => r.text())
    .then(html => {
        document.querySelector(".admin_box3").innerHTML = html;
    });
}
