<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Runtime\Runtime;

use function NewfoldLabs\WP\ModuleLoader\register;

if ( function_exists( 'add_action' ) ) {

	add_action(
		'plugins_loaded',
		function () {
			register(
				array(
					'name'     => 'runtime',
					'label'    => __( 'Runtime', 'wp-module-runtime' ),
					'callback' => function ( Container $container ) {
						$runtime = new Runtime( $container );
						$runtime->loadIntoPage( 'load-toplevel_page_' . $container->plugin()->id );
					},
					'isActive' => true,
					'isHidden' => true,
				)
			);
		}
	);

}