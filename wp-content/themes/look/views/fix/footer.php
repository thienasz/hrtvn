</div>
<section id="myModal" class="modal" aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button class="close" aria-label="Close" data-dismiss="modal" type="button">
				<span aria-hidden="true">&#xD7;</span>
			</button>
			<div class="content-item row product-quickview-content">

			</div>
		</div>
	</div>
</section>
<section id="searchModal" class="modal fade" aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<button class="close" aria-label="Close" data-dismiss="modal" type="button">
				<span aria-hidden="true">&#xD7;</span>
			</button>
			<div class="content-item">
				<h3 class="screen-reader-text" for="search"><?php _e( 'Search', 'look')?></h3>
				<p><?php _e( 'Type your keyword and hit enter button for result', 'look' ); ?></p>
				<?php if ( class_exists( 'WooCommerce' ) ) :
				get_product_search_form();
				else :
					get_search_form();
				endif ; ?>
			</div>
		</div>
	</div>
</section>
<footer id="footer" <?php look_schema_metadata( array( 'context' => 'footer' ) ); ?>>
	<div class="container-fluid">
		<div class="row footer-menu">
            <?php if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
			<?php
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-sidebar') ) :
				endif; ?>
            <?php endif; ?>
		</div>
	</div>
	<div class="copyright">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<p><?php echo (look_get_option('look_footer_text')=='')?  __('The Look &copy;2015 - All Rights Reserved', 'look'): look_get_option('look_footer_text') ; ?> </p>
				</div>
			</div>
		</div>
	</div>
</footer>
</div>
<a href="#" class="scrollToTop">Go Top</a>

