<?php
namespace madxartworkPro\Modules\ThemeBuilder\Classes;

use madxartworkPro\Modules\ThemeBuilder\Documents;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Templates_Types_Manager {
	private $docs_types = [];

	public function __construct() {
		add_action( 'madxartwork/documents/register', [ $this, 'register_documents' ] );
	}

	public function get_types_config() {
		$config = [];

		$document_types = Plugin::madxartwork()->documents->get_document_types();

		foreach ( $document_types as $type => $document_type ) {
			$properties = $document_type::get_properties();

			if ( ( new $document_type() ) instanceof Documents\Theme_Document ) {
				$config[ $type ] = $properties;
			}
		}

		return $config;
	}

	public function register_documents() {
		$this->docs_types = [
			'section' => Documents\Section::get_class_full_name(),
			'header' => Documents\Header::get_class_full_name(),
			'footer' => Documents\Footer::get_class_full_name(),
			'single' => Documents\Single::get_class_full_name(),
			'archive' => Documents\Archive::get_class_full_name(),
		];

		foreach ( $this->docs_types as $type => $class_name ) {
			Plugin::madxartwork()->documents->register_document_type( $type, $class_name );
		}
	}
}
