<?php
/**
 * The template for displaying search forms in NextStepMaine
 */
?>
	<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'site search', 'nextstepmaine' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'go', 'nextstepmaine' ); ?>" />
	</form>
