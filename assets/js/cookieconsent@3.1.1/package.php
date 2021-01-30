<?php

return array(

    'scripts' => array(

        'default' => array(

            'src'  => 'cookieconsent.min.js',

            'l10n' => array(

                'message' => __( 'This site uses cookies to improve your experience. By continuing to browse you are agreeing to our use of cookies.', 'lithe' ),

                'dismiss' => __( 'Dismiss', 'lithe' ),

                'link'    => __( 'Learn more', 'lithe' ),

            ),

            'after' => '
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#f9f9f9",
      "text": "#000000"
    },
    "button": {
      "background": "#037dc7",
      "text": "#ffffff"
    }
  },
  "content": {
    "message": cookieconsent_l10n.message,
    "dismiss": cookieconsent_l10n.dismiss,
    "link": cookieconsent_l10n.link,
    "href": "' . esc_js( get_home_url( null, 'about/privacy' ) ) . '"
  },
  "position": "bottom-right",
  "theme": "classic",
  "container": document.getElementById("site-footer"),
});
            ',

            'in_footer' => true,

        ),

    ),

    'styles' => array(

        'default' => array(

            'src'    => 'cookieconsent.min.css',

        ),

    ),

);