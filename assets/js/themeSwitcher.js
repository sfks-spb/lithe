var TS = function(d, w, s, h) {
    return class ThemeSwitcher {

        constructor(selector) {
            this.selector = selector;
            this.loaded = this.loaded.bind(this);
            this.switch = this.switch.bind(this);
            d.addEventListener("DOMContentLoaded", this.loaded);
        },

        loaded() {
            this.toggle = d.querySelector(this.selector);

            if (this.toggle) {
                return this.toggle.addEventListener("click", this.switch);
            }

            console.error("ThemeSwitcher: error: theme switcher element [" + this.selector + "] not found")
        },

        switch () {
            this.setTheme("dark" == this.theme ? "light" : "dark");
            s.setItem("theme", this.theme);
        },

        animate() {
            if (this.toggle) {
                var toggle = this.toggle;
                toggle.classList.add("switching");
                setTimeout(() => {
                    toggle.classList.remove("switching");
                }, 700)
            }
        },

        userPrefersDark() {
            return w.matchMedia && w.matchMedia("(prefers-color-scheme: dark)").matches
        },

        addClass(classname) {
            h.classList.add(classname)
        },

        removeClass(classname) {
            h.classList.remove(classname)
        },

        setTheme(theme) {
            this.animate();
            this.removeClass(this.theme + "-theme");
            this.theme = theme;
            this.addClass(theme + "-theme");
            d.dispatchEvent(new CustomEvent( "ThemeChanged", { detail: theme } ));
            return this.theme;
        },

        getTheme() {
            var theme = this.theme || s.getItem("theme")

            if (!theme) {
                theme = this.userPrefersDark() ? "dark" : "light"
            }

            return this.setTheme(theme);
        }
    }
}(document, window, localStorage, document.html || document.getElementsByTagName("html")[0]);