<?php
namespace cnb\admin\action;

// don't load directly
defined( 'ABSPATH' ) || die( '-1' );

class CnbActionType {

	/**
	 * @param string $type
	 * @param string $name
	 * @param bool $isPro
	 */
	public function __construct( $type, $name, $isPro ) {
		$this->type  = $type;
		$this->name  = $name;
		$this->isPro = $isPro;
	}

	/**
	 * PHONE, EMAIL, etc
	 * @var string
	 */
	public $type;

	/**
	 * Display name, such as "💬 Phone", "🔗 Link", etc
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Whether this feature is a Pro feature
	 * @var boolean
	 */
	public $isPro;

}
