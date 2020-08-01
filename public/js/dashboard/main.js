function getAllElementsWithAttribute(attribute) {
    var matchingElements = [];
    var allElements = document.getElementsByTagName('*');
    for (var i = 0, n = allElements.length; i < n; i++) {
        if (allElements[i].getAttribute(attribute) !== null) {
            // Element exists with attribute. Add to array.
            matchingElements.push(allElements[i]);
        }
    }
    return matchingElements;
}

//Minus cars
getAllElementsWithAttribute("data-btn-minus").forEach(function (value) {

    var btn = document.getElementById(value.getAttribute("data-btn-minus"));

    if (btn != null)
        btn.addEventListener("click", function () {

            if (value.classList.contains("disabled")) {
                value.classList.remove("disabled")
            } else {
                value.classList.add("disabled")
            }

        })

});


// Menu Left
document.querySelectorAll(".menu-item.menu-item_deploy").forEach(menu => {
    menu.querySelector(".menu-item-link").addEventListener("click", () => {
        if (menu.classList.contains("menu-item_select")) {
            applyStyleMenu(false, menu);
        } else {
            document.querySelectorAll(".menu-item.menu-item_deploy.menu-item_select").forEach(selectMenu => {
                applyStyleMenu(false, selectMenu);
            });
            applyStyleMenu(true, menu);
        }
    })
})

function applyStyleMenu(add = true, menu) {

    let item = menu.querySelector("ul.menu-item-sub_menu")
    item.style.display = "block";
    item.style.overflow = "hidden";
    if (add) {
        item.style.height = 0.2 + "px";
        item.style["padding-top"] = "0px";
        item.style["padding-bottom"] = "0px";
        item.style["margin-top"] = "0.2px";
        item.style["margin-bottom"] = "0px"
    } else {
        let offsetHeight = item.clientHeight - parseFloat(window.getComputedStyle(item, null).getPropertyValue('padding-top')) - parseFloat(window.getComputedStyle(item, null).getPropertyValue('padding-bottom'));
        item.style.height = offsetHeight + "px";
    }
    if (add)
        menu.classList.add("menu-item_select")

    setTimeout(() => {
        if (add) {
            item.style.height = item.scrollHeight + "px";
            item.style.removeProperty("padding-top")
            item.style.removeProperty("padding-bottom")
            item.style.removeProperty("margin-top")
            item.style.removeProperty("margin-bottom")
        } else {
            item.style.height = 0.2 + "px";
            item.style["padding-top"] = "0px";
            item.style["padding-bottom"] = "0px";
            item.style["margin-top"] = "0.2px";
            item.style["margin-bottom"] = "0px"
        }
        setTimeout(() => {
            item.style.removeProperty("height")
            item.style.removeProperty("display")
            item.style.removeProperty("overflow")
            item.style.removeProperty("padding-top")
            item.style.removeProperty("padding-bottom")
            item.style.removeProperty("margin-top")
            item.style.removeProperty("margin-bottom")
            if (!add)
                menu.classList.remove("menu-item_select")
        }, 300)
    }, 50)
}

//Nav Top
document.querySelectorAll('.nav-top_item.nav-top_dropdown').forEach(item => {
    item.querySelector("a").addEventListener('click', (event) => {
        item.classList.toggle('nav-top_dropdown_open');

    })
})
window.addEventListener('click', function (e) {
    document.querySelectorAll('.nav-top_item.nav-top_dropdown.nav-top_dropdown_open').forEach(item => {
        if (!item.contains(e.target))
            item.classList.remove('nav-top_dropdown_open');

    })
});
if (document.querySelector('#notif_markasread') != null)
    document.querySelector('#notif_markasread').addEventListener('click', function () {
        fetch(Routing.generate('profil_mark_notifs_as_reead'))
            .then(function () {
                if (document.querySelectorAll('ul.dropdown_notifications__list').length > 0) {
                    let newEl = document.createElement("p");
                    newEl.innerText = trad_notifications_type_title_no_notifications + ' ;(';
                    document.querySelector('div.nav-dropdown_notifications').replaceChild(newEl, document.querySelector('ul.dropdown_notifications__list'));
                    document.querySelector('div.nav-dropdown_notifications').parentElement.querySelector('a').classList.remove('nav-drop_badge');
                }
                document.querySelector('#notif_markasread').remove();
            })
            .then(function (myBlob) {

            });

    })

document.querySelector('#nav-responsive_open').addEventListener('click', toogleMenu)
document.querySelector('#nav-responsive_close').addEventListener('click', toogleMenu)

window.onresize = function () {
    if (window.innerWidth >= 1250) {
        document.getElementById("nav-left").style.removeProperty("width");
    }
};


function toogleMenu(event) {
    let item = document.querySelector('.nav-left');
    console.log(item, item.style.hasOwnProperty('width'));
    if (item.style.hasOwnProperty('width') && item.style.width != '')
        item.style.removeProperty('width')
    else
        item.style.width = '300px';
}