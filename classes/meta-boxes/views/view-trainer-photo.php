<?php

    $photo_id = get_post_meta($post->ID, 'photo_id', true);
    $photo = wp_get_attachment_image_src( $photo_id, 'medium' );

?>

<div class="trainer-photo-container">

    <?php if ( is_array( $photo ) ): ?>

        <img src="<?php echo $photo[0]; ?>" alt="" style="width: 100%; max-width: 400px;">

    <?php endif; ?>

</div>

<input type="hidden" name="photo_id" value="<?php echo esc_attr( $photo_id ); ?>">
<input type="button" class="upload button" value="<?php esc_attr_e( 'Upload photo', 'lithe' ); ?>">
<input type="button" class="remove button" value="<?php esc_attr_e( 'Delete photo', 'lithe' ); ?>" style="<?php echo ( is_array( $photo ) ) ? 'display: none;' : ''; ?>">