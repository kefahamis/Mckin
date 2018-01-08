<?php
	/*	
	*	Goodlayers Widget Utility
	*/

	// include the shortcode support for the text widget
	add_filter('widget_text', 'do_shortcode');
	add_filter('widget_title', 'do_shortcode');

	if( !class_exists('gdlr_core_widget_util') ){
		class gdlr_core_widget_util{

			// get option as html
			static function get_option($options){

				if( !empty($options) ){
					foreach($options as $option_slug => $option){

						echo '<p>';
						if( !empty($option['title']) ){
							echo '<label for="' . esc_attr($option['id']) . '" >' . gdlr_core_escape_content($option['title']) . '</label>';
						}

						switch( $option['type'] ){

							case 'text': 
								echo '<input type="text" class="widefat" id="' . esc_attr($option['id']) . '" name="' . esc_attr($option['name']) . '" ';
								echo 'value="' . (isset($option['value'])? esc_attr($option['value']): '') . '" />';
								break;

							case 'combobox':
								echo '<select class="widefat" id="' . esc_attr($option['id']) . '" name="' . esc_attr($option['name']) . '" >';
								foreach( $option['options'] as $key => $value ){
									echo '<option value="' . esc_attr($key) . '" ' . ((isset($option['value']) && $key == $option['value'])? 'selected': '') . ' >' . esc_html($value) . '</option>';
								}
								echo '</select>';
								break; 

							default: break; 

						} // switch
						echo '</p>';

					} // $option['type']
				} // $options

			}

			// option update
			static function get_option_update($instances){

				if( !empty($instances) ){
					foreach($instances as $key => $value){
						$instances[$key] = isset($value)? strip_tags($value): '';
					}
				}

				return $instances;
			}

		} // class
	} // class_exists