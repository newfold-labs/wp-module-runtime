<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Runtime\Runtime;

use function NewfoldLabs\WP\ModuleLoader\register;

			register(
				array(
					'name'     => 'runtime',
					'label'    => __( 'Runtime', 'wp-runtime' ),
					'callback' => function ( Container $container ) {
						$runtime = new Runtime( $container );
						$runtime->loadIntoPage( 'admin_enqueue_scripts' );
					},
					'isActive' => true,
					'isHidden' => true,
				)
			);

