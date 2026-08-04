
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
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
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
                const box = document.querySelector(".admin_box3");
                box.innerHTML = html;
                box.scrollTo({ top: 0, behavior: "smooth" });
            });

        } else {
            alert("Hiba történt: " + valasz);
        }
    });
}

function ujDolgozoMegse() {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=a_dolgozok"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function dolgozoSzerkesztes(id) {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=dolgozo_szerkesztes_form&id=" + id
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function modDolgozoMentes() {
    
    const form = document.getElementById("modDolgozoForm");
    const formData = new FormData(form);

    // KILÉPETT checkbox → dátum vagy üres
    let kilepettChecked = form.querySelector("input[name='kilepett']").checked;

    if (kilepettChecked) {
        let datum = new Date().toISOString().slice(0, 19).replace('T', ' ');
        formData.set("kilepett", datum);   // dátumot írunk be
    } else {
        formData.set("kilepett", "");      // üres → PHP NULL-t fog menteni
    }

    formData.append("action", "update_dolgozo");

    fetch("ajax.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text())
    .then(valasz => {

        if (valasz.trim() === "OK") {

            fetch("ajax.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=a_dolgozok"
            })
            .then(r => r.text())
            .then(html => {
                const box = document.querySelector(".admin_box3");
                box.innerHTML = html;
                box.scrollTo({ top: 0, behavior: "smooth" });
            });

        } else {
            alert(valasz);
        }
    });
}

function modDolgozoMegse() {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=a_dolgozok"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
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
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
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
                const box = document.querySelector(".admin_box3");
                box.innerHTML = html;
                box.scrollTo({ top: 0, behavior: "smooth" });
            });

        } else {
            alert("Hiba történt: " + valasz);
        }
    });
}

function ujFelhasznaloMegse() {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=a_felhasznalok"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function felhasznaloSzerkesztes(id) {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=felhasznalo_szerkesztes_form&id=" + id
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function modFelhasznaloMentes() {

    const form = document.getElementById("modFelhasznaloForm");
    const formData = new FormData(form);

    // TÖRÖLVE checkbox → dátum vagy üres
    let torolveChecked = form.querySelector("input[name='torolve']").checked;

    if (torolveChecked) {
        let datum = new Date().toISOString().slice(0, 19).replace('T', ' ');
        formData.set("torolve", datum);   // dátumot írunk be
    } else {
        formData.set("torolve", "");      // üres → PHP NULL-t fog menteni
    }

    formData.append("action", "update_felhasznalo");

    fetch("ajax.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text())
    .then(valasz => {

        if (valasz.trim() === "OK") {

            fetch("ajax.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=a_felhasznalok"
            })
            .then(r => r.text())
            .then(html => {
                const box = document.querySelector(".admin_box3");
                box.innerHTML = html;
                box.scrollTo({ top: 0, behavior: "smooth" });
            });

        } else {
            alert(valasz);
        }
    });
}

function modFelhasznaloMegse() {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=a_felhasznalok"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function ujEszkozok() {
    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=uj_eszkoz_form"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function ujEszkozMentes() {
    const form = document.getElementById("ujEszkozForm"); //a böngésző megkeresi a formot az ID alapján
    const formData = new FormData(form); // a form adatainak összegyűjtése a FormData objektumba: nem kell kézzel írni a mezőket, a FormData automatikusan összegyűjti az összes mezőt a formból

    formData.append("action", "uj_eszkoz_mentes"); // hozzáadjuk az action mezőt a formData-hoz, ez mondja meg a php-nak, hogy melyik függvényt hívja meg

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
                body: "action=a_eszkozok"
            })
            .then(r => r.text())
            .then(html => { // a szerver válaszát beírjuk az admin_box3 div-be
                const box = document.querySelector(".admin_box3");
                box.innerHTML = html;
                box.scrollTo({ top: 0, behavior: "smooth" });
            });

        } else {
            alert("Hiba történt: " + valasz);
        }
    });
}

function ujEszkozMegse() {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=a_eszkozok"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function eszkozSzerkesztes(id) {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=eszkoz_szerkesztes_form&id=" + id
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function modEszkozMentes() {

    const form = document.getElementById("modEszkozForm");
    const formData = new FormData(form);

    formData.append("action", "update_eszkoz");

    fetch("ajax.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text())
    .then(valasz => {

        if (valasz.trim() === "OK") {

            fetch("ajax.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=a_eszkozok"
            })
            .then(r => r.text())
            .then(html => {
                const box = document.querySelector(".admin_box3");
                box.innerHTML = html;
                box.scrollTo({ top: 0, behavior: "smooth" });
            });

        } else {
            alert("Hiba: " + valasz);
        }
    });
}

function modEszkozMegse() {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=a_eszkozok"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
        box.scrollTo({ top: 0, behavior: "smooth" });
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

// a kategoria select változását figyeljük, és a tipus selectet frissítjük az ajax.php-ból kapott adatokkal
// új eszköz létrehozásakor csak a kiválasztott kategóriához tartoz ó típusok jelenjenek meg
document.addEventListener("change", function(e) {
    if (e.target && e.target.id === "eszkoz_kategoria") {

        const kat = e.target.value;
        const tipusSelect = document.getElementById("tipus");

        fetch("ajax.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "action=tipusok_kategoria_szerint&kategoria=" + kat
        })
        .then(r => r.text())
        .then(html => {
            tipusSelect.innerHTML = html;
        });
    }
});





