<div class="form-field">

    <div>

        <label for="post_image_float"><?php _e( 'Post Image Float', 'lithe' ); ?></label>

    </div>

    <select name="post_image_float">

        <option value="center" <?php selected( $post_image_float, 'center' ); ?>><?php _e( 'Center', 'lithe' ); ?></option>
        <option value="left" <?php selected( $post_image_float, 'left' ); ?>><?php _e( 'Left', 'lithe' ); ?></option>
        <option value="right" <?php selected( $post_image_float, 'right' ); ?>><?php _e( 'Right', 'lithe' ); ?></option>

    </select>

    <p class="description"><?php _e( 'Post image position relative to the content or excerpt.', 'lithe' ); ?></p>

</div>

<div class="form-field">

    <div>

        <label for="post_image_parallax"><?php _e( 'Post Image Parallax', 'lithe' ); ?></label>

    </div>

    <select name="post_image_parallax">

        <option value="0" <?php selected( $post_image_parallax, 0 ); ?>><?php _e( 'Disabled', 'lithe' ); ?></option>
        <option value="1" <?php selected( $post_image_parallax, 1 ); ?>><?php _e( 'Enabled', 'lithe' ); ?></option>

    </select>

    <p class="description"><?php _e( 'Post image should have parallax effect.', 'lithe' ); ?></p>

</div>

<div class="form-field">

    <div>

        <label for="post_image_parallax_layers"><?php _e( 'Post Image Parallax Layers', 'lithe' ); ?></label>

    </div>

    <input type="text" name="post_image_parallax_layers" value="<?php echo $post_image_parallax_layers; ?>" />

    <p class="description"><?php _e( 'Post image parallax layers separated by comma.', 'lithe' ); ?></p>

</div>
