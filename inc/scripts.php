<?php
/**
 * Enqueue Scripts
 *
 * @package Farm Factory
 */

/**
 * Never worry about cache again!
 */
function farmfactory_load_scripts() {

	wp_register_script( 'web3', FARMFACTORY_URL . 'assets/js/web3.min.js', array(), '1.5.1', true );
	wp_register_script( 'web3modal', FARMFACTORY_URL . 'assets/js/web3modal.min.js', array(), '1.9.4', true );
	wp_register_script( 'bignumber', FARMFACTORY_URL . 'assets/js/bignumber.min.js', array(), '8.0.2', true );
	wp_register_script( 'web3-provider', FARMFACTORY_URL . 'assets/js/web3-provider.min.js', array(), '1.7.0', true );
	wp_register_script( 'fortmatic', FARMFACTORY_URL . 'assets/js/fortmatic.js', array(), '2.0.6', true );

	$dependencies = array(
		'web3',
		'web3modal',
		'bignumber',
		'web3-provider',
		'fortmatic'
	);

	// create my own version codes.
	$my_js_ver  = gmdate( 'ymd-Gis', filemtime( FARMFACTORY_PATH . 'lib/farmfactory.js' ) );
	$my_css_ver = gmdate( 'ymd-Gis', filemtime( FARMFACTORY_PATH . 'assets/css/farmfactory.css' ) );

	wp_enqueue_script( 'farmfactory-js', FARMFACTORY_URL . 'lib/farmfactory.js', $dependencies, $my_js_ver, true );
  wp_enqueue_script( 'token-price', FARMFACTORY_URL . 'assets/js/token-price.js', array(), '1.0.0', true);

	wp_enqueue_style( 'farmfactory-css', FARMFACTORY_URL . 'assets/css/farmfactory.css', false, $my_css_ver );

	if ( wp_count_posts( 'farmfactory' ) ) {

	$inline_scripts = '
	var networkName = "' . get_option( 'farmfactory_networkName', 'ropsten' ) . '";

	var chainIds = {
	  "mainnet": 1,
      "ropsten": 3,
      "rinkeby": 4,
      "kovan": 42,
      "bsc": 56,
      "bsc_test": 97,
      "matic": 137,
      "mumbai": 80001,
      "xdai": 100,
      "aurora": 1313161554
	};

	var chainId = chainIds[networkName.toLowerCase()];

	var walletConnectOptions;

	if (chainId === 56 || chainId === 97 || chainId === "56" || chainId === "97") {
	  walletConnectOptions = {
		infuraId: "' . get_option( 'farmfactory_infura_id', farmfactory_default_infura_id() ) . '",
		rpc: {
		  1: "https://bsc-dataseed.binance.org",
		  56: "https://bsc-dataseed.binance.org",
		  97: "https://data-seed-prebsc-1-s1.binance.org:8545",
		},
		network: "binance",
      };
	} else if (chainId === 100 || chainId === "100") {
		walletConnectOptions = {
		  infuraId: "' . get_option( 'farmfactory_infura_id', farmfactory_default_infura_id() ) . '",
		  rpc: {
			1: "https://rpc.xdaichain.com",
			100: "https://rpc.xdaichain.com",
		  },
		  network: "xdai",
		};
	}
	else {
	  walletConnectOptions = {
	    infuraId: "' . get_option( 'farmfactory_infura_id', farmfactory_default_infura_id() ) . '",
	  }
	}

	farmFactory.init({
		networkName: networkName,
		wallet: {
			providerOptions: {
				walletconnect: {
					package: window.WalletConnectProvider.default,
					options: walletConnectOptions,
				},
				fortmatic: {
					package: window.Fortmatic,
					options: {
						key: "' . get_option( 'farmfactory_fortmatic_key_deprecated' ) . '",
					},
				},
			},
		},
	});
	';

	wp_add_inline_script( 'farmfactory-js', $inline_scripts, 'after' );
	}

}
add_action('wp_enqueue_scripts', 'farmfactory_load_scripts');

/**
 * Admin Enqueue Scripts
 *
 * @param string $hook Current page.
 */
function farmfactory_admin_scripts( $hook ) {

	global $typenow;

	if ( 'post-new.php' === $hook || 'post.php' === $hook || 'toplevel_page_FARMFACTORY' === $hook ) {
		if ( 'toplevel_page_FARMFACTORY' === $hook || 'farmfactory' === $typenow ) {

			wp_enqueue_style( 'farmfactory-admin', FARMFACTORY_URL . 'assets/css/farmfactory-admin.css', false, FARMFACTORY_VER );

			$ver = wp_rand( 1, 2222222 );

			wp_enqueue_script( 'farmfactory-deployer', '//farm.wpmix.net/wp-content/plugins/farmfactory/lib/farmdeployer.js', array(), $ver, true );

			wp_enqueue_script( 'farmfactory-admin', FARMFACTORY_URL . 'assets/js/farmfactory-admin.js', array( 'farmfactory-deployer' ), $ver, true );

			$post_type_object = get_post_type_object( $typenow );

			/* Translatable string */
			wp_localize_script('farmfactory-admin', 'farmfactory',
				array(
					'l18n' => array(
						'featuredImage'       => esc_html__( 'Staking Token Icon', 'farmfactory' ),
						'setFeaturedImage'    => $post_type_object->labels->set_featured_image,
						'removeFeaturedImage' => $post_type_object->labels->remove_featured_image,
						'clickTheImage'       => esc_html__( 'Click the image to edit or update', 'farmfactory' ),
					),
				)
			);

		}
	}

}
add_action( 'admin_enqueue_scripts', 'farmfactory_admin_scripts' );
