<?php
/**
 * Codilight Lite Theme Customizer.
 *
 * @package Codilight_Lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function codilight_lite_customize_register( $wp_customize ) {

	// Load custom controls
	require_once get_template_directory() . '/inc/customizer-controls.php';

	// Remove default sections
	//$wp_customize->remove_section('colors');
	//$wp_customize->remove_section('background_image');

	// Remove default control.

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/*------------------------------------------------------------------------*/
    /*  Site Identity apply_filters('the_title', '  My Custom Title (tm)  ');
    /*------------------------------------------------------------------------*/

    	$wp_customize->add_setting( 'codilight_lite_site_logo',
			array(
				'sanitize_callback' => 'codilight_lite_sanitize_file_url',
				// 'default'           => esc_url( get_template_directory_uri() . '/assets/images/logo.png' )
				'default'           => apply_filters('customizer_default_logo', esc_url( get_template_directory_uri() . '/assets/images/logo.png' ) )
			)
		);
    	$wp_customize->add_control( new WP_Customize_Image_Control(
            $wp_customize,
            'codilight_lite_site_logo',
				array(
					'label' 		=> __('Site Logo', 'codilight-lite'),
					'section' 		=> 'title_tagline',
					'description'   => esc_html__('Your site logo', 'codilight-lite'),
				)
			)
		);

	/*------------------------------------------------------------------------*/
    /*  Layout
    /*------------------------------------------------------------------------*/
	$wp_customize->add_section( 'codilight_lite_layout' ,
		array(
			'priority'    => 23,
			'title'       => __( 'Site Layout', 'codilight-lite' ),
			'description' => '',
		)
	);

		$wp_customize->add_setting( 'layout_sidebar', array(
			'sanitize_callback' => 'codilight_lite_sanitize_layout_sidebar',
			'default'           => 'right',
		) );
		$wp_customize->add_control( 'layout_sidebar', array(
			'label'      => esc_html__( 'Default Sidebar Position', 'codilight-lite' ),
			'section'    => 'codilight_lite_layout',
			'type'       => 'radio',
			'choices'    => array(
				'left'   => 'Left Sidebar',
				'right'  => 'Right Sidebar',
			),
		) );

		$wp_customize->add_setting( 'layout_frontpage_posts', array(
			'sanitize_callback' => 'codilight_lite_sanitize_layout',
			'default'           => 'grid',
		) );
		$wp_customize->add_control( 'layout_frontpage_posts', array(
			'label'      => esc_html__( 'Front Page Posts Layout', 'codilight-lite' ),
			'section'    => 'codilight_lite_layout',
			'type'       => 'radio',
			'choices'    => array(
				'list'   => 'List',
				'grid'   => 'Grid',
			),
		) );

		$wp_customize->add_setting( 'layout_archive_posts', array(
			'sanitize_callback' => 'codilight_lite_sanitize_layout',
			'default'           => 'grid',
		) );
		$wp_customize->add_control( 'layout_archive_posts', array(
			'label'      => esc_html__( 'Archive Page Posts Layout', 'codilight-lite' ),
			'section'    => 'codilight_lite_layout',
			'type'       => 'radio',
			'choices'    => array(
				'list'   => 'List',
				'grid'   => 'Grid',
			),
			'description' => esc_html__( 'Category, Tag, Author, Archive Page ...', 'codilight-lite' ),
		) );

}
add_action( 'customize_register', 'codilight_lite_customize_register' );


/*------------------------------------------------------------------------*/
/*  OnePress Sanitize Functions.
/*------------------------------------------------------------------------*/

function codilight_lite_sanitize_file_url( $file_url ) {
	$output = '';
	$filetype = wp_check_filetype( $file_url );
	if ( $filetype["ext"] ) {
		$output = esc_url( $file_url );
	}
	return $output;
}

function codilight_lite_sanitize_number( $input ) {
    return force_balance_tags( $input );
}

function codilight_lite_sanitize_layout_sidebar( $layout_sidebar ) {
    if ( $layout_sidebar == 'left' ) {
		return 'left';
	} else {
		return 'right';
	}
}

function codilight_lite_sanitize_layout( $layout ) {
    if ( $layout == 'list' ) {
		return 'list';
	} else {
		return 'grid';
	}
}

function codilight_lite_sanitize_hex_color( $color ) {
	if ( $color === '' ) {
		return '';
	}
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}
	return null;
}

function codilight_lite_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
		return 1;
    } else {
		return 0;
    }
}

function codilight_lite_sanitize_text( $string ) {
	return wp_kses_post( force_balance_tags( $string ) );
}

function codilight_lite_sanitize_html_input( $string ) {
	return wp_kses_allowed_html( $string );
}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function codilight_lite_customize_preview_js() {
	wp_enqueue_script( 'codilight_lite_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'codilight_lite_customize_preview_js' );