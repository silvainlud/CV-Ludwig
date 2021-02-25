export function toggleMenu(x) {

    x.classList.toggle("is-opened")
    document.querySelectorAll(".header_menu-list").forEach(y => {
        if (x.classList.contains("is-opened")) {
            y.classList.add("is-opened");
            y.animate([
                // keyframes
                {opacity: '0'},
                {opacity: '1'}
            ], {
                // timing options
                duration: 400,
                iterations: 1
            });
        } else {
            y.animate([
                // keyframes
                {opacity: '1'},
                {opacity: '0'}
            ], {
                // timing options
                duration: 400,
                iterations: 1
            });
            setTimeout(function () {
                y.classList.remove("is-opened");
            }, 400)

        }
    })
}

export function loadBtnMenu(x) {
    document.querySelectorAll(".header_menu-list a").forEach(y => {
        y.addEventListener('click', function (e) {
            if (x.classList.contains("is-opened"))
                toggleMenu(x, e);
        });
    })
    x.addEventListener("click", function (e) {
        e.preventDefault();
        toggleMenu(x, e);
    });
}


