<?php

namespace madxartwork\Modules\Finder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<script type="text/template" id="tmpl-madxartwork-finder">
	<div id="madxartwork-finder__search">
		<i class="eicon-search"></i>
		<input id="madxartwork-finder__search__input" placeholder="<?php echo __( 'Type to find anything in madxartwork', 'madxartwork' ); ?>">
	</div>
	<div id="madxartwork-finder__content"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-finder-results-container">
	<div id="madxartwork-finder__no-results"><?php echo __( 'No Results Found', 'madxartwork' ); ?></div>
	<div id="madxartwork-finder__results"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-finder__results__category">
	<div class="madxartwork-finder__results__category__title">{{{ title }}}</div>
	<div class="madxartwork-finder__results__category__items"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-finder__results__item">
	<a href="{{ url }}" class="madxartwork-finder__results__item__link">
		<div class="madxartwork-finder__results__item__icon">
			<i class="eicon-{{{ icon }}}"></i>
		</div>
		<div class="madxartwork-finder__results__item__title">{{{ title }}}</div>
		<# if ( description ) { #>
			<div class="madxartwork-finder__results__item__description">- {{{ description }}}</div>
		<# } #>
	</a>
	<# if ( actions.length ) { #>
		<div class="madxartwork-finder__results__item__actions">
		<# jQuery.each( actions, function() { #>
			<a class="madxartwork-finder__results__item__action madxartwork-finder__results__item__action--{{ this.name }}" href="{{ this.url }}" target="_blank">
				<i class="eicon-{{{ this.icon }}}"></i>
			</a>
		<# } ); #>
		</div>
	<# } #>
</script>
