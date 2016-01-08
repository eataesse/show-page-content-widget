<?php 
/*Plugin Name: Show Page Content Widget
Description: This widget shows the content of a selected page
Version: 0.1
Author: Richard de Jong
Author URI: http://rdejong.nl
License: GPLv2
*/


class Show_Page_Content_Widget extends WP_Widget {
     
    function __construct() {
		parent::__construct(
         
			// base ID of the widget
			'show_page_content_widget',
			 
			// name of the widget
			__('Show Page Content', 'show_page_content_widget' ),
			 
			// widget options
			array (
				'description' => __( 'Load the content of a page into a widget area.', 'show_page_content_widget' )
			)
        );
    }
     
    function form( $instance ) {
		$defaults = array(
			'page_id' => '0'
		);
        
        if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}

		if (isset($instance['page_id'])) {
			$page_id = esc_attr($instance['page_id']);
		}
		
		if (isset($instance['content_after'])) {
			$content_after = esc_attr($instance['content_after']);
		}
        
        //set arguments for list of pages
        $pageIdArgs = array(
			'selected' => $page_id,
			'name' => $this->get_field_name('page_id'),
            'id' => $this->get_field_id( 'page_id'),
            'class' => 'widefat',
            'show_option_none'      => 'Select page',
            'show_option_no_change' => '0',
		);
		
		// markup for form ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'page_id' ); ?>">Page content to display:</label>
			<?php wp_dropdown_pages($pageIdArgs); //display a list of pages in a select box ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'content_after' ); ?>">Extra content after:</label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('content_after'); ?>" name="<?php echo $this->get_field_name('content_after'); ?>"><?php echo $content_after; ?></textarea>
		</p>
		<?php
    }
     
    function update( $new_instance, $old_instance ) { 
		$instance = $old_instance;
		$instance[ 'page_id' ] = strip_tags( $new_instance[ 'page_id' ] );
        $instance[ 'title' ] = strip_tags($new_instance[ 'title' ]);
		$instance[ 'content_after' ] = strip_tags($new_instance[ 'content_after' ], '<p><a>');
		return $instance;
    }
     
    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $page_id = $instance['page_id']; 
		$content_after = $instance['content_after'];
        
		echo $before_widget;
        
        if(!$page_id){
			echo 'No Page ID set.';
			echo $after_widget;
			return;
		}
        
        if ($title) {
			echo $before_title . $title . $after_title;
		}
        
        $page = get_post($page_id, OBJECT, 'display');
        $content = apply_filters('the_content', $page->post_content);
                
        echo $content;
		
		echo $content_after;
        
        echo $after_widget;
			
    }
     
}

function show_register_page_content_widget() {
 
    register_widget( 'Show_Page_Content_Widget' );
 
}
add_action( 'widgets_init', 'show_register_page_content_widget' );


?>
