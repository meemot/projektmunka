
/*Elküld egy kérést az ajax_dolgozok.php fájlnak,
majd a választ beírja az admin_box3 div-be.*/

document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".menu_link").forEach(link => {
        link.addEventListener("click", (event) => {
            event.preventDefault();

            let action = link.dataset.action;

            fetch("ajax.php", {
                method: 'POST',
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=" + action
            })
            .then(response => response.text())
            .then(data => {

                const targetBox = document.querySelector(".admin_box3") 
                               || document.querySelector(".operator_box3");

                if (targetBox) {
                    targetBox.innerHTML = data;
                }

            });
        });
    });

});

