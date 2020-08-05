<tr class="form-field term-group-wrap">

    <th scope="row">
        <label for="link"><?php _e( 'Website', 'lithe' ); ?></label>
    </th>

    <td>
        <input type="url" id="link" name="link" value="<?php echo get_term_meta( $term->term_id, 'link', true ); ?>" />
        <p class="description"><?php _e( "Venue's website or social network link.", 'lithe' ); ?></p>
    </td>

</tr>

<tr class="form-field term-group-wrap">

    <th scope="row">
        <label for="address"><?php _e( 'Address', 'lithe' ); ?></label>
    </th>

    <td>
        <input type="text" id="address" name="address" value="<?php echo get_term_meta( $term->term_id, 'address', true ); ?>" />
        <p class="description"><?php _e( "Venue's physical address.", 'lithe' ); ?></p>
    </td>

</tr>

<tr class="form-field term-group-wrap">

    <th scope="row">
        <label for="coords"><?php _e( 'Coordinates', 'lithe' ); ?></label>
    </th>

    <td>
        <input type="text" id="coords" name="coords" value="<?php echo get_term_meta( $term->term_id, 'coords', true ); ?>" />
        <p class="description"><?php _e( "Venue's coordinates.", 'lithe' ); ?></p>
    </td>

</tr>

<tr>
    <td colspan="2"><h2><?php _e( 'Weather Widget', 'lithe'); ?></h2></td>
</tr>

<tr class="form-field term-group-wrap">

    <th scope="row">
        <label for="location_id"><?php _e( 'Location ID', 'lithe' ); ?></label>
    </th>

    <td>
        <input type="text" id="location_id" name="location_id" value="<?php echo get_term_meta( $term->term_id, 'location_id', true ); ?>" />
        <p class="description"><?php _e( "Venue's location ID for the weather widget.", 'lithe' ); ?></p>
    </td>

</tr>

<tr class="form-field term-group-wrap">

    <th scope="row">
        <label for="position"><?php _e( 'Position', 'lithe' ); ?></label>
    </th>

    <td>
        <input type="number" id="position" name="position" value="<?php echo get_term_meta( $term->term_id, 'position', true ); ?>" />
        <p class="description"><?php _e( "Venue's position in the weather widget. Lower numbers go first.", 'lithe' ); ?></p>
    </td>

</tr>