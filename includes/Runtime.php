<?php

namespace NewfoldLabs\WP\Module\Runtime;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * Class Runtime
 *
 * @package NewfoldLabs\WP\Module\Runtime
 */
class Runtime {
	/**
	 * Main Runtime file
	 *
	 * Entry point via bootstrap.php
	 */

	/**
	 * Container loaded from the brand plugin.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Runtime constructor.
	 *
	 * @param Container $container Container loaded from the brand plugin.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
		add_action( 'load-toplevel_page_' . $container->plugin()->id, array( $this, 'register_runtime' ) );
	}

	public function prepareData() {
		$sdk = apply_filters( 'newfold-runtime', array() );
		return array(
			'site'           => array( 
				'url' => \get_site_url(),
			),
			'admin_url'      => \admin_url(),
			'rest_url'       => \get_home_url() . '/index.php',
			'capabilities'   => $this->container->get('capabilities')->all(),
			'sdk'            => $sdk
		);
	}

	/**
	 * Load Runtime into the page.
	 */
	public function register_runtime() {
		\wp_register_script(
			'nfd-runtime',
			$this->container->plugin()->url . 'vendor/newfold-labs/wp-module-ecommerce/includes/runtime.js',
			array('wp-url'),
			'1.0.0'
		);
		\wp_add_inline_script(
			'nfd-runtime',
			'window.NewfoldRuntime =' . wp_json_encode( $this->prepareData( $this->container ) ) . ';',
			'before'
		);
		\wp_enqueue_script( 'nfd-runtime' );
	}

}
