<?php

if ( ! function_exists( 'lithe_date_format' ) ) {
    /**
     *
     */
    function lithe_date_format(): string {
        return 'j F Y ' . _x( '\a\\t', 'At certain time', 'lithe' ) . ' H:i';
    }
}

if ( ! function_exists( 'lithe_strtotime' ) ) {
    /**
     *
     */
    function lithe_strtotime( $string ) {
        if ( is_null ( $string ) ) return false;

        $timezone = get_option( 'timezone_string' );

        if ( empty( $timezone ) ) {
            $timezone_offset = get_option('gmt_offset', 0);
            $timezone = ($timezone_offset == 0) ? 'UTC' : $timezone_offset;

            if ( ! in_array( substr( $timezone_offset, 0, 1 ), array( '-', '+', 'U' ) ) ) {
                $timezone = '+' . $timezone_offset;
            }
        }

        return ( new DateTime( $string, new DateTimeZone( $timezone ) ) )->format('U');
    }
}

if ( ! function_exists( 'lithe_render' ) ) {
    /**
     *
     */
    function lithe_render( string $view, array $args = array() ): void {

        $view_path = lithe()->get_classes_directory_path(  $view . '.php' );

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
     *
     */
    function lithe_register_menus( array $menus ): void {
        lithe()->register_menus( $menus );
    }

}

if ( ! function_exists( 'lithe_photon_uri' ) ) {

    /**
     *
     */
    function lithe_photon_uri( string $protocol = 'https' ): string {
        static $current = 0;
        $max = 3;

        if ( $current > $max ) {
            $current = 0;
        }

        return $protocol . '://i' . $current++ . '.wp.com';
    }

}

if ( ! function_exists( 'lithe_photon_prepend_uri' ) ) {

    /**
     *
     */
    function lithe_photon_prepend_uri( string $uri, array $params = array(), string $protocol = 'https' ): string {
        $uri = preg_replace( '/https?:\/\//i', lithe_photon_uri( $protocol ) . '/', $uri );

        if ( 'https' === $protocol ) {
            $params[ 'ssl' ] = 1;
        }

        if ( false !== strpos( $uri, '?' ) ) {
            $uri = str_replace( '?', '?' . http_build_query( $params ) . '&', $uri );
        }

        return $uri;
    }

}

if ( ! function_exists( 'lithe_the_post_meta' ) ) {

    /**
     *
     */
    function lithe_the_post_meta(): void {
        ?>
        <span class="entry-date"><i class="far fa-clock fa-fw"></i> <time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>"><?php the_time( lithe_date_format() ); ?></time></span>
        <span class="entry-author"><i class="far fa-user fa-fw"></i> <?php the_author(); ?></span>
        <?php
    }

}

if ( ! function_exists( 'lithe_the_copyright' ) ) {

    /**
     *
     */
    function lithe_the_copyright( string $holder, int $starting_year = 2007 ): void {
        echo "$holder, $starting_year - " . date( 'Y' );
    }

}

if ( ! function_exists( 'lithe_breadcrumbs' ) ) {

    /**
     *
     */
    function lithe_breadcrumbs(): void {
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<nav class="navigation breadcrumbs has-light-gray-border-color" aria-label="' . __( 'Breadcrumbs', 'lithe' ) . '">', '</nav>' );
        }
    }

}

if ( ! function_exists( 'lithe_get_antispam' ) ) {

    /**
     *
     */
    function lithe_get_antispam( string $content, $type = 'text' ): string {
        switch ( $type ) {
            case 'email':
                return '<a href="' . antispambot( 'mailto:' . $content ) . '">' . antispambot( $content ) . '</a>';
            case 'phone':
            case 'text':
            default:
                return antispambot( $content );
        }
    }

}

if ( ! function_exists( 'lithe_antispam' ) ) {

    /**
     *
     */
    function lithe_antispam( string $content, $type = 'text' ): void {
        echo lithe_get_antispam( $content, $type );
    }

}

if ( ! function_exists( 'lithe_get_breakpoints' ) ) {

    /**
     *
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
     *
     */
    function lithe_get_breakpoints_json(): string {
        return wp_json_encode( lithe_get_breakpoints() );
    }

}

if ( ! function_exists( 'lithe_the_tags' ) ) {

    /**
     *
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
     *
     */
    function lithe_site_logo( string $logo = 'logo-plain', ?string $title = null ): void {
        $tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
        $title = is_null( $title ) ? get_bloginfo( 'description' ) : $title;

        echo '<' . $tag . ' id="site-title">';

        ?>
            <a title="<?php echo esc_attr( $title ); ?>" href="<?php echo home_url( '/' ); ?>" rel="home">

                <?php lithe_site_logo_compact( $logo, $title ); ?><span class="font-family-serif"><?php bloginfo( 'name' ); ?></span>

            </a>
        <?php

        echo "</$tag>";
    }

}

if ( ! function_exists( 'lithe_site_logo_compact' ) ) {

    /**
     *
     */
    function lithe_site_logo_compact( string $logo = 'logo-plain', ?string $title = null ): void {
        $title = is_null( $title ) ? get_bloginfo( 'description' ) : $title;

        lithe_svg( $logo, array( 'title' => $title ) );
    }

}

if ( ! function_exists( 'lithe_related_posts' ) ) {
    /**
     *
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
     *
     */
    function lithe_get_views(): int {
        global $post;

        $views = get_post_meta( $post->ID, 'post_view_count', true );

        return (int) $views;
    }
}

if ( ! function_exists( 'lithe_the_views' ) ) {
    /**
     *
     */
    function lithe_the_views(): void {
        $views = lithe_get_views();

        $views = ( $views >= 1000 ) ? ( $views / 1000 ) . 'K' : $views;

        echo '<span class="entry-views"><i title="' . esc_attr( sprintf( __( '%s Views', 'lithe' ), $views ) ) . '" class="far fa-eye fa-fw"></i><span class="entry-views-count">' . $views . '</span></span>';
    }
}

if ( ! function_exists( 'lithe_comments_link' ) ) {
    /**
     *
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

if ( ! function_exists( 'lithe_sports' ) ) {
    /**
     *
     */
    function lithe_sports(): void {

        $sports = get_terms( array(
            'taxonomy'   => 'sport',
            'order'      => 'DESC',
            'hide_empty' => true,
        ) );

        if ( ! empty( $sports ) ):
        ?>
            <ul class="sports-list">

            <?php foreach( $sports as $sport ): ?>

                <li class="sport-item" data-sport-id="<?php echo esc_attr( $sport->term_id ); ?>">
                    <?php echo esc_html( $sport->name ); ?>
                    <span class="sport-trainers-count"><?php echo $sport->count; ?></span>
                </li>

            <?php endforeach; ?>

            </ul>
        <?php
        endif;

    }

}