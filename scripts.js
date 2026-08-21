
/*Elküld egy kérést az ajax.php fájlnak,
majd a választ beírja az admin_box3 div-be.*/
console.log("JS betöltve!");
// FORM-ok megjelenítése és mentése az admin_box3 div-ben


// xxxxxxxxxxxxxxxxxxxx
// -=ADMIN FÜGGVÉNYEK=-
// xxxxxxxxxxxxxxxxxxxx

// ====== Dolgozók =======

function ujDolgozo() {

    window.scrollTo(0, 0); // görgetés az oldal tetejére, hogy a felhasználó az elejéről lássa a formot

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=uj_dolgozo_form"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
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
    });
}

function modDolgozoMentes() {
    
    const form = document.getElementById("modDolgozoForm");
    const formData = new FormData(form);

    console.log("checkbox:", form.querySelector("input[name='kilepett']"));
    console.log("checked:", form.querySelector("input[name='kilepett']").checked);


    // KILÉPETT checkbox → pipálva van vagy sem
    let kilepettChecked = form.querySelector("input[name='kilepett']").checked;
    formData.set("kilepett", kilepettChecked ? "1" : "0");

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
    });
}


// ====== Felhasználók ======

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
    });
}

function modFelhasznaloMentes() {

    const form = document.getElementById("modFelhasznaloForm");
    const formData = new FormData(form);

    // TÖRÖLVE checkbox → dátum vagy üres
    let torolveChecked = form.querySelector("input[name='torolve']").checked;
    formData.set("torolve", torolveChecked ? "1" : "0");

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
    });
}


// ===== Eszközök =====

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
    });
}

// =============================================================================================================================
// ===== ÚJ KIADÁS =====

function ujKiadas() { // katt az új kiadás gombra
    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=uj_kiadas_form"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
    });
}

function ujKiadasMentes() {
    const formData = new FormData(document.getElementById('ujKiadasForm'));

    formData.append("action", "kiadas_mentes");

    fetch('ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.text())
    .then(valasz => {

        // sikeres mentés esetén a PHP visszaadja pl.:
        // "A kiadás sikeresen mentve (ID: 123)"
        alert(valasz);

        if (!valasz.startsWith("HIBA")) {
            
            // UGYANAZ, mint az új eszköznél
            // csak itt a KIADÁS modult töltjük újra
            fetch("ajax.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=a_kiadas"
            })
            .then(r => r.text())
            .then(html => {
                const box = document.querySelector(".admin_box3");
                box.innerHTML = html;
            });
        }
    });
}

function ujKiadasMegse() {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=a_kiadas"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
    });
}

// KIADÁSBAN, az eszköz hozzáadása gomb!
let hozzaadottEszkozok = [];
function hozzaadEszkoz() {
    const select = document.getElementById("eszkoz_id");
    const eszkozId = select.value;

    if (!eszkozId) {
        alert("Előbb válassz ki egy eszközt!");
        return;
    }

    const option = select.options[select.selectedIndex];
    const eszkozTipus = option.dataset.tipus || "";
    const eszkozText = select.options[select.selectedIndex].text;

    const tbody = document.querySelector("#kiadottEszkozok tbody");

    const row = document.createElement("tr");
    row.innerHTML = `
        <td>${eszkozTipus}</td>
        <td>${eszkozText}</td>
        <td><button class='btn btn-danger btn-sm' onclick='torolEszkoz(this)'>Törlés</button>
            <input type='hidden' name='eszkozok[]' value='${eszkozId}'>
        </td>
    `;

    tbody.appendChild(row);
    hozzaadottEszkozok.push(eszkozId); //a kiválasztott eszközök tárolása a hozzaadottEszkozok-be (634. sor)


    // HOZZÁADÁS UTÁN A LEGÖRDÜLŐK VISSZAÁLLÍTÁSA ALAPÉRTELMEZETTRE
    document.getElementById("tipus").value = "";
    document.getElementById("eszkoz_id").innerHTML = "<option value=''>--Előbb válassz típust!--</option>";

    // Ha kiürült a lista, visszaállítjuk
    if (select.options.length === 0) {
        select.innerHTML = "<option value=''>--Nincs több eszköz--</option>";
    }
}


// Visszavét gomb

function Visszavet(reszletek_id) {
    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=visszavet_form&reszletek_id=" + reszletek_id
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
    });
}

function VisszavetMentes(){
    const formData = new FormData(document.getElementById('visszavet_form'));

    formData.append("action", "VisszavetMentes");

    fetch('ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.text())
    .then(valasz => {

        // sikeres mentés esetén a PHP visszaadja pl.:
        // "A kiadás sikeresen mentve (ID: 123)"
        alert(valasz);

        if (!valasz.startsWith("HIBA")) {
            
            // UGYANAZ, mint az új eszköznél
            // csak itt a KIADÁS modult töltjük újra
            fetch("ajax.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=a_kiadas"
            })
            .then(r => r.text())
            .then(html => {
                const box = document.querySelector(".admin_box3");
                box.innerHTML = html;
            });
        }
    });

}

function VisszavetMegse() {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=a_kiadas"
    })
    .then(r => r.text())
    .then(html => {
        const box = document.querySelector(".admin_box3");
        box.innerHTML = html;
    });
}




// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
// x                        LISTENER (ESEMÉNYFIGYELŐ)!!!                          x
// x  Ez tölti be AJAX-szal a modulokat a tartalom dobozba (admin/operator_box3)  x
// x           Kell a menüpontok működéséhez, figyeli a menü linkeket             x
// x                        Lekéri a data-action értékét                          x
// x                      AJAX kérést küld az ajax.php-nak                        x
// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
document.addEventListener("DOMContentLoaded", () => { // megvárja, hogy betöltődjön a teljes html

    // Megkeressük az összes menü linket, és eseményfigyelőt adunk hozzá
    document.querySelectorAll(".menu_link").forEach(link => { //megkeresi az összes menü-linket
        link.addEventListener("click", (event) => { // hozzáad egy eseményfigyelőt
            event.preventDefault(); // aaz oldal újratöltésének megakadályozása

            let action = link.dataset.action; // lekéri a data-action értéket

            /* AJAX kérést küldünk a szervernek (ajax.php) */
            fetch("ajax.php", {
                method: 'POST',
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=" + action
            })
            .then(response => response.text()) /* A szerver válaszát szövegként olvassuk be */
            .then(data => {

                /* Megkeressük az admin_box3 vagy operator_box3 div-et, és beírjuk a visszakapott html-t */
                const targetBox = document.querySelector(".admin_box3") 
                               || document.querySelector(".operator_box3");

                if (targetBox) {
                    targetBox.innerHTML = data;

                    targetBox.scrollTo({ top: 0, behavior: "smooth" }); // görgetés az oldal tetejére, hogy a felhasználó az elejéről lássa a tartalmat
                }

            });
        });
    });

});


// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
// x                                LISTENER!!!                               x
// x  ÚJ ESZKÖZ LÉTREHOZÁSakor figyeli, hogy mikor változik a KATEGÓRIA mező  x
// x    A változásnak megfelelően tölti be az eszköz típust a legördülőbe     x
// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
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



// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
// x                           LISTENER!!!                          x
// x ÚJ ESZKÖZ KIADÁSakor figyeli, hogy mikor változik a tipus mező x
// x  A változásnak megfelelően tölti be az azonosító legördülőbe   x
// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
document.addEventListener("change", function(e) {  // esemény figyelése, változott e valami a dokumentumban

    if (e.target && e.target.id === "tipus") { // csak akkor fut, ha a tipus selectben változott valami

        const tipus = e.target.value; //lekéri a kiválasztot tipus ID-jét
        const eszkozSelect = document.getElementById("eszkoz_id");

        console.log("TIPUS:", tipus);
        console.log("ESZKOZ SELECT:", eszkozSelect);

        // AJAX hívás indul az adatbázis felé
        fetch("ajax.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "action=eszkozok_tipus_szerint&tipus=" + encodeURIComponent(tipus)
        })
        .then(r => r.text())
        .then(html => {

            console.log("AJAX VÁLASZ:");
            console.log(html);

            eszkozSelect.innerHTML = html; // a php által visszaadott html beillesztése a legördülőbe (törli a régit, beilleszti az ujat)
            
        // már hozzáadott eszközök eltávolítása a listából
            for (let id of hozzaadottEszkozok) {
                let opt = eszkozSelect.querySelector(`option[value="${id}"]`);
                if (opt) opt.remove();
            }
        })
        .catch(error => {
            console.error("AJAX HIBA:", error);
        });
    }
});


// figyelő a visszavét gombhoz a kiadás menüben (ez futtatja le a function-t)
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-visszavet")) {
        const reszletek_id = e.target.dataset.id;
        Visszavet(reszletek_id);
    }
});

// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
// x                           LISTENER!!!                          x
// x           Az oszlopok tetején lévő SZŰRŐ működése              x
// x           Univerzális, mindegyik táblában működik              x
// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
document.addEventListener("keyup", runFilters);
document.addEventListener("change", runFilters);

function runFilters(e) {
    // Csak akkor fut, ha filter mezőben gépelünk vagy checkbox pipálás van
    if (
        !e.target.classList.contains("filter-input") &&
        !e.target.classList.contains("filter-kilepett")
    ) return;

    let table = e.target.closest("table");
    let rows = table.querySelectorAll("tbody tr");

    let textFilters = table.querySelectorAll(".filter-input");
    let kilepettFilter = table.querySelector(".filter-kilepett");

    rows.forEach(row => {
        let visible = true;

        // 1) Szöveges mezők szűrése
        textFilters.forEach(input => {
            let colIndex = input.dataset.col;
            let filterValue = input.value.toLowerCase().trim();

            if (filterValue !== "") {
                let cellText = row.children[colIndex].textContent.toLowerCase();
                if (!cellText.includes(filterValue)) {
                    visible = false;
                }
            }
        });

        // 2) Kilépett checkbox szűrés
        if (kilepettFilter.checked) {
            let colIndex = kilepettFilter.dataset.col;
            let cellText = row.children[colIndex].textContent.trim();

            // Csak akkor látszik, ha VAN érték a kilépett oszlopban
            if (cellText === "") {
                visible = false;
            }
        }

        row.style.display = visible ? "" : "none";
    });
}




// KEZDŐOLDAL
document.addEventListener("DOMContentLoaded", () => {

    fetch("ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=kezdolap"
    })
    .then(r => r.text())
    .then(html => {
        document.querySelector(".admin_box3").innerHTML = html;
    });

});



// új kiadás - hozzáadott eszközt táblázatban - törlés gomb
function torolEszkoz(btn) {
    btn.closest("tr").remove();
}

