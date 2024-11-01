<?php

namespace Bubuku\Plugins\ShowTemplateName\Admin;

use const Bubuku\Plugins\ShowTemplateName\PLUGIN_NAME;

class FilterAction {

	public function __construct() {
		$this->_define_admin_hooks();
	}

	/**
	 * Add name column head
	 *
	 * @since    0.1
	 * @access   public
	 * 
	 * @param array $defaults Default values of columns name.
	 * 
	 * @return array<array>
	 */
	public function page_column_views( $defaults ) {
		$defaults['page-layout'] = __('Template', PLUGIN_NAME);
		return $defaults;
	}

	/**
	 * Add name in the column header.
	 *
	 * @since    0.1
	 * @access   public
	 * 
	 * @param string $column_name
	 * @param number $id 
	 * @return void	
	 */
	public function page_custom_column_views( $column_name, $id ) {
		if (  'page-layout' === $column_name ) {
			$set_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
			
			if ( empty($set_template ) || 'default' === $set_template ) {
				echo __('Default template', PLUGIN_NAME);
			} else {
				$aTemplates = get_page_templates();
				ksort( $aTemplates );
				foreach ( array_keys( $aTemplates ) as $template ) {
					if ( $set_template == $aTemplates[$template]) {
						echo $template;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Add selector in filters
	 * 
	 * @since    0.1
	 * @access   public
	 *
	 * @return void
	 */
	public function template_filter() {
		$screen = '';
		if ( is_admin() && function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
		}
		
		if ( $screen->id == 'edit-page' ) {
			
			$selected = '';
			if ( isset( $_GET['template_admin_filter'] ) ) {
				$selected = sanitize_text_field( $_GET['template_admin_filter'] );
			}
	
			$out = '<select name="template_admin_filter"><option value="All">'. __(' All templates', PLUGIN_NAME ) .'</option>';
				$aTemplates = get_page_templates();
				ksort( $aTemplates );
				foreach ( $aTemplates as $key => $value ) {
					$is_selected = ($selected === $value) ? 'selected' : '';
					$out .= '<option value="'. sanitize_text_field($value) .'" '. $is_selected .' >'. $key .'</option>';
				}
			$out .= '</select>';
			echo $out;
		}

		return false;
	}
	
	/**
	 * Add new parameter to search query
	 *
	 * @since    0.1
	 * @access   public
	 * 
	 * @param [type] $query
	 * @return void
	 */
	public function template_filter_results($query){
		
		$screen = '';
		if ( is_admin() && function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
		}
		 
		if ( is_object($screen) && $screen->id == 'edit-page' ) {
			if ( isset($_GET['template_admin_filter'] ) && 'All' !== $_GET['template_admin_filter']  ) {
				$template_id = sanitize_text_field($_GET['template_admin_filter']);
	
				$meta_query = array(
					// 'relation' => 'AND',
					array(
						'key' => '_wp_page_template',
						'value' => $template_id,
						'compare' => '=',
					)
				);
	
				$query->set('meta_query', $meta_query);
			}
		}
	}

	/**
	 * Add class to body
	 * 
	 * @since    0.1
	 * @access   public
	 * 
	 * 
	 * @param string $classes
	 * @return string
	 * 
	 */
	public function add_body_class( $classes ) {
		$template_slug = get_page_template_slug();
		if ($template_slug) {
			$page_name = basename($template_slug, '.php');
			$class_name = 'tpl-'. sanitize_html_class( $page_name );
			$classes .= ' ' . $class_name;
		}
		return $classes;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function _define_admin_hooks() {
		add_filter( 'manage_pages_columns', array( $this, 'page_column_views' ), 10, 1 );
		add_action( 'manage_pages_custom_column', array( $this, 'page_custom_column_views' ), 5, 2 );		
		// add select
		add_action( 'restrict_manage_posts', array( $this, 'template_filter' ) );
		// Filter query
		add_action( 'pre_get_posts', array( $this, 'template_filter_results' ) );
		// add Class to body
		add_filter( 'admin_body_class', array( $this, 'add_body_class' ) );
	}
}