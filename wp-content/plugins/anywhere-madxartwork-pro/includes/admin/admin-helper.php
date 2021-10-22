<?php

namespace Aepro\Admin;

use Aepro\Helper;

class AdminHelper{
    private static $_instance;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->register_ajax_function();
    }

    private function register_ajax_function(){
        add_action('wp_ajax_ae_prev_post',[ $this, 'ae_preview_post']);
        add_action('wp_ajax_ae_prev_term',[ $this, 'ae_preview_term']);
        add_action('wp_ajax_ae_acf_repeater_fields', [ $this, 'ae_acf_repeater_fields']);
    }


    public function ae_preview_term(){
        $result = [];
        $q = $_REQUEST['q'];
        $taxonomy = $_REQUEST['taxonomy'];
        $terms = get_terms( $taxonomy, array( 'name__like' => $q, 'fields' => 'id=>name', 'hide_empty' => false ) );

        foreach($terms as $tid => $term){
            $result[] = [
                'id'  => $tid,
                'text'  => $term
            ];
        }

        wp_send_json_success($result);
    }

    public function ae_preview_post(){
        $results = [];
        $params = $query_params = [
            's'         => $_REQUEST['q'],
            'post_type' => $_REQUEST['post_type']
        ];
        $query = new \WP_Query( $params );

        foreach ( $query->posts as $post ) {
            $results[] = [
                'id'   => $post->ID,
                'text' => $post->post_title,
            ];
        }

        wp_send_json_success( $results );
    }

    public function ae_acf_repeater_fields(){
    	$helper = new Helper();
        $fields = $helper->ae_acf_get_field_objects($_REQUEST['post_id']);
        $results = [];

        if( $fields )
        {
            foreach( $fields as $field_name => $field )
            {
                if($field['type'] == 'repeater'){
                    $results[] = [
                      'id' => $field['name'],
                      'text' => $field['label']
                    ] ;
                }
            }
        }
        wp_send_json_success( $results );

    }
}

AdminHelper::instance();