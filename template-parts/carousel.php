<div id="<?php echo $carousel['id']; ?>" class="owl-carousel owl-theme <?php echo esc_attr( $carousel['classes'] ); ?>" aria-roledescription="carousel">

    <?php echo implode( "\n", $carousel['items'] ); ?>

</div>
