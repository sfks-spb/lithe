<p class="description">
    <?php _e( "Trainer's schedule on different venues.", 'lithe' ); ?>
</p>

<?php foreach ( $venues as $venue ): $venue_sports = get_post_meta( $post->ID, 'sports_' . $venue->term_id ); ?>

    <h3><?php echo __( 'Venue', 'lithe' ) . ': ' . esc_html( $venue->name ); ?></h3>

    <table class="form-table">
        <thead>

            <td><?php echo __( 'Sport', 'lithe' ); ?></td>
            <td><?php echo __( 'Timetable', 'lithe' ); ?></td>

        </thead>

        <tbody>

    <?php foreach ( $sports as $sport ): $meta_key = $venue->term_id . '-' . $sport->term_id; ?>

        <tr class="form-field">

            <td>
                <label>
                    <input type="checkbox" name="sports[<?php echo $venue->term_id; ?>][]" value="<?php echo $sport->term_id; ?>" <?php echo ( in_array( $sport->term_id, $venue_sports ) ) ? 'checked' : ''; ?>>
                    <?php echo $sport->name; ?>
                </label>
            </td>

            <td>
                <textarea name="timetable[<?php echo $meta_key; ?>][]"><?php echo get_post_meta($post->ID, 'timetable_' . $meta_key, true); ?></textarea>
            </td>

        </tr>

    <?php endforeach; ?>

        </tbody>

    </table>

<?php endforeach; ?>
