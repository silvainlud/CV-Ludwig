export function loadBtnMenu(x) {
    document.querySelectorAll(".header_menu-list").forEach(y => {
        y.querySelectorAll("a").forEach(a => {
            a.addEventListener("click", function () {
                x.click();
            });
        })
    });

    x.addEventListener("click", function (e) {
        e.preventDefault();
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
    });
}