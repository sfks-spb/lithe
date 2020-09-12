<?php $post_image_float = get_post_meta( $post->ID, 'post_image_float', true ); ?>
<div class="form-field">
    <div><label for="post_image_float"><?php _e( 'Post Image Float', 'lithe' ); ?></label></div>
    <select name="post_image_float">
        <option value="center" <?php selected( $post_image_float, 'center' ); ?>><?php _e( 'Center', 'lithe' ); ?></option>
        <option value="left" <?php selected( $post_image_float, 'left' ); ?>><?php _e( 'Left', 'lithe' ); ?></option>
        <option value="right" <?php selected( $post_image_float, 'right' ); ?>><?php _e( 'Right', 'lithe' ); ?></option>
    </select>
    <p class="description"><?php _e( 'Post image position relative to the content or excerpt.', 'lithe' ); ?></p>
</div>
