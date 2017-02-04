<body <?php body_class( 'fixed-layout' ); look_schema_metadata( array( 'context' => 'body' ) ); ?>>
	<div class="push-menu visible-xs visible-sm">
		<?php wp_nav_menu(array('theme_location' => 'main-menu', 'container_id' => 'mobile-menu', 'walker' => new Look_Menu_Maker_Walker() )); ?>
		<a id="close-menu" href="javascript:void(0);">&#xD7;</a>
	</div>
	<div id="wrapper">
		<?php if(look_get_option('header')== 0): ?>
			<header id="header" class="header header-layout-1" <?php look_schema_metadata( array( 'context' => 'header' ) ); ?>>
				<div class="header-top">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<?php look_social_block(); ?>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<h1 class="visual-hidden"><?php wp_title(); ?></h1>
								<div class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="regular-logo" src="<?php echo (look_get_option('look_logo')=='')?  ASSETS_URI.'/images/logo.png': look_get_option('look_logo') ; ?>" alt="look"/><img class="retina-logo" src="<?php echo (look_get_option('look_retina_logo')=='')?  ASSETS_URI.'/images/logo2x.png': look_get_option('look_retina_logo') ; ?>" alt="look"/></a></div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 top-menu">
								<?php if ( class_exists( 'WooCommerce' ) ) : ?>
									<?php
									global $woocommerce;
									$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
									$logout_url = '';
									if ( $myaccount_page_id ) {

										$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );

										if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' )
											$logout_url = str_replace( 'http:', 'https:', $logout_url );
									}
									?>
									<ul>
										<li class="menu-toggle visible-sm visible-xs icon"><a href="javascript:void(0);" title="<?php _e('Menu','look')?>" id="menu-toggle">Menu</a></li>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>
										<?php if ( class_exists( 'WooCommerce' ) ) : ?>
											<li class="cart icon"><a href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" title="<?php _e('Cart','look')?>"><?php _e('Cart','look');?><span class="mini-cart-total-item"><?php echo WC()->cart->cart_contents_count; ?></span></a>
													<?php the_widget('WC_Widget_Cart');?>
											</li>
											<?php if(function_exists('yith_wcwl_count_products')):?>
												<li class="wishlist icon"><a href="<?php echo esc_url( get_permalink(get_option( 'yith_wcwl_wishlist_page_id' )) ); ?>" title="<?php _e('Wishlist','look')?>"><?php _e('Wishlist','look'); ?><span class="wishlistcount"><?php echo yith_wcwl_count_products(); ?></span></a></li>
											<?php endif; ?>
											<li class="my-account <?php if (is_user_logged_in()):?> logged <?php endif; ?> icon"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','look'); ?>"><?php _e('My Account','look'); ?></a>
												<ul>
													<?php if (is_user_logged_in()):?>
														<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e('My Account','look'); ?></a></li>
														<li><a href="<?php echo (string)$logout_url; ?>"><?php _e('Logout','look'); ?></a></li>
													<?php else: ?>
														<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e('Register / Login','look'); ?></a></li>
													<?php endif; ?>
												</ul>
											</li>

										<?php endif ; ?>
									</ul>
								<?php else: ?>
									<ul>
										<li class="menu-toggle visible-sm visible-xs icon"><a href="javascript:void(0);" title="<?php _e('Menu','look')?>" id="menu-toggle">Menu</a></li>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>
									</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="header-middle hidden-sm hidden-xs">
					<div class="container-fluid">
						<div class="row">
							<nav class="main-menu col-lg-12 col-md-12">
								<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
							</nav>
						</div>
					</div>
				</div>
			</header>
		<?php elseif(look_get_option('header')== 1): ?>
			<header id="header" class="header header-layout-2" <?php look_schema_metadata( array( 'context' => 'header' ) ); ?>>
				<div class="header-top">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<?php look_social_block(); ?>
							</div> 
							<div class="col-lg-4 col-md-4 promo-text hidden-sm hidden-xs">
								<span><?php echo look_get_option('look_top_notice'); ?></span>
							</div>
							<div class="col-lg-4 col-md-4 hidden-sm hidden-xs top-menu">
								<?php if ( class_exists( 'WooCommerce' ) ) : ?>
									<?php
									global $woocommerce;
									$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
									$logout_url = '';
									if ( $myaccount_page_id ) {

										$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );

										if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' )
											$logout_url = str_replace( 'http:', 'https:', $logout_url );
									}
									?>
									<ul>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>	
										<?php if ( class_exists( 'WooCommerce' ) ) : ?>
											<li class="cart icon"><a href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" title="<?php _e('Cart','look')?>"><?php _e('Cart','look');?><span class="mini-cart-total-item"><?php echo WC()->cart->cart_contents_count; ?></span></a>
												<?php the_widget('WC_Widget_Cart');?>
											</li>
											<?php if(function_exists('yith_wcwl_count_products')):?>
												<li class="wishlist icon"><a href="<?php echo esc_url(  get_permalink(get_option( 'yith_wcwl_wishlist_page_id' )) ); ?>" title="<?php _e('Wishlist','look')?>"><?php _e('Wishlist','look'); ?><span class="wishlistcount"><?php echo yith_wcwl_count_products(); ?></span></a></li>
											<?php endif; ?>
											<li class="my-account <?php if (is_user_logged_in()):?> logged <?php endif; ?> icon"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','look'); ?>"><?php _e('My Account','look'); ?></a>
												<ul>
													<?php if (is_user_logged_in()):?>
														<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e('My Account','look'); ?></a></li>
														<li><a href="<?php echo (string)$logout_url; ?>"><?php _e('Logout','look'); ?></a></li>
													<?php else: ?>
														<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e('Register / Login','look'); ?></a></li>
													<?php endif; ?>
												</ul>
											</li>
										<?php endif ; ?>
									</ul>
								<?php else: ?>
									<ul>
										<li class="menu-toggle visible-sm visible-xs icon"><a href="javascript:void(0);" title="<?php _e('Menu','look')?>" id="menu-toggle">Menu</a></li>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>
									</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="header-middle">
					<div class="container-fluid">
						<div class="row">
							<h1 class="visual-hidden"><?php wp_title(); ?></h1>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<nav class="main-menu">
									<div class="col-lg-5 col-md-5 hidden-sm hidden-xs menu-left"><?php wp_nav_menu( array( 'theme_location' => 'menu-left' ) ); ?></div>
									<div class="logo col-lg-2 col-md-2 col-sm-12 col-xs-12"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="regular-logo" src="<?php echo (look_get_option('look_logo')=='')?  ASSETS_URI.'/images/logo.png': look_get_option('look_logo') ; ?>" alt="look"/><img class="retina-logo" src="<?php echo (look_get_option('look_retina_logo')=='')?  ASSETS_URI.'/images/logo2x.png': look_get_option('look_retina_logo') ; ?>" alt="look"/></a></div>
									<div class="col-lg-5 col-md-5 col-sm-4 hidden-sm hidden-xs menu-right"><?php wp_nav_menu( array( 'theme_location' => 'menu-right' ) ); ?></div>
								</nav>
							</div>
							<div class="visible-sm visible-xs col-sm-12 col-xs-12 mobile-top-menu top-menu">
								<?php if ( class_exists( 'WooCommerce' ) ) : ?>
									<?php
									global $woocommerce;
									$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
									$logout_url = '';
									if ( $myaccount_page_id ) {

										$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );

										if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' )
											$logout_url = str_replace( 'http:', 'https:', $logout_url );
									}
									?>
									<ul>
										<li class="menu-toggle icon"><a href="javascript:void(0);" title="<?php _e('Menu','look')?>" id="menu-toggle">Menu</a></li>
										<?php if ( class_exists( 'WooCommerce' ) ) : ?>
											<?php if (is_user_logged_in()):?>
												<li class="my-account <?php if (is_user_logged_in()):?> logged <?php endif; ?> icon"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','look'); ?>"><?php _e('My Account','look'); ?></a></li>
											<?php else: ?>
												<li class="my-account <?php if (is_user_logged_in()):?> logged <?php endif; ?> icon"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Register / Login','look'); ?>"><?php _e('Register / Login','look'); ?></a></li>
											<?php endif; ?>
											<li class="cart icon"><a href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" title="<?php _e('Cart','look')?>"><?php _e('Cart','look');?><span class="mini-cart-total-item"><?php echo WC()->cart->cart_contents_count; ?></span></a>
											<?php the_widget('WC_Widget_Cart');?></li>
											<?php if(function_exists('yith_wcwl_count_products')):?>
												<li class="wishlist icon"><a href="<?php echo esc_url( home_url( '/wishlist' ) ); ?>" title="<?php _e('Wishlist','look')?>"><?php _e('Wishlist','look'); ?><span class="wishlistcount"><?php echo yith_wcwl_count_products(); ?></span></a></li>
											<?php endif; ?>

										<?php endif ; ?>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>
									</ul>
								<?php else: ?>
									<ul>
										<li class="menu-toggle visible-sm visible-xs icon"><a href="javascript:void(0);" title="<?php _e('Menu','look')?>" id="menu-toggle">Menu</a></li>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>
									</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</header>
		<?php elseif(look_get_option('header')== 2): ?>
			<header id="header" class="header header-layout-3" <?php look_schema_metadata( array( 'context' => 'header' ) ); ?>>
				<div class="header-top">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<?php look_social_block(); ?>
							</div> 
							<div class="col-lg-4 col-md-4 promo-text hidden-sm hidden-xs">
								<span><?php echo look_get_option('look_top_notice'); ?></span>
							</div>
							<div class="col-lg-4 col-md-4 hidden-sm hidden-xs top-menu">
								<?php if ( class_exists( 'WooCommerce' ) ) : ?>
									<?php
									global $woocommerce;
									$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
									$logout_url = '';
									if ( $myaccount_page_id ) {

										$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );

										if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' )
											$logout_url = str_replace( 'http:', 'https:', $logout_url );
									}
									?>
									<ul>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>	
										<?php if ( class_exists( 'WooCommerce' ) ) : ?>
											<li class="cart icon"><a href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" title="<?php _e('Cart','look')?>"><?php _e('Cart','look');?><span class="mini-cart-total-item"><?php echo WC()->cart->cart_contents_count; ?></span></a>
												<?php the_widget('WC_Widget_Cart');?>
											</li>
											<?php if(function_exists('yith_wcwl_count_products')):?>
												<li class="wishlist icon"><a href="<?php echo esc_url(  get_permalink(get_option( 'yith_wcwl_wishlist_page_id' )) ); ?>" title="<?php _e('Wishlist','look')?>"><?php _e('Wishlist','look'); ?><span class="wishlistcount"><?php echo yith_wcwl_count_products(); ?></span></a></li>
											<?php endif; ?>
											<li class="my-account <?php if (is_user_logged_in()):?> logged <?php endif; ?> icon"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','look'); ?>"><?php _e('My Account','look'); ?></a>
												<ul>
													<?php if (is_user_logged_in()):?>
														<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e('My Account','look'); ?></a></li>
														<li><a href="<?php echo (string)$logout_url; ?>"><?php _e('Logout','look'); ?></a></li>
													<?php else: ?>
														<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e('Register / Login','look'); ?></a></li>
													<?php endif; ?>
												</ul>
											</li>
										<?php endif ; ?>
									</ul>
								<?php else: ?>
									<ul>
										<li class="menu-toggle visible-sm visible-xs icon"><a href="javascript:void(0);" title="<?php _e('Menu','look')?>" id="menu-toggle">Menu</a></li>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>
									</ul>
								<?php endif; ?>

							</div>
						</div>
					</div>
				</div>
				<div class="header-middle">
					<div class="container-fluid">
						<div class="row">
							<h1 class="visual-hidden"><?php wp_title(); ?></h1>
							<nav class="main-menu">
								<div class="logo col-lg-3 col-md-3 col-sm-12 col-xs-12"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="regular-logo" src="<?php echo (look_get_option('look_logo')=='')?  ASSETS_URI.'/images/logo.png': look_get_option('look_logo') ; ?>" alt="look"/><img class="retina-logo" src="<?php echo (look_get_option('look_retina_logo')=='')?  ASSETS_URI.'/images/logo2x.png': look_get_option('look_retina_logo') ; ?>" alt="look"/></a></div>
								<div class="col-lg-9 col-md-9 col-sm-9 hidden-sm hidden-xs"><?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?></div>
							</nav>
							<div class="visible-sm visible-xs col-sm-12 col-xs-12 mobile-top-menu top-menu">
								<?php if ( class_exists( 'WooCommerce' ) ) : ?>
									<?php
									global $woocommerce;
									$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
									$logout_url = '';
									if ( $myaccount_page_id ) {

										$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );

										if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' )
											$logout_url = str_replace( 'http:', 'https:', $logout_url );
									}
									?>
									<ul>
										<li class="menu-toggle icon"><a href="javascript:void(0);" title="<?php _e('Menu','look')?>" id="menu-toggle">Menu</a></li>
										<?php if ( class_exists( 'WooCommerce' ) ) : ?>
											<?php if (is_user_logged_in()):?>
												<li class="my-account <?php if (is_user_logged_in()):?> logged <?php endif; ?> icon"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','look'); ?>"><?php _e('My Account','look'); ?></a></li>
											<?php else: ?>
												<li class="my-account <?php if (is_user_logged_in()):?> logged <?php endif; ?> icon"><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Register / Login','look'); ?>"><?php _e('Register / Login','look'); ?></a></li>
											<?php endif; ?>
											<li class="cart icon"><a href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" title="<?php _e('Cart','look')?>"><?php _e('Cart','look');?><span class="mini-cart-total-item"><?php echo WC()->cart->cart_contents_count; ?></span></a><?php the_widget('WC_Widget_Cart');?></li>
											<?php if(function_exists('yith_wcwl_count_products')):?>
												<li class="wishlist icon"><a href="<?php echo esc_url( home_url( '/wishlist' ) ); ?>" title="<?php _e('Wishlist','look')?>"><?php _e('Wishlist','look'); ?><span class="wishlistcount"><?php echo yith_wcwl_count_products(); ?></span></a></li>
											<?php endif; ?>

										<?php endif ; ?>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>
									</ul>
								<?php else: ?>
									<ul>
										<li class="menu-toggle visible-sm visible-xs icon"><a href="javascript:void(0);" title="<?php _e('Menu','look')?>" id="menu-toggle">Menu</a></li>
										<li class="mobile-search icon"><a href="javascript:void(0);" title="<?php _e('Search','look')?>" data-target="#searchModal" data-toggle="modal"><?php _e('Search','look')?></a></li>
									</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</header>
		<?php endif; ?>
		<?php look_get_template( 'title-bar' ); ?>
		<div id="content">


