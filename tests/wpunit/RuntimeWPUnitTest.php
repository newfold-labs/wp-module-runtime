<?php

namespace NewfoldLabs\WP\Module\Runtime;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * Tests for Runtime class.
 *
 * @covers \NewfoldLabs\WP\Module\Runtime\Runtime
 */
class RuntimeWPUnitTest extends \lucatume\WPBrowser\TestCase\WPTestCase {

	/**
	 * Creates a container mock with capabilities returning an array.
	 *
	 * @param array $capabilities Capabilities to return from capabilities->all().
	 * @return Container
	 */
	private function create_container_mock( array $capabilities = array() ) {
		// phpcs:disable Squiz.Commenting.VariableComment.Missing, Squiz.Commenting.FunctionComment.Missing -- anonymous class for test double
		$capabilities_obj = new class( $capabilities ) {
			private $caps;
			public function __construct( array $caps ) {
				$this->caps = $caps;
			}
			public function all() {
				return $this->caps;
			}
		};
		// phpcs:enable Squiz.Commenting.VariableComment.Missing, Squiz.Commenting.FunctionComment.Missing
		$container        = $this->createMock( Container::class );
		$container->method( 'get' )->with( 'capabilities' )->willReturn( $capabilities_obj );
		return $container;
	}

	/**
	 * Verifies loadIntoPage adds an action for the given page hook.
	 *
	 * @return void
	 */
	public function test_load_into_page_registers_action() {
		$container = $this->create_container_mock();
		$runtime   = new Runtime( $container );
		$runtime->loadIntoPage( 'admin_enqueue_scripts' );
		$this->assertNotFalse( has_action( 'admin_enqueue_scripts', array( $runtime, 'register_runtime' ) ) );
	}

	/**
	 * Verifies prepareRuntime returns an array with expected keys.
	 *
	 * @return void
	 */
	public function test_prepare_runtime_returns_expected_structure() {
		$container = $this->create_container_mock( array( 'manage_options' ) );
		$runtime   = new Runtime( $container );
		$data      = $runtime->prepareRuntime();
		$this->assertIsArray( $data );
		$this->assertArrayHasKey( 'siteUrl', $data );
		$this->assertArrayHasKey( 'siteTitle', $data );
		$this->assertArrayHasKey( 'adminUrl', $data );
		$this->assertArrayHasKey( 'homeUrl', $data );
		$this->assertArrayHasKey( 'restUrl', $data );
		$this->assertArrayHasKey( 'restNonce', $data );
		$this->assertArrayHasKey( 'capabilities', $data );
		$this->assertArrayHasKey( 'wpVersion', $data );
		$this->assertArrayHasKey( 'currentTheme', $data );
		$this->assertSame( array( 'manage_options' ), $data['capabilities'] );
		$this->assertNotEmpty( $data['restNonce'] );
	}

	/**
	 * Verifies prepareRuntime uses newfold_runtime filter.
	 *
	 * @return void
	 */
	public function test_prepare_runtime_is_filterable() {
		$container = $this->create_container_mock();
		$runtime   = new Runtime( $container );
		add_filter(
			'newfold_runtime',
			function ( $data ) {
				$data['customKey'] = 'filtered';
				return $data;
			},
			10
		);
		$data = $runtime->prepareRuntime();
		$this->assertArrayHasKey( 'customKey', $data );
		$this->assertSame( 'filtered', $data['customKey'] );
	}

	/**
	 * Verifies register_runtime registers nfd-runtime script and adds inline data.
	 *
	 * @return void
	 */
	public function test_register_runtime_registers_script() {
		$container = $this->create_container_mock();
		$runtime   = new Runtime( $container );
		$runtime->register_runtime();
		$this->assertTrue( wp_script_is( 'nfd-runtime', 'registered' ) );
		global $wp_scripts;
		$inline = $wp_scripts->get_inline_script_data( 'nfd-runtime', 'before' );
		$this->assertStringContainsString( 'window.NewfoldRuntime', $inline );
	}
}
