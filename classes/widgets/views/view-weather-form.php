<p>

    <label for="<?php echo esc_attr( $widget->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'lithe' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $widget->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

</p>

<p>

    <label for="<?php echo esc_attr( $widget->get_field_id( 'apikey' ) ); ?>"><?php esc_attr_e( 'API Key (from developer.accuweather.com):', 'lithe' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $widget->get_field_id( 'apikey' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'apikey' ) ); ?>" type="text" value="<?php echo esc_attr( $apikey ); ?>">

</p>

<p>

    <label for="<?php echo esc_attr( $widget->get_field_id( 'update_interval' ) ); ?>"><?php esc_attr_e( 'Update Interval:', 'lithe' ); ?></label>
    <select class="widefat" id="<?php echo esc_attr( $widget->get_field_id( 'update_interval' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'update_interval' ) ); ?>">
        <option <?php selected( $update_interval, 3); ?> value="3"><?php esc_html_e( '3 hours', 'lithe' ) ?></option>
        <option <?php selected( $update_interval, 6); ?> value="6"><?php esc_html_e( '6 hours', 'lithe' ) ?></option>
        <option <?php selected( $update_interval, 12); ?> value="12"><?php esc_html_e( '12 hours', 'lithe' ) ?></option>
    </select>

</p>