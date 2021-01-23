const ThemeKey = 'CvTheme'
const ThemeDark = 'dark'
const ThemeDark_icon = '<i class="fas fa-moon" title="Passer au théme clair"></i>'
const ThemeLight = 'light'
const ThemeLight_icon = '<i class="fas fa-sun" title="Passer au théme sombre"></i>'

export default class ThemeSwitcher extends HTMLElement {
    connectedCallback() {
        this.innerHTML = ` <button class="app-nav-btn-text"></button>`
        const input = this.querySelector('button')

        input.addEventListener('click', e => {
            let nTheme = ThemeDark
            if (document.body.classList.contains("theme-" + ThemeDark)) {
                nTheme = ThemeLight;
                document.body.classList.remove("theme-" + ThemeDark)
            } else if (document.body.classList.contains("theme-" + ThemeLight))
                document.body.classList.remove("theme-" + ThemeLight)

            localStorage.setItem(ThemeKey, nTheme)
            document.body.classList.add(`theme-` + nTheme)
            if (nTheme === 'dark')
                input.innerHTML = ThemeDark_icon;
            else
                input.innerHTML = ThemeLight_icon;
        })


        let theme = localStorage.getItem(ThemeKey)
        if (theme === null && window.matchMedia('(prefers-color-scheme: dark)').matches)
            theme = 'dark';

        if (theme !== null) {
            document.body.classList.add("theme-" + theme)
            if (theme === ThemeDark)
                input.innerHTML = ThemeDark_icon;
            else
                input.innerHTML = ThemeLight_icon;
        } else
            input.innerHTML = ThemeLight_icon;
    }
}
