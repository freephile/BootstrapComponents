<?php

namespace MediaWiki\Extension\BootstrapComponents\Tests\Unit\Components;

use MediaWiki\Extension\BootstrapComponents\Components\Tooltip;
use MediaWiki\Extension\BootstrapComponents\NestingController;
use MediaWiki\Extension\BootstrapComponents\Tests\Unit\ComponentsTestBase;
use \MWException;

/**
 * @covers  \MediaWiki\Extension\BootstrapComponents\Components\Tooltip
 *
 * @ingroup Test
 *
 * @group extension-bootstrap-components
 * @group mediawiki-databaseless
 *
 * @license GNU GPL v3+
 *
 * @since   1.0
 * @author  Tobias Oetterer
 */
class TooltipTest extends ComponentsTestBase {

	private $input = 'Tooltip test text';

	/**
	 * @throws \MWException
	 */
	public function testCanConstruct() {

		$this->assertInstanceOf(
			Tooltip::class,
			new Tooltip(
				$this->getComponentLibrary(),
				$this->getParserOutputHelper(),
				$this->createMock( NestingController::class ),
			)
		);
	}

	/**
	 * @param string $input
	 * @param array  $arguments
	 * @param string $expectedOutput
	 *
	 * @dataProvider placeMeArgumentsProvider
	 * @throws MWException
	 */
	public function testCanRender( $input, $arguments, $expectedOutput ) {
		$instance = new Tooltip(
			$this->getComponentLibrary(),
			$this->getParserOutputHelper(),
			$this->getNestingController()
		);

		$parserRequest = $this->buildParserRequest( $input, $arguments );

		/** @noinspection PhpParamsInspection */
		$generatedOutput = $instance->parseComponent( $parserRequest );

		if ( is_array( $generatedOutput ) ) {
			$generatedOutput = $generatedOutput[0];
		}

		$this->assertEquals( $expectedOutput, $generatedOutput );
	}

	/**
	 * @return array
	 */
	public function placeMeArgumentsProvider() {
		return [
			'simple'              => [
				$this->input,
				[ 'text' => 'simple' ],
				'<span class="bootstrap-tooltip" id="bsc_tooltip_NULL" data-toggle="tooltip" title="simple">' . $this->input . '</span>',
			],
			'empty'               => [
				'',
				[],
				'bootstrap-components-tooltip-target-missing',
			],
			'text missing'        => [
				$this->input,
				[],
				'bootstrap-components-tooltip-content-missing',
			],
			'id, style and class' => [
				$this->input,
				[ 'text' => 'simple', 'class' => 'dummy nice', 'style' => 'float:right;background-color:#80266e', 'id' => 'vera' ],
				'<span class="bootstrap-tooltip dummy nice" style="float:right;background-color:#80266e" id="vera" data-toggle="tooltip" title="simple">' . $this->input . '</span>',
			],
		];
	}
}
