<body <?php body_class( 'home-left-layout' ); ?>>
	<div id="wrapper">
		<div id="sidebar">
			<a href="javascript:void(0);" class="mobile-button visible-sm visible-xs" id="close-btn">Menu</a>
			<header id="header">
				<h1 class="visual-hidden"><?php wp_title(); ?></h1>
				<div class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="regular-logo" src="<?php echo (look_get_option('look_logo')=='')?  ASSETS_URI.'/images/logo.png': look_get_option('look_logo') ; ?>" alt="look"/><img class="retina-logo" src="<?php echo (look_get_option('look_retina_logo')=='')?  ASSETS_URI.'/images/logo2x.png': look_get_option('look_retina_logo') ; ?>" alt="look"/></a></div>
				<ul class="top-menu">
					<li class="search"><a href="javascript:void(0);" title="<?php _e('Search','look'); ?>" data-target="#searchModal" data-toggle="modal"></a></li>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<li class="my-account"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','look'); ?>"></a></li>
                        <?php if(function_exists('yith_wcwl_count_products')):?>
                            <li class="wishlist icon"><a href="<?php echo esc_url(  get_permalink(get_option( 'yith_wcwl_wishlist_page_id' )) ); ?>" title="<?php _e('Wishlist','look')?>"><?php _e('Wishlist','look'); ?><span class="wishlistcount"><?php echo yith_wcwl_count_products(); ?></span></a></li>
                        <?php endif; ?>
						<li class="cart"><a  href="javascript:void(0);" data-target="#cartModal" data-toggle="modal" title="<?php _e('Cart','look'); ?>"><span><?php echo WC()->cart->cart_contents_count; ?></span></a></li>
					<?php endif; ?>
				</ul>
				<nav class="main-menu">
					<?php wp_nav_menu(array('theme_location' => 'main-menu', 'container_id' => 'mobile-menu', 'walker' => new Look_Menu_Maker_Walker() )); ?>
				</nav>
			</header>
			<section id="footer-1">
                <?php look_social_block(); ?>
				<div class="copyright">
					<p><?php echo (look_get_option('look_footer_text')=='')?  __('The Look &copy;2015 - All Rights Reserved', 'look'): look_get_option('look_footer_text') ; ?></p>
				</div>
			</section>
		</div>
		<div class="header-top">
			<a href="javascript:void(0);" class="mobile-button visible-sm visible-xs" id="menu-toggle">Menu</a>
		</div>
		<div id="content">
			<?php look_get_template( 'title-bar' ); ?>