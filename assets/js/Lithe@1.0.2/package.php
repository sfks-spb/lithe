<?php

return array(

    'scripts' => array(

        'default' => array(

            'src' => 'bundle.js',

            'l10n' => array(

                'from_your_position' => __( 'from your position', 'lithe' ),

            ),

            'atts' => array( 'defer' => true ),

            'deps' => array( 'jquery', 'owlcarousel2', 'openapi' ),

            'before' => '
var TS=function(e,t,s,h){return class{constructor(t){this.selector=t,this.loaded=this.loaded.bind(this),this.switch=this.switch.bind(this),e.addEventListener("DOMContentLoaded",this.loaded)}loaded(){if(this.toggle=e.querySelector(this.selector),this.toggle)return this.toggle.addEventListener("click",this.switch);console.error("ThemeSwitcher: error: theme switcher element ["+this.selector+"] not found")}switch(){this.setTheme("dark"==this.theme?"light":"dark"),s.setItem("theme",this.theme)}animate(){if(this.toggle){var e=this.toggle;e.classList.add("switching");setTimeout(()=>{e.classList.remove("switching");},700)}}userPrefersDark(){return t.matchMedia&&t.matchMedia("(prefers-color-scheme: dark)").matches}addClass(e){h.classList.add(e)}removeClass(e){h.classList.remove(e)}setTheme(t){return this.animate(),this.removeClass(this.theme+"-theme"),e.dispatchEvent(new CustomEvent("ThemeChanged",{detail:t})),this.theme=t,this.addClass(t+"-theme"),this.theme}getTheme(){return this.theme?this.theme:s.getItem("theme")?this.setTheme(s.getItem("theme")):this.setTheme(this.userPrefersDark()?"dark":"light")}}}(document,window,localStorage,document.html||document.getElementsByTagName("html")[0]);
window.lithe = window.lithe || {};
window.lithe.locale = "' . esc_js( get_locale() ) . '";
window.lithe.home = "' . esc_js( get_home_url() ) . '";
window.lithe.ajax = ' . wp_json_encode( array( 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'lithe-ajax-nonce' ) ) ) . ';
window.lithe.rest = ' . wp_json_encode( lithe()->rest->get_settings() ) . ';
window.lithe.themeSwitcher = new TS(".theme-switcher");
window.lithe.theme = window.lithe.themeSwitcher.getTheme();
window.lithe.breakpoints = ' . lithe_get_breakpoints_json() . ';
            ',

            'condition' => ! is_admin(),

        ),

        'admin' => array(

            'src' => 'admin.js',

            'l10n' => array(

                'trainers_photo' => __( 'Trainer\'s Photo', 'lithe' ),

                'use_this_photo' => __( 'Use this photo', 'lithe' ),

            ),

            'condition' => is_admin(),

        ),

    ),

);