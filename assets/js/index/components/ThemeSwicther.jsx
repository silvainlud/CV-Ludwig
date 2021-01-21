import React from "react";

const LocalStorageKey = 'CvThemeMode';
const CssThemeLight = 'theme-light';
const CssThemeDark = 'theme-dark';

class ThemeSwitcher extends React.Component {


    constructor(props) {
        super(props);
        this.state = {darkMode: this.getDefaultMode()};
        // this.UpdateDom();
    }

    getDefaultMode() {
        if (window.localStorage.getItem(LocalStorageKey) !== null)
            return window.localStorage.getItem(LocalStorageKey) === 'dark';
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    UpdateDom(updateLocalStorage = false, darkMode = this.state.darkMode) {
        if (darkMode) {
            if (updateLocalStorage)
                window.localStorage.setItem(LocalStorageKey, 'dark')

            if (document.body.classList.contains(CssThemeLight))
                document.body.classList.remove(CssThemeLight)
            document.body.classList.add(CssThemeDark)
        } else {
            if (updateLocalStorage)
                window.localStorage.setItem(LocalStorageKey, 'light')

            if (document.body.classList.contains(CssThemeDark))
                document.body.classList.remove(CssThemeDark)
            document.body.classList.add(CssThemeLight)
        }
    }

    ToggleMode() {
        this.UpdateDom(true, !this.state.darkMode);
        this.setState(() => ({darkMode: !this.state.darkMode}));
    }

    render() {
        return <button className="app-nav-btn-text" onClick={this.ToggleMode.bind(this)}
                       title={"Passer sur le thème " + (!this.state.darkMode ? 'sombre' : 'clair') + '.'}>
            {!this.state.darkMode ? <i className="far fa-sun"/> : <i className="far fa-moon"/>}
        </button>;
    }
}

export default ThemeSwitcher;