
/*Elküld egy kérést az ajax.php fájlnak,
majd a választ beírja az admin_box3 div-be.*/
console.log("JS betöltve!");
// FORM-ok megjelenítése és mentése az admin_box3 div-ben

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

function ujDolgozoMentes() {
    const form = document.getElementById("ujDolgozoForm"); //a böngésző megkeresi a formot az ID alapján
    const formData = new FormData(form); // a form adatainak összegyűjtése a FormData objektumba: nem kell kézzel írni a mezőket, a FormData automatikusan összegyűjti az összes mezőt a formból

    formData.append("action", "uj_dolgozo_mentes"); // hozzáadjuk az action mezőt a formData-hoz, ez mondja meg a php-nak, hogy melyik függvényt hívja meg

    /* AJAX kérést küldünk a szervernek (ajax.php) a form adataival */
    fetch("ajax.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text()) // válasz beolvasása szövegként
    .then(valasz => {    // itt kapjuk meg a szerver válaszát, amit a php visszaadott

        if (valasz.trim() === "OK") {

            // sikeres mentés után újratöltjük a dolgozók táblát
            fetch("ajax.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=a_dolgozok"
            })
            .then(r => r.text())
            .then(html => { // a szerver válaszát beírjuk az admin_box3 div-be
                document.querySelector(".admin_box3").innerHTML = html;
            });

        } else {
            alert("Hiba történt: " + valasz);
        }
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

function ujFelhasznaloMentes() {
    const form = document.getElementById("ujFelhasznaloForm"); //a böngésző megkeresi a formot az ID alapján
    const formData = new FormData(form); // a form adatainak összegyűjtése a FormData objektumba: nem kell kézzel írni a mezőket, a FormData automatikusan összegyűjti az összes mezőt a formból

    formData.append("action", "uj_felhasznalo_mentes"); // hozzáadjuk az action mezőt a formData-hoz, ez mondja meg a php-nak, hogy melyik függvényt hívja meg

    /* AJAX kérést küldünk a szervernek (ajax.php) a form adataival */
    fetch("ajax.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text()) // válasz beolvasása szövegként
    .then(valasz => {    // itt kapjuk meg a szerver válaszát, amit a php visszaadott

        if (valasz.trim() === "OK") {

            // sikeres mentés után újratöltjük a dolgozók táblát
            fetch("ajax.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=a_felhasznalok"
            })
            .then(r => r.text())
            .then(html => { // a szerver válaszát beírjuk az admin_box3 div-be
                document.querySelector(".admin_box3").innerHTML = html;
            });

        } else {
            alert("Hiba történt: " + valasz);
        }
    });
}


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



