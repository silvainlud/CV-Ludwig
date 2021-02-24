import ThemeSwitcher from './components/ThemeSwitcher'
import {loadBtnMenu} from './components/ResponsiveMenu'

customElements.define('theme-switch', ThemeSwitcher)

window.addEventListener("load", function () {
    document.querySelectorAll(".hambuger_icon").forEach(x => {
        loadBtnMenu(x);
    });
});

