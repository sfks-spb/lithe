<?php

if ( ! function_exists( 'lithe_date_format' ) ) {

    /**
     * Gets date format.
     *
     * @param  string $style Format style. (full or compact)
     *
     * @return string
     */
    function lithe_date_format( string $style = 'full' ): string {
        if ( 'compact' === $style ) {
            return 'j F Y';
        }

        return 'j F Y ' . _x( '\a\\t', 'At certain time', 'lithe' ) . ' H:i';
    }
}

if ( ! function_exists( 'lithe_timezone' ) ) {

    /**
     * Gets current WordPress timezone.
     *
     * @return DateTimeZone
     */
     function lithe_timezone(): DateTimeZone {
         $timezone = get_option( 'timezone_string' );

         if ( empty( $timezone ) ) {
             $timezone_offset = get_option('gmt_offset', 0);
             $timezone = ($timezone_offset == 0) ? 'UTC' : $timezone_offset;

             if ( ! in_array( substr( $timezone_offset, 0, 1 ), array( '-', '+', 'U' ) ) ) {
                 $timezone = '+' . $timezone_offset;
             }
         }

         return new DateTimeZone( $timezone );
     }

}

if ( ! function_exists( 'lithe_strtotime' ) ) {

    /**
     * Converts string to time with timezone support.
     *
     * @param  string $string Datetime string to convert.
     *
     * @return DateTime|bool
     */
    function lithe_strtotime( string $string ) {
        if ( is_null ( $string ) ) return false;

        return new DateTime( $string, lithe_timezone() );
    }
}

if ( ! function_exists( 'lithe_now' ) ) {

    /**
     * Gets current timestamp with timezone support.
     *
     * @return DateTime
     */
     function lithe_now(): DateTime {
         return new DateTime( 'now', lithe_timezone() );
     }

}

if ( ! function_exists( 'lithe_get_classes_directory_path' ) ) {

    /**
     * Constructs path in classes directory.
     *
     * @param  string|null $path Relative path to file inside classes directory.
     *
     * @return void
     */
    function lithe_get_classes_directory_path( ?string $path ): string {
        $classes_directory = trailingslashit( get_template_directory() ) . 'classes';

        if ( is_null( $path ) ) {
            return $classes_directory;
        }

        return trailingslashit( $classes_directory ) . $path;
    }
}

if ( ! function_exists( 'lithe_render' ) ) {

    /**
     * Renders view.
     *
     * @param  string $view View path.
     * @param  array  $args Arguments to pass to the view.
     *
     * @return void
     */
    function lithe_render( string $view, array $args = array() ): void {

        $view_path = lithe_get_classes_directory_path(  $view . '.php' );

        if ( is_file( $view_path ) ) {

            foreach ( $args as $var => $value ) {
                ${$var} = $value;
            }

            include $view_path;
        }

    }
}

if ( ! function_exists( 'lithe_register_menus' ) ) {

    /**
     * Registers menus.
     *
     * @param  array $menus List of menues to register.
     *
     * @return void
     */
    function lithe_register_menus( array $menus ): void {
        lithe()->register_menus( $menus );
    }

}

if ( ! function_exists( 'lithe_photon_url' ) ) {

    /**
     * Gets Photon image url.
     *
     * @param  string $image_url Publicly accesable image URL.
     * @param  array  $args      URL arguments.
     * @param  string $scheme    URL scheme. (https or http)
     *
     * @return string
     */
    function lithe_photon_url( string $image_url, array $args = array(), ?string $scheme = 'https' ): string {

        return apply_filters( 'jetpack_photon_url', $image_url, $args, $scheme );

    }

}

if ( ! function_exists( 'lithe_the_post_meta' ) ) {

    /**
     * Outputs post meta.
     *
     * @return void
     */
    function lithe_the_post_meta(): void {
        ?>
        <span class="entry-date"><i class="far fa-clock fa-fw"></i> <time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>"><?php the_time( lithe_date_format( 'compact' ) ); ?></time></span>
        <span class="entry-author"><i class="far fa-user fa-fw"></i> <?php the_author(); ?></span>
        <?php
    }

}

if ( ! function_exists( 'lithe_the_copyright' ) ) {

    /**
     * Outputs copyright message.
     *
     * @param  string $holder Copyright holder.
     * @param  int    $starting_year Copyright starting year.
     *
     * @return void
     */
    function lithe_the_copyright( string $holder, int $starting_year = 2007 ): void {
        echo "$holder, $starting_year - " . date( 'Y' );
    }

}

if ( ! function_exists( 'lithe_breadcrumbs' ) ) {

    /**
     * Outputs breadcrumbs.
     *
     * @return void
     */
    function lithe_breadcrumbs(): void {
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<nav class="navigation breadcrumbs has-light-gray-border-color" aria-label="' . __( 'Breadcrumbs', 'lithe' ) . '">', '</nav>' );
        }
    }

}

if ( ! function_exists( 'lithe_get_breakpoints' ) ) {

    /**
     * Gets breakpoints list.
     *
     * @return array
     */
    function lithe_get_breakpoints(): array {
        return [
            'chihuahua' => 250,
            'corgi'     => 320,
            'aussie'    => 600,
            'labrador'  => 900,
            'podenco'   => 1200,
        ];
    }

}

if ( ! function_exists( 'lithe_get_breakpoints_json' ) ) {

    /**
     * Gets breakpoints list serialized as JSON.
     *
     * @return string
     */
    function lithe_get_breakpoints_json(): string {
        return wp_json_encode( lithe_get_breakpoints() );
    }

}

if ( ! function_exists( 'lithe_the_tags' ) ) {

    /**
     * Gets post tag list.
     *
     * @global WP_Post $post Current post instance.
     *
     * @return void|WP_Error
     */
    function lithe_the_tags() {
        global $post;

        $terms = get_the_terms( $post->ID, 'post_tag' );

        if ( is_wp_error( $terms ) ) {
            return $terms;
        }

        if ( empty( $terms ) ) {
            return false;
        }

        $links = array();

        foreach ( $terms as $term ) {
            $link = get_term_link( $term, 'post_tag' );

            if ( is_wp_error( $link ) ) {
                return $link;
            }

            $links[] = '<a class="tag" href="' . esc_url( $link ) . '" rel="tag"><i class="fas fa-hashtag fa-sm"></i> ' . $term->name . ' <span class="tag-count">' . $term->count . '</span></a>';
        }

        echo join( '', $links );
    }

}

if ( ! function_exists( 'lithe_site_logo' ) ) {

    /**
     * Outputs site logo.
     *
     * @param  string      $logo Logo name.
     * @param  string|null $title Logo title attribute.
     *
     * @return void
     */
    function lithe_site_logo( string $logo = 'logo-plain', ?string $title = null ): void {
        $tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
        $title = is_null( $title ) ? get_bloginfo( 'description' ) : $title;

        echo '<' . $tag . ' id="site-title">';

        ?>
            <a title="<?php echo esc_attr( $title ); ?>" href="<?php echo esc_attr( home_url( '/' ) ); ?>" rel="home">

                <?php lithe_site_logo_compact( $logo, $title ); ?><span class="font-family-serif"><?php bloginfo( 'name' ); ?></span>

            </a>
        <?php

        echo "</$tag>";
    }

}

if ( ! function_exists( 'lithe_site_logo_compact' ) ) {

    /**
     * Outputs compact logo. (without the text)
     *
     * @param  string      $logo Logo name.
     * @param  string|null $title Logo title attribute.
     *
     * @return void
     */
    function lithe_site_logo_compact( string $logo = 'logo-plain', ?string $title = null ): void {
        $title = is_null( $title ) ? get_bloginfo( 'description' ) : $title;

        lithe_svg( $logo, array( 'title' => $title ) );
    }

}

if ( ! function_exists( 'lithe_related_posts' ) ) {

    /**
     * Outputs related posts list.
     *
     * @global WP_Post $post Current post instance.
     *
     * @return void
     */
    function lithe_related_posts(): void {
        global $post;

        $tags = wp_get_post_tags( $post->ID );

        if ( $tags ) {
            $tag_ids = array();

            foreach( $tags as $tag ) $tag_ids[] = $tag->term_id;

            $related = new WP_Query( array(
                'tag__in'          => $tag_ids,
                'post__not_in'     => array( $post->ID ),
                'posts_per_page'   => 3,
                'caller_get_posts' => 1,
            ) );

            set_query_var( 'related', $related );
            get_template_part( 'template-parts/related-posts' );
            set_query_var( 'related', false );

            wp_reset_postdata();
        }
    }
}

if ( ! function_exists( 'lithe_get_views' ) ) {

    /**
     * Gets post views.
     *
     * @global WP_Post $post Current post instance.
     *
     * @return int
     */
    function lithe_get_views(): int {
        global $post;

        $views = get_post_meta( $post->ID, 'post_view_count', true );

        return (int) $views;
    }
}

if ( ! function_exists( 'lithe_the_views' ) ) {

    /**
     * Outputs post views.
     *
     * @return void
     */
    function lithe_the_views(): void {
        $views = lithe_get_views();

        $views = ( $views >= 1000 ) ? round( $views / 1000, 1 ) . 'K' : $views;

        echo '<span class="entry-views"><i title="' . esc_attr( sprintf( __( '%s Views', 'lithe' ), $views ) ) . '" class="far fa-eye fa-fw"></i><span class="entry-views-count">' . $views . '</span></span>';
    }
}

if ( ! function_exists( 'lithe_comments_link' ) ) {

    /**
     * Outputs comments counter and link.
     *
     * @return void
     */
    function lithe_comments_link(): void {
        $before = '<i title="' . __( 'Leave a comment', 'lithe' ) . '" class="far fa-comment fa-fw"></i>';
        $after = ' ' . __( 'on', 'lithe' ) . ' ' . get_the_title();

        comments_popup_link(
            $before . '0<span class="screen-reader-text">' . __( '0 Comments', 'lithe' ) . $after . '</span>',
            $before . '1<span class="screen-reader-text">' . __( '1 Comment', 'lithe' ) . $after . '</span>',
            $before . '%<span class="screen-reader-text">' . __( '% Comments', 'lithe' ) . $after . '</span>',
            'comments-link',
            '<i title="' . __( 'Comments are off for this post', 'lithe' ) . '" class="fas fa-comment-slash"></i>0'
        );
    }
}

if ( ! function_exists( 'lithe_post_thumbnail' ) ) {

    /**
     * Outputs post thumbnail.
     *
     * @param  string $before Output before thumbnail.
     * @param  string $after  Output after thumbnail.
     *
     * @global WP_Post $post Current post instance.
     * @global array   $_wp_additional_image_sizes Array of additional image sizes.
     *
     * @return void
     */
    function lithe_post_thumbnail(string $before = '', string $after = ''): void {

        global $post;

        if ( has_post_thumbnail( $post->ID ) ) {

            echo $before;

            if ( lithe_has_parallax_thumbnail() && is_singular() ) {

                lithe_the_parallax_thumbnail();

            } else {

                $thumbnail_id = get_post_thumbnail_id( $post->ID );

                the_post_thumbnail( 'lithe_medium', array(
                    'alt'   => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
                    'class' => $class,
                ) );

                if ( is_singular() ) lithe_post_thumbnail_attribution( $thumbnail_id );

            }

            echo $after;

        }

    }

}

if ( ! function_exists( 'lithe_post_thumbnail_attribution' ) ) {

    /**
     * Outputs post thumbnail attribution link.
     *
     * @param  int $thumbnail_id - numerical ID of post thumbnail
     *
     * @return void
     */
    function lithe_post_thumbnail_attribution( int $thumbnail_id ): void {

        $attribution = get_the_excerpt( $thumbnail_id );

        if ( ! empty( $attribution ) ) {

            $template = '<span class="attribution">%s</span>';

            $attribution_parts = explode( ',', $attribution );
            if ( count( $attribution_parts ) > 1 ) {
                $template = '<a class="attribution" href="' . esc_attr( $attribution_parts[1] ) . '" rel="noreferrer nofollow">%s</a>';
            }

            $content = __( 'Image By', 'lithe' ) . ': ' . esc_html( $attribution_parts[0] );
            printf( $template, $content );

        }

    }

}

if ( ! function_exists( 'lithe_the_parallax_thumbnail' ) ) {

    /**
     * Outputs parallax thumbnail.
     *
     * @global WP_Post $post Current post instance.
     * @global array   $_wp_additional_image_sizes Array of additional image sizes.
     *
     * @return void
     */
    function lithe_the_parallax_thumbnail(): void {

        global $post;
        global $_wp_additional_image_sizes;

        $thumbnail_id = get_post_thumbnail_id( $post->ID );
        $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'lithe_medium' );
        $thumbnail_width = $_wp_additional_image_sizes['lithe_medium']['width'];
        $thumbnail_height = $_wp_additional_image_sizes['lithe_medium']['height'];
        $thumbnail_ext = pathinfo( $thumbnail_url, PATHINFO_EXTENSION );

        $class = 'rellax-fallback';
        $layers = get_post_meta( $post->ID, 'post_image_parallax_layers', true );

        echo '<div class="rellax-image">';

        if ( ! empty( $layers ) ) {

            $class = 'rellax-overlay';
            $layers = explode( ',', $layers );

            foreach ( $layers as $layer => $speed ) {

                set_query_var( 'width', $thumbnail_width );
                set_query_var( 'height', $thumbnail_height );
                set_query_var( 'layer', ++$layer );
                set_query_var( 'speed', trim( $speed ) );
                set_query_var( 'url', str_replace( ".${thumbnail_ext}", "-${layer}.${thumbnail_ext}", $thumbnail_url ) );

                get_template_part( 'template-parts/parallax-layer' );

            }

        }

        the_post_thumbnail( 'lithe_medium', array(
            'alt'   => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
            'class' => $class,
        ) );

        echo '</div>';

    }

}

if ( ! function_exists( 'lithe_has_parallax_thumbnail' ) ) {
    /**
     * Checks whether post has parallax thumbnail.
     *
     * @global WP_Post $post Current post instance.
     *
     * @return bool
     */
    function lithe_has_parallax_thumbnail(): bool {

        global $post;

        $parallax = get_post_meta( $post->ID, 'post_image_parallax', true );

        if ( $parallax ) {
            return true;
        }

        return false;

    }
}

if ( ! function_exists( 'lithe_sports' ) ) {

    /**
     * Outputs sports list.
     *
     * @return void
     */
    function lithe_sports(): void {

        $sports = get_terms( array(
            'taxonomy'   => 'sport',
            'order'      => 'ASC',
            'hide_empty' => true,
        ) );

        if ( ! empty( $sports ) ):
        ?>
            <select class="sports-list" name="sports">

            <option value="0"><?php esc_html_e( 'All', 'lithe' ); ?></option>

            <?php foreach( $sports as $sport ): ?>

                <option value="<?php echo esc_attr( $sport->term_id ); ?>"><?php echo esc_html( $sport->name ) ?></option>

            <?php endforeach; ?>

            </select>
        <?php
        endif;

    }

}
