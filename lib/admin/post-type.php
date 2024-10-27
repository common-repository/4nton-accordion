<?php

class Anton_Accordions_Post_Type {
	public $post_type = 'anton_accordion';
	public $taxonomy = 'anton_accordions';
	function __construct() {
		add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
		add_action( 'init', array( $this, 'post_type' ) );
		//add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		//add_action( 'save_post', array( $this, 'save_meta_box' ) ); 
		//add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		add_filter( 'manage_taxonomies_for_anton_accordion_columns', array( $this, 'manage_taxonomies' ) );
	}
	function admin_styles(){
		printf( '<style>body.post-type-%s #edit-slug-box, body.post-type-%s .row-actions .view { display: none; }</style>', $this->post_type, $this->post_type );
	}
	function post_type() {
		register_post_type(
			$this->post_type,
			array(
				'labels' => array(
					'name' 				=> __( 'Anton Accordions', ANTON_ACCORDION ),
					'singular_name' 	=> __( 'Anton Accordion', ANTON_ACCORDION ),
					'add_new' 			=> __( 'Add Accordion', ANTON_ACCORDION ),
					'add_new_item' 		=> __( 'Add New Accordion', ANTON_ACCORDION ),
					'edit_item' 		=> __( 'Edit Accordion', ANTON_ACCORDION ),
					'all_items'			=> __( 'Accordions', ANTON_ACCORDION ),
					'menu_name' 		=> __( 'Anton Accordions', ANTON_ACCORDION ),
					'view_item' 		=> false
				),
				'public'		 		=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> false,
				'query_var' 			=> false,
				'show_in_nav_menus' 	=> false,
				'exclude_from_search' 	=> true,
				'supports' 				=> array( 'title', 'editor', 'revisions' ),
				'taxonomies'   			=> array( $this->taxonomy ),
			)
		);
		register_taxonomy(
			$this->taxonomy,
			$this->post_type,
			array(
				'labels' => array(
					'name'          => __( 'Category', 'taxonomy general name', ANTON_ACCORDION ),
					'add_new_item'  => __( 'Add New Category', ANTON_ACCORDION ),
					'new_item_name' => __( 'New Category', ANTON_ACCORDION ),
				),
				'exclude_from_search'	=> true,
				'has_archive'	=> true,
				'hierarchical'	=> true,
				'rewrite' => false,
			)
		);
	}
	function manage_taxonomies( $taxonomies ) {
		$taxonomies[] = $this->taxonomy;
		return $taxonomies;	
	}
	function template_redirect(){
		if( get_post_type() == $this->post_type ){
			wp_redirect( get_bloginfo( 'url' ) );
			exit();
		}
	}
	function add_meta_box(){
		add_meta_box(
			'anton_theme_meta_box',
			__( 'Extra Fields', ANTON_ACCORDION ),
			array( $this, 'meta_box_callback' ),
			$this->post_type,
			'normal'
		);
	}
	function meta_box_callback( $post ) {
		wp_nonce_field( 'anton_accordion_meta_box', 'anton_accordion_meta_box_nonce' );
		$img_url = ANTON_WIDGETS_IMG_URL . '/no-image.png';
		if( wp_get_attachment_image_url( get_post_meta( $post->ID, 'cover_image', true ), 'full' ) ){
			$img_url = wp_get_attachment_image_url( get_post_meta( $post->ID, 'cover_image', true ), 'full' );
		}
		?>
        <table>
        	<tbody>
            	<tr>
                	<td colspan="2">
						<h3><?php _e( 'Cover Image', ANTON_ACCORDION ); ?></h3>
                        <div id="<?php anton_widget_e( 'upload' ); ?>" data-id="#<?php anton_widget_e( 'cover_image' ); ?>">
                            <img id="<?php anton_widget_e( 'cover_image' ); ?>-img" src="<?php echo $img_url; ?>" style=" max-width: 100%; " />
                        </div>
                        <input id="<?php anton_widget_e( 'cover_image' ); ?>-input" name="<?php anton_widget_e( 'cover_image' ); ?>" type="hidden" value="<?php echo get_post_meta( $post->ID, 'cover_image', true ); ?>" />
                    </td>
                </tr>
            	<tr>
                	<td><h3><?php _e( 'Gender', ANTON_ACCORDION ); ?></h3></td>
                	<td>
                        <label><input type="radio" id="anton_accordion_gender" name="gender" value="male" <?php checked( get_post_meta( $post->ID, 'gender', true ), 'male' ); ?> /> Male</label>
                        <label><input type="radio" id="anton_accordion_gender" name="gender" value="female" <?php checked( get_post_meta( $post->ID, 'gender', true ), 'female' ); ?> /> Female</label>
                        <label><input type="radio" id="anton_accordion_gender" name="gender" value="group" <?php checked( get_post_meta( $post->ID, 'gender', true ), 'group' ); ?> /> Group</label>
                    </td>
                </tr>
            	<tr>
                	<td><h3><?php _e( 'Position', ANTON_ACCORDION ); ?></h3></td>
                	<td><input type="text" id="anton_accordion_position" name="position" autocomplete="off" value="<?php echo get_post_meta( $post->ID, 'position', true ); ?>" /></td>
                </tr>
            	<tr>
                	<td><h3><?php _e( 'Counrty', ANTON_ACCORDION ); ?></h3></td>
                	<td><input type="text" id="anton_accordion_counrty" name="counrty" autocomplete="off" value="<?php echo get_post_meta( $post->ID, 'counrty', true ); ?>" /></td>
                </tr>
            </tbody>
        </table>
        <?php
    }
	function save_meta_box( $post_id ) {
		if ( ! isset( $_POST['anton_accordion_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['anton_accordion_meta_box_nonce'], 'anton_accordion_meta_box' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( isset( $_POST['post_type'] ) && $this->post_type == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		update_post_meta( $post_id, 'gender', sanitize_text_field( $_POST['gender'] ) );
		update_post_meta( $post_id, 'position', sanitize_text_field( $_POST['position'] ) );
		update_post_meta( $post_id, 'counrty', sanitize_text_field( $_POST['counrty'] ) );
	}
}

new Anton_Accordions_Post_Type;