import ThemeSwitcher from './components/ThemeSwitcher'
import {loadBtnMenu} from './components/ResponsiveMenu'

var Turbolinks = require("turbolinks")
Turbolinks.start()

customElements.define('theme-switch', ThemeSwitcher)

window.addEventListener("load", function () {
    document.querySelectorAll(".hambuger_icon").forEach(x => {
        loadBtnMenu(x);
    });
});

