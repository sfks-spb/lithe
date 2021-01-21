<div class="form-field">
    <div><label for="full_name"><?php _e( 'Full Name', 'lithe' ); ?></label></div>
    <input type="text" name="full_name" value="<?php echo get_post_meta($post->ID, 'full_name', true); ?>" />
    <p class="description"><?php _e( "Dog's full name (as written in pedigree).", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="dob"><?php _e( 'Birthday', 'lithe' ); ?></label></div>
    <input type="datetime-local" name="dob" value="<?php echo get_post_meta($post->ID, 'dob', true); ?>" />
    <p class="description"><?php _e( "Dog's birthday date.", 'lithe' ); ?></p>
</div>

<?php
    $gender = get_post_meta($post->ID, 'gender', true);
?>
<div class="form-field">
    <div><label for="gender"><?php _e( 'Gender', 'lithe' ); ?></label></div>
    <select name="gender">
        <option value="m" <?php selected( $gender, 'm' ); ?>><?php _e( 'Male', 'lithe' ); ?></option>
        <option value="f" <?php selected( $gender, 'f' ); ?>><?php _e( 'Female', 'lithe' ); ?></option>
    </select>
    <p class="description"><?php _e( "Dog's gender.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="breed"><?php _e( 'Breed', 'lithe' ); ?></label></div>
    <input type="text" name="breed" value="<?php echo get_post_meta($post->ID, 'breed', true); ?>" />
    <p class="description"><?php _e( "Dog's breed.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="pedigree_no"><?php _e( 'Pedigree No', 'lithe' ); ?></label></div>
    <input type="text" name="pedigree_no" value="<?php echo get_post_meta($post->ID, 'pedigree_no', true); ?>" />
    <p class="description"><?php _e( "Dog's pedigree number.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="tattoo"><?php _e( 'Tattoo No', 'lithe' ); ?></label></div>
    <input type="text" name="tattoo" value="<?php echo get_post_meta($post->ID, 'tattoo', true); ?>" />
    <p class="description"><?php _e( "Dog's tattoo number.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="microchip_no"><?php _e( 'Microchip No', 'lithe' ); ?></label></div>
    <input type="text" name="microchip_no" value="<?php echo get_post_meta($post->ID, 'microchip_no', true); ?>" />
    <p class="description"><?php _e( "Dog's microchip number.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="workbook_no"><?php _e( 'Workbook No', 'lithe' ); ?></label></div>
    <input type="text" name="workbook_no" value="<?php echo get_post_meta($post->ID, 'workbook_no', true); ?>" />
    <p class="description"><?php _e( "Dog's workbook number.", 'lithe' ); ?></p>
</div>

<div class="form-field">
    <div><label for="height"><?php _e( 'Height', 'lithe' ); ?></label></div>
    <input type="number" name="height" value="<?php echo get_post_meta($post->ID, 'height', true); ?>" />
    <p class="description"><?php _e( "Dog's height (in cm).", 'lithe' ); ?></p>
</div>
