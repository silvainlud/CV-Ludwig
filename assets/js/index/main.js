import ThemeSwitcher from './components/ThemeSwitcher'
import {loadBtnMenu} from './components/ResponsiveMenu'

customElements.define('theme-switch', ThemeSwitcher)

window.addEventListener("load", function () {
    console.log(document.querySelectorAll(".hambuger_icon"));
    document.querySelectorAll(".hambuger_icon").forEach(x => {
        loadBtnMenu(x);
    });
});

