<div class="form-field">
    <div><label for="first_name"><?php _e( 'First Name', 'lithe' ); ?></label></div>
    <input type="text" name="first_name" value="<?php echo get_post_meta($post->ID, 'first_name', true); ?>" />
    <p class="description"><?php _e( "Trainer's first name.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="middle_name"><?php _e( 'Middle Name', 'lithe' ); ?></label></div>
    <input type="text" name="middle_name" value="<?php echo get_post_meta($post->ID, 'middle_name', true); ?>" />
    <p class="description"><?php _e( "Trainer's middle name.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="last_name"><?php _e( 'Last Name', 'lithe' ); ?></label></div>
    <input type="text" name="last_name" value="<?php echo get_post_meta($post->ID, 'last_name', true); ?>" />
    <p class="description"><?php _e( "Trainer's last name.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="organization"><?php _e( 'Organization', 'lithe' ); ?></label></div>
    <input type="text" name="organization" value="<?php echo get_post_meta($post->ID, 'organization', true); ?>" />
    <p class="description"><?php _e( 'Organization that trainer is a part of.', 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="phone"><?php _e( 'Phone', 'lithe' ); ?></label></div>
    <input type="tel" id="phone" name="phone" value="<?php echo get_post_meta($post->ID, 'phone', true); ?>" />
    <p class="description"><?php _e( "Trainer's contact phone.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="email"><?php _e( 'E-Mail', 'lithe' ); ?></label></div>
    <input type="email" name="email" value="<?php echo get_post_meta($post->ID, 'email', true); ?>" />
    <p class="description"><?php _e( "Trainer's contact e-mail.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="social"><?php _e( 'Social Network', 'lithe' ); ?></label></div>
    <input type="url" name="social" value="<?php echo get_post_meta($post->ID, 'social', true); ?>" />
    <p class="description"><?php _e( "Link to trainer's social network profile.", 'lithe' ); ?></p>
</div>