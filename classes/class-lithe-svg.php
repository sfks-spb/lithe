<?php

if ( ! class_exists( 'Lithe_SVG' ) ) {

    class Lithe_SVG {

        /**
         * Gets svg code for icon
         *
         * @param  string $icon
         * @param  array  $attributes
         *
         * @return string|null
         */
        public static function get_svg( string $icon, array $attributes = array() ): ?string {
            $types = array(
                'icon',
                'logo',
            );

            list( $type, $name ) = explode( '-', $icon, 2 );

            if ( ! in_array( $type, $types ) ) {
                return null;
            }

            $svg = self::${ $type . 's' }[$name];

            if ( $svg ) {

                $attributes['data-icon'] = $icon;

                self::append_attributes( $svg, $attributes );

                return $svg;

            }
        }

        /**
         * Appends attributes to svg element
         *
         * @param  string $svg
         * @param  array  $attributes
         *
         * @return void
         */
        public static function append_attributes( string &$svg, array $attributes ): void {
            if ( ! empty( $attributes ) ) {

                $attribute_str = '';
                $additional_tags = '';

                foreach ( $attributes as $key => $value ) {

                    if ( in_array( $key, self::$allowed_attributes ) ) {

                        $attribute_str .= ' ' . $key . '="' . esc_attr( $value ) . '"';

                        if ( 'title' === $key ) {
                            $label_id = self::get_random_id( $attributes[ 'data-icon' ] );
                            $additional_tags = '<title id="' . $label_id . '">' . $attributes['title'] . '</title>';
                            $attribute_str .= ' aria-labeledby="' . esc_attr( $label_id ) . '"';
                        }

                    }

                }

                $svg = preg_replace( '/^<svg\s+([^>]+)>/s', '<svg $1 ' . $attribute_str . '>' . $additional_tags, $svg );
            }
        }

        /**
         * Gets random id string
         *
         * @param  string|null $prefix
         *
         * @return string
         */
        public static function get_random_id( ?string $prefix ): string {
            return $prefix . '-' . wp_generate_password(12, /* $special_chars */ false);
        }

        /**
         * Contains list of allowed attributes
         *
         * @var array
         */
        public static $allowed_attributes = array(
            'id',
            'title',
            'data-icon',
            'aria-labeledby',
        );

        /**
         * Contains list of svg logos
         *
         * @var array
         */
        public static $logos = array(
            'plain'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"><defs><clipPath id="a"><circle cx="150" cy="150" r="150" fill="none"></circle></clipPath></defs><circle cx="150" cy="150" r="150" fill="currentColor"></circle><g clip-path="url(#a)" fill="var(--lt-secondary-logo-color, white)"><path d="M176.4 187.7c5.715-5.143 11.36-10.37 17.78-14.66 6.505-4.342 13.3-8.056 21.21-9.049 6.765-.85 13.4.2 19.94 1.853 14.49 3.66 27.5 10.6 40.18 18.25 10.44 6.298 20.54 13.07 29.24 21.71a21.352 21.352 0 014.262 5.446c1.404 2.799.204 5.085-2.903 5.55-3.34.5-6.524-.369-9.68-1.279-9.267-2.672-17.88-7.056-26.96-10.26-10.12-3.576-20.29-6.918-30.65-9.777-14-3.862-27-2.535-39.09 5.755a23.843 23.843 0 00-5.02 3.183l-7.793 5.158a28.705 28.705 0 00-5.934 4.18c-6.261 4.31-12.48 8.688-18.8 12.91a88.45 88.45 0 01-16.43 9.01 16.721 16.721 0 01-6.714 1.5c-3.192-.106-4.711-2.285-3.695-5.33a17.544 17.544 0 014.854-6.883c5.782-5.605 11.74-11.03 17.24-16.92.2-.215.368-.46.552-.692a20.787 20.787 0 004.43-4.759l9.705-10.59a16.434 16.434 0 004.267-4.297z"></path><path d="M158 207.3c-10.83-4.116-21.68-8.08-33.47-8.432-8.881-.265-16.89 2.398-24.46 6.841-10.4 6.11-19.78 13.66-29.5 20.74-10.63 7.735-21.21 15.55-32.24 22.72a34.798 34.798 0 01-9.83 4.785c-5.097 1.367-8.096-1.498-6.915-6.672.85-3.721 2.951-6.83 5.175-9.843 4.99-6.76 11.08-12.53 16.89-18.54a361.819 361.819 0 0142.9-38.02c7.267-5.457 15.18-9.86 24.2-11.75 9.902-2.08 19.63-.625 29.21 2.06 11.51 3.228 21.98 8.86 32.47 14.41 1.319.697 45.84 24.21 45 33.53-6.173 7.441-35.74-1.353-59.44-11.82zm-53.16-42.65c-6.268-.27-13.56-4.38-17.99-12.72-4.183-7.87-4.228-15.96.622-23.65 3.525-5.592 8.896-9.103 14.96-11.28 23.1-8.271 46.14-16.76 70.16-22.15a39.066 39.066 0 0111.33-1.12c2.085.148 4.422.253 5.237 2.653.783 2.306-.42 4.24-2.018 5.82a26.68 26.68 0 01-8.38 5.088c-11.28 4.938-22.89 9.083-34 14.42-4.574 2.2-9.078 4.544-13.66 6.738-1.215.582-1.388 1.074-.827 2.35 8.554 19.47-9.159 34.35-25.44 33.84zm.56-102.8c21.63-.067 32.51 26.06 17.24 41.37s-41.43 4.486-41.41-17.14c-.061-13.38 10.79-24.26 24.17-24.23zM171.7 173.6a22.246 22.246 0 01-8.805-1.319c-2.637-1.044-4.616-2.718-4.94-5.799-.364-3.458.413-6.395 3.613-8.277.762-.47 1.554-.89 2.37-1.258a13.522 13.522 0 007.101-6.83c2.545-5.294 5.52-10.17 10.57-13.62a14.181 14.181 0 005.69-8.866 2.772 2.772 0 012.783-2.454 2.648 2.648 0 012.714 2.226c.9 3.723 3.208 6.517 5.95 9.014a31.151 31.151 0 016.202 7.03c3.807 6.454 3.066 13.07-2.167 18.46-6.085 6.262-13.85 9.157-22.18 10.81a40.75 40.75 0 01-8.9.886zM36.36 175.7c-4.76-.026-6.313-2.7-3.922-6.888a28.15 28.15 0 016.28-7.25c10.69-9.21 21.15-18.71 32.62-26.97a32.296 32.296 0 017.112-4.205 2.564 2.564 0 012.926.221 2.679 2.679 0 01.713 3.084 20.247 20.247 0 00.203 14.24 2.735 2.735 0 01-1.104 3.533c-12.77 8.29-25.4 16.83-39.42 22.96a14.414 14.414 0 01-5.413 1.276zM274.8 145.3c-.065 1.236-.071 3.182-.285 5.104-1.117 10.03-8.075 15.3-16.16 19.59a6.552 6.552 0 01-5.014.45 4.52 4.52 0 01-3.194-5.584 10.75 10.75 0 011.537-3.457 21.583 21.583 0 012.627-3.612c5.055-4.914 7.539-10.93 8.197-17.87.22-2.312.97-4.543 2.191-6.519a5.073 5.073 0 014.832-2.649c2.09.107 3.144 1.526 3.854 3.267 1.386 3.398 1.306 6.998 1.41 11.28z"></path><path d="M82.96 244.4c1.06.109 2.33-.142 2.689 1.156.351 1.268-.769 1.955-1.695 2.314-6.118 2.37-12.09 5.134-18.37 7.077-.92.284-2.047.612-2.638-.432-.633-1.118.11-2.047 1.011-2.66a48.8 48.8 0 0119-7.455zM178.9 227.4c.448 0 .896-.007 1.344.001.783.015 1.558.18 1.846.984.349.978-.423 1.509-1.083 1.987a5.08 5.08 0 01-1.397.691q-8.501 2.766-17.02 5.489a8.06 8.06 0 01-1.752.327c-.746.065-1.575.112-1.938-.734a1.818 1.818 0 01.728-2.167 4.441 4.441 0 01.693-.565 49.11 49.11 0 0118.58-6.013zM248.8 205.1c1.116.27 2.562-.136 2.89 1.3.326 1.418-.95 1.969-1.98 2.308-6.088 2.003-12.08 4.315-18.3 5.885-1.042.263-2.38.676-2.915-.713-.507-1.32.622-2.103 1.572-2.646a51.13 51.13 0 0118.74-6.134zm-147.7 25.38a15.82 15.82 0 012.266.184c1.408.357 1.73 1.489.664 2.473a5.378 5.378 0 01-1.916 1.105c-5.59 1.85-11.19 3.68-16.81 5.448-1.308.411-3.123 1.135-3.85-.376-.843-1.754 1.009-2.605 2.284-3.27a52.54 52.54 0 0117.36-5.563zM194.7 204.4c6.355 3.887 12.99 7.383 18.3 12.82a220.4 220.4 0 01-26.09-7.66zm107.8 6.367a1.062 1.062 0 01.039.206c0 .027-.054.053-.083.08.028-.103.056-.206.089-.31zm-263.2-41.46c-.21.217-.432.305-.682.058l.48-.275q.102.107.202.217z"></path></g></svg>',
            'stay-at-home' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"><defs><clipPath id="a"><circle cx="150" cy="150" r="150" fill="none"/></clipPath></defs><circle cx="150" cy="150" r="150" fill="currentColor"/><g clip-path="url(#a)" stroke-width=".875" fill="var(--lt-secondary-logo-color, white)"><path d="M169.03 188.06c5-4.5 9.94-9.074 15.558-12.828 5.692-3.8 11.638-7.05 18.56-7.918 5.92-.744 11.726.175 17.448 1.621 12.679 3.203 24.064 9.276 35.159 15.97 9.135 5.51 17.973 11.436 25.586 18.996a18.684 18.684 0 013.73 4.766c1.228 2.449.178 4.45-2.54 4.856-2.923.438-5.71-.323-8.471-1.12-8.11-2.337-15.646-6.173-23.591-8.977-8.856-3.13-17.754-6.054-26.82-8.555-12.251-3.38-23.626-2.218-34.205 5.036a20.864 20.864 0 00-4.393 2.785l-6.82 4.513a25.118 25.118 0 00-5.192 3.658c-5.478 3.771-10.92 7.602-16.45 11.297a77.397 77.397 0 01-14.378 7.884 14.632 14.632 0 01-5.875 1.313c-2.793-.093-4.122-2-3.233-4.664a15.352 15.352 0 014.248-6.023c5.059-4.905 10.273-9.652 15.086-14.806.175-.188.322-.403.483-.606a18.189 18.189 0 003.876-4.164l8.492-9.267a14.38 14.38 0 003.734-3.76z"/><path d="M152.93 205.21c-9.477-3.602-18.971-7.07-29.288-7.378-7.771-.232-14.779 2.098-21.403 5.986-9.1 5.346-17.308 11.953-25.814 18.148-9.302 6.768-18.56 13.607-28.211 19.88a30.45 30.45 0 01-8.602 4.188c-4.46 1.196-7.084-1.31-6.05-5.838.743-3.256 2.582-5.977 4.528-8.613 4.366-5.916 9.695-10.964 14.779-16.223a316.61 316.61 0 0137.539-33.27c6.359-4.774 13.283-8.627 21.176-10.281 8.664-1.82 17.177-.547 25.56 1.802 10.072 2.825 19.233 7.753 28.412 12.61 1.154.61 40.112 21.184 39.377 29.34-5.402 6.51-31.274-1.185-52.012-10.344zm-46.517-37.32c-5.485-.236-11.866-3.833-15.742-11.13-3.66-6.886-3.7-13.966.544-20.695 3.085-4.893 7.785-7.965 13.091-9.87 20.213-7.238 40.374-14.666 61.393-19.382a34.184 34.184 0 019.914-.98c1.825.13 3.87.22 4.583 2.321.685 2.018-.367 3.71-1.766 5.093a23.346 23.346 0 01-7.333 4.452c-9.87 4.32-20.03 7.948-29.75 12.618-4.003 1.925-7.944 3.976-11.954 5.896-1.063.51-1.214.94-.723 2.056 7.485 17.037-8.015 30.058-22.261 29.611zm.49-89.954c18.927-.059 28.447 22.803 15.086 36.2-13.362 13.397-36.253 3.925-36.235-14.998-.053-11.708 9.442-21.228 21.15-21.202zm58.015 97.785a19.466 19.466 0 01-7.705-1.154c-2.307-.914-4.039-2.379-4.322-5.075-.319-3.025.361-5.595 3.161-7.242.667-.411 1.36-.779 2.074-1.101a11.832 11.832 0 006.214-5.976c2.227-4.633 4.83-8.9 9.249-11.918a12.409 12.409 0 004.979-7.759 2.426 2.426 0 012.435-2.147 2.317 2.317 0 012.375 1.948c.787 3.258 2.807 5.702 5.206 7.887a27.258 27.258 0 015.427 6.152c3.332 5.647 2.683 11.437-1.896 16.153-5.325 5.48-12.119 8.013-19.408 9.46a35.658 35.658 0 01-7.788.774zm-118.43 1.838c-4.165-.023-5.524-2.363-3.432-6.028a24.632 24.632 0 015.495-6.344c9.355-8.059 18.507-16.372 28.544-23.6a28.26 28.26 0 016.224-3.68 2.244 2.244 0 012.56.194 2.344 2.344 0 01.624 2.699 17.717 17.717 0 00.178 12.46 2.393 2.393 0 01-.966 3.092c-11.174 7.254-22.226 14.727-34.494 20.091a12.613 12.613 0 01-4.737 1.117zm208.64-26.601c-.057 1.081-.062 2.784-.25 4.466-.977 8.776-7.065 13.388-14.14 17.142a5.733 5.733 0 01-4.388.394 3.955 3.955 0 01-2.795-4.887 9.407 9.407 0 011.345-3.025 18.886 18.886 0 012.299-3.16c4.423-4.3 6.597-9.564 7.173-15.637a13.24 13.24 0 011.917-5.705 4.44 4.44 0 014.228-2.318c1.829.094 2.751 1.336 3.372 2.86 1.213 2.972 1.143 6.123 1.234 9.87z"/><path d="M87.27 237.67c.928.095 2.039-.124 2.353 1.012.307 1.11-.673 1.71-1.483 2.024-5.354 2.074-10.58 4.493-16.074 6.193-.805.248-1.791.535-2.309-.378-.553-.978.097-1.791.885-2.328a42.702 42.702 0 0116.626-6.523zm83.951-14.876c.392 0 .784-.006 1.176 0 .685.014 1.363.158 1.615.862.306.856-.37 1.32-.947 1.739a4.445 4.445 0 01-1.223.604q-7.438 2.42-14.893 4.803a7.053 7.053 0 01-1.533.286c-.653.057-1.378.098-1.696-.642a1.59 1.59 0 01.637-1.896 3.886 3.886 0 01.607-.494 42.973 42.973 0 0116.258-5.262zm61.165-19.513c.977.236 2.242-.119 2.529 1.138.285 1.24-.831 1.723-1.733 2.02-5.327 1.752-10.57 3.775-16.013 5.149-.911.23-2.082.591-2.55-.624-.444-1.155.544-1.84 1.375-2.316a44.741 44.741 0 0116.398-5.367zm-129.24 22.208a13.843 13.843 0 011.983.161c1.232.312 1.514 1.303.58 2.164a4.706 4.706 0 01-1.676.967 1046.39 1046.39 0 01-14.709 4.767c-1.144.36-2.732.993-3.369-.329-.737-1.535.883-2.28 1.999-2.861a45.974 45.974 0 0115.19-4.868zm81.904-22.821c5.56 3.401 11.367 6.46 16.013 11.218a192.86 192.86 0 01-22.83-6.703zm94.329 5.571a.93.93 0 01.034.18c0 .024-.047.047-.073.07.025-.09.05-.18.078-.27zM49.069 171.96c-.184.19-.378.267-.597.051l.42-.24q.09.093.177.19zM57 61l93-39 93 39v20l-93-33-93 33z"/><path d="M180 21h20v25h-20z"/></g></svg>',
        );

        /**
         * Contains list of svg icons
         *
         * @var array
         */
        public static $icons = array(
            'menu'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 590 512"><path id="top" d="m 34.400547,102.7944 c 0,0 324.088783,0 353.551393,0 294.62616,0 147.31308,471.40185 -29.46261,294.62616 C 240.63886,279.57009 34.400547,73.33178 34.400547,73.33178" stroke="currentColor" style="fill:none;stroke-width:50;stroke-linecap:round" /><path id="middle" d="M 34.400547,250.10748 H 387.95194" stroke="currentColor" style="fill:none;stroke-width:50;stroke-linecap:round" /><path id="bottom" d="m 34.400547,409.2056 c 0,0 324.088783,0 353.551393,0 294.62616,0 147.31308,-471.401851 -29.46261,-294.62616 C 240.63886,232.42991 34.400547,438.66822 34.400547,438.66822" stroke="currentColor" style="fill:none;stroke-width:50;stroke-linecap:round" /></svg>',
            'times'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>',
            'moon'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M283.211 512c78.962 0 151.079-35.925 198.857-94.792 7.068-8.708-.639-21.43-11.562-19.35-124.203 23.654-238.262-71.576-238.262-196.954 0-72.222 38.662-138.635 101.498-174.394 9.686-5.512 7.25-20.197-3.756-22.23A258.156 258.156 0 0 0 283.211 0c-141.309 0-256 114.511-256 256 0 141.309 114.511 256 256 256z"></path></svg>',
            'sun'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 160c-52.9 0-96 43.1-96 96s43.1 96 96 96 96-43.1 96-96-43.1-96-96-96zm246.4 80.5l-94.7-47.3 33.5-100.4c4.5-13.6-8.4-26.5-21.9-21.9l-100.4 33.5-47.4-94.8c-6.4-12.8-24.6-12.8-31 0l-47.3 94.7L92.7 70.8c-13.6-4.5-26.5 8.4-21.9 21.9l33.5 100.4-94.7 47.4c-12.8 6.4-12.8 24.6 0 31l94.7 47.3-33.5 100.5c-4.5 13.6 8.4 26.5 21.9 21.9l100.4-33.5 47.3 94.7c6.4 12.8 24.6 12.8 31 0l47.3-94.7 100.4 33.5c13.6 4.5 26.5-8.4 21.9-21.9l-33.5-100.4 94.7-47.3c13-6.5 13-24.7.2-31.1zm-155.9 106c-49.9 49.9-131.1 49.9-181 0-49.9-49.9-49.9-131.1 0-181 49.9-49.9 131.1-49.9 181 0 49.9 49.9 49.9 131.1 0 181z"></path></svg>',
            'jump'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><polyline points="212.545 227.66 299.455 227.66 299.455 273.004 212.545 273.004" fill="currentColor"/><rect x="144.528" y="51.95" width="45.344" height="408.099" fill="currentColor"/><polygon points="121.856 142.639 31.167 460.05 121.856 460.049 121.856 142.639" fill="currentColor"/><rect x="322.127" y="51.95" width="45.344" height="408.099" fill="currentColor"/><polygon points="390.144 142.639 480.833 460.05 390.144 460.049 390.144 142.639" fill="currentColor"/></svg>',
            'apport'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><polygon points="321.793 227.261 439.599 183.55 409.848 161.504 292.859 205.89 321.793 227.261" fill="currentColor"/><polygon points="265.8 291.284 306.503 236.646 277.377 215.134 236.673 269.771 265.8 291.284" fill="currentColor"/><polygon points="261.931 438.601 261.931 308.817 232.08 286.768 232.08 416.553 261.931 438.601" fill="currentColor"/><polygon points="311.359 463.506 433.129 415.802 473.3 359.311 480.833 218.711 446.754 193.458 318.891 240.9 273.698 301.564 273.698 442.165 311.359 463.506" fill="currentColor"/><path d="M226.377,279.639l-85.907-53.52s-7.859-23.445,4.713-37.24a45.735,45.735,0,0,1,29.689-14.327l80.508,50.505,6.551-122.3L230.4,79.4,112.111,123.287,70.3,179.41V309.487l34.842,19.744Z" fill="currentColor"/><polygon points="113.844 110.507 225.008 69.26 196.893 48.494 85.728 89.741 113.844 110.507" fill="currentColor"/><polygon points="62.832 166.525 97.345 120.197 72.649 101.957 38.136 148.284 62.832 166.525" fill="currentColor"/><polygon points="58.546 305.78 58.546 186.747 31.167 166.525 31.167 285.558 58.546 305.78" fill="currentColor"/></svg>',
            'frisbee'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256,48.494C133.869,48.494,34.862,141.4,34.862,256S133.869,463.506,256,463.506,477.138,370.6,477.138,256,378.131,48.494,256,48.494Zm0,400.636C142.33,449.13,50.183,362.663,50.183,256S142.33,62.871,256,62.871,461.817,149.338,461.817,256,369.67,449.13,256,449.13Z" fill="currentColor"/><path d="M257.689,66.26C154.432,66.26,70.725,140.462,70.725,232s83.707,165.737,186.964,165.737S444.653,323.53,444.653,232,360.947,66.26,257.689,66.26Zm0,174.866c-33.936,0-61.446-24.387-61.446-54.47s27.51-54.47,61.446-54.47,61.447,24.387,61.447,54.47S291.625,241.126,257.689,241.126Z" fill="currentColor"/><ellipse cx="257.689" cy="186.656" rx="36.518" ry="32.371" fill="currentColor"/></svg>',
            'tennis-ball' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M430.042,336.185c-14.738-3.557-60.6-4.319-88.175,18.55s-44.342,54.887-47.772,66.7-6.48,20.964-7.623,24.4a147.691,147.691,0,0,1-7.962,17.673A208.27,208.27,0,0,0,446.216,341.142C440.582,339.178,434.808,337.336,430.042,336.185Z" fill="currentColor"/><path d="M84.705,138.408c19.572-.172,56.926,2.115,102.665,30.321s76.994,38.116,106.725,32.78,69.836-25.762,71.658-73.183a379.266,379.266,0,0,0-1.59-49.547A208.183,208.183,0,0,0,84.705,138.408Z" fill="currentColor"/><path d="M265.889,418.008c7.115-25.919,24.394-64.543,54.887-89.446s68.609-31.509,98.085-26.427c13.96,2.407,27.116,7.549,37.105,12.3a208.357,208.357,0,0,0-62.227-213.818c2.684,26.257.636,56.473-14.624,82.155-15.664,26.362-51.478,47.709-101.029,51.521s-72.42-15.247-98.339-29.731-68.263-40.48-115.317-29.389a207.428,207.428,0,0,0-16.544,81.439c0,107.314,81.23,195.646,185.556,206.892C247.633,449.846,261.437,434.226,265.889,418.008Z" fill="currentColor"/></svg>',
            );

    }

}
