<form role="search" method="get" id="searchform-mobile" class="collapsible" action="<?php echo esc_attr( home_url( '/' ) ) ?>">

	<label class="screen-reader-text" for="sm"><?php _e( 'Search', 'lithe' ); ?>:</label>

	<div class="input-group" class="awaits-icons fades-in">

        <input type="checkbox" id="search-toggle" class="collapsible-toggle" autocomplete="off" tabindex="-1" aria-hidden="true">

		<label for="search-toggle">
			<span class="collapsible-open"><i title="<?php esc_attr_e( 'Show search', 'lithe' ); ?>" class="fas fa-search fa-fw"></i></span>
			<span class="collapsible-close"><i title="<?php esc_attr_e( 'Hide search', 'lithe' ); ?>" class="fas fa-times fa-fw"></i></span>
		</label>

        <label class="screen-reader-text" for="searchform-input"><?php _e( 'Search', 'lithe' ) ?></label>
		<input type="text" value="<?php echo get_search_query() ?>" name="s" id="searchform-input" placeholder="<?php esc_attr_e( 'Search', 'lithe' ); ?>..." />

	</div>

</form>

<script type="text/javascript">

    (function (d) {
        d.querySelector('#search-toggle').addEventListener('change', event => {
            if (event.target.checked) {
                var i = d.querySelector('#searchform-input');
                i.value = '';
                i.focus();
            }
        });
    })(document);

</script>
