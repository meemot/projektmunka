
/*Elküld egy kérést az ajax_dolgozok.php fájlnak,
majd a választ beírja az admin_box3 div-be.*/

document.addEventListener("DOMContentLoaded", function() {
    // Your code here:
    document.getElementById("dolgozok_menu").addEventListener("click", function(event) {
        event.preventDefault(); // az alapértelmezett link viselkedés megakadályozása

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "ajax_dolgozok.php", true);

        xhr.onload = function() {
            if (this.status === 200) {
                document.querySelector(".admin_box3").innerHTML = this.responseText;
            }
        };
        xhr.send();
    });
});