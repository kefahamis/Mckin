<?php
	/**
	 * @package     Freemius
	 * @copyright   Copyright (c) 2015, Freemius, Inc.
	 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
	 * @since       1.0.3
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! function_exists( 'fs_dummy' ) ) {
		function fs_dummy() {
		}
	}

	/* Url.
	--------------------------------------------------------------------------------------------*/
	if ( ! function_exists( 'fs_get_url_daily_cache_killer' ) ) {
		function fs_get_url_daily_cache_killer() {
			return date( '\YY\Mm\Dd' );
		}
	}

	/* Templates / Views.
	--------------------------------------------------------------------------------------------*/
	if ( ! function_exists( 'fs_get_template_path' ) ) {
		function fs_get_template_path( $path ) {
			return WP_FS__DIR_TEMPLATES . '/' . trim( $path, '/' );
		}

		function fs_include_template( $path, &$params = null ) {
			$VARS = &$params;
			include fs_get_template_path( $path );
		}

		function fs_include_once_template( $path, &$params = null ) {
			$VARS = &$params;
			include_once fs_get_template_path( $path );
		}

		function fs_require_template( $path, &$params = null ) {
			$VARS = &$params;
			require fs_get_template_path( $path );
		}

		function fs_require_once_template( $path, &$params = null ) {
			$VARS = &$params;
			require_once fs_get_template_path( $path );
		}

		function fs_get_template( $path, &$params = null ) {
			ob_start();

			$VARS = &$params;
			require fs_get_template_path( $path );

			return ob_get_clean();
		}
	}

	/* Scripts and styles including.
	--------------------------------------------------------------------------------------------*/

	/**
	 * Generates an absolute URL to the given path. This function ensures that the URL will be correct whether the asset
	 * is inside a plugin's folder or a theme's folder.
	 *
	 * Examples:
	 * 1. "themes" folder
	 *    Path: C:/xampp/htdocs/fswp/wp-content/themes/twentytwelve/freemius/assets/css/admin/common.css
	 *    URL: http://fswp:8080/wp-content/themes/twentytwelve/freemius/assets/css/admin/common.css
	 *
	 * 2. "plugins" folder
	 *    Path: C:/xampp/htdocs/fswp/wp-content/plugins/rating-widget-premium/freemius/assets/css/admin/common.css
	 *    URL: http://fswp:8080/wp-content/plugins/rating-widget-premium/freemius/assets/css/admin/common.css
	 *
	 * @author Leo Fajardo (@leorw)
	 * @since  1.2.2
	 *
	 * @param  string $asset_abs_path Asset's absolute path.
	 *
	 * @return string Asset's URL.
	 */
	function fs_asset_url( $asset_abs_path ) {
		$wp_content_dir = fs_normalize_path( WP_CONTENT_DIR );
		$asset_abs_path = fs_normalize_path( $asset_abs_path );
		$asset_rel_path = str_replace( $wp_content_dir, '', $asset_abs_path );

		$asset_url = content_url( fs_normalize_path( $asset_rel_path ) );

		return $asset_url;
	}

	function fs_enqueue_local_style( $handle, $path, $deps = array(), $ver = false, $media = 'all' ) {
		wp_enqueue_style( $handle, fs_asset_url( WP_FS__DIR_CSS . '/' . trim( $path, '/' ) ), $deps, $ver, $media );
	}

	function fs_enqueue_local_script( $handle, $path, $deps = array(), $ver = false, $in_footer = 'all' ) {
		wp_enqueue_script( $handle, fs_asset_url( WP_FS__DIR_JS . '/' . trim( $path, '/' ) ), $deps, $ver, $in_footer );
	}

	function fs_img_url( $path, $img_dir = WP_FS__DIR_IMG ) {
		return ( fs_asset_url( $img_dir . '/' . trim( $path, '/' ) ) );
	}

	/* Request handlers.
	--------------------------------------------------------------------------------------------*/
	/**
	 * @param string      $key
	 * @param mixed       $def
	 * @param string|bool $type Since 1.2.1.7 - when set to 'get' will look for the value passed via querystring, when
	 *                          set to 'post' will look for the value passed via the POST request's body, otherwise,
	 *                          will check if the parameter was passed in any of the two.
	 *
	 * @return mixed
	 */
	function fs_request_get( $key, $def = false, $type = false ) {
		if ( is_string( $type ) ) {
			$type = strtolower( $type );
		}

		switch ( $type ) {
			case 'post':
				$value = isset( $_POST[ $key ] ) ? $_POST[ $key ] : $def;
				break;
			case 'get':
				$value = isset( $_GET[ $key ] ) ? $_GET[ $key ] : $def;
				break;
			default:
				$value = isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $def;
				break;
		}

		return $value;
	}

	function fs_request_has( $key ) {
		return isset( $_REQUEST[ $key ] );
	}

	function fs_request_get_bool( $key, $def = false ) {
		if ( ! isset( $_REQUEST[ $key ] ) ) {
			return $def;
		}

		if ( 1 == $_REQUEST[ $key ] || 'true' === strtolower( $_REQUEST[ $key ] ) ) {
			return true;
		}

		if ( 0 == $_REQUEST[ $key ] || 'false' === strtolower( $_REQUEST[ $key ] ) ) {
			return false;
		}

		return $def;
	}

	function fs_request_is_post() {
		return ( 'post' === strtolower( $_SERVER['REQUEST_METHOD'] ) );
	}

	function fs_request_is_get() {
		return ( 'get' === strtolower( $_SERVER['REQUEST_METHOD'] ) );
	}

	function fs_get_action( $action_key = 'action' ) {
		if ( ! empty( $_REQUEST[ $action_key ] ) ) {
			return strtolower( $_REQUEST[ $action_key ] );
		}

		if ( 'action' == $action_key ) {
			$action_key = 'fs_action';

			if ( ! empty( $_REQUEST[ $action_key ] ) ) {
				return strtolower( $_REQUEST[ $action_key ] );
			}
		}

		return false;
	}

	function fs_request_is_action( $action, $action_key = 'action' ) {
		return ( strtolower( $action ) === fs_get_action( $action_key ) );
	}

	/**
	 * @author Vova Feldman (@svovaf)
	 * @since  1.0.0
	 *
	 * @since  1.2.1.5 Allow nonce verification.
	 *
	 * @param string $action
	 * @param string $action_key
	 * @param string $nonce_key
	 *
	 * @return bool
	 */
	function fs_request_is_action_secure(
		$action,
		$action_key = 'action',
		$nonce_key = 'nonce'
	) {
		if ( strtolower( $action ) !== fs_get_action( $action_key ) ) {
			return false;
		}

		$nonce = ! empty( $_REQUEST[ $nonce_key ] ) ?
			$_REQUEST[ $nonce_key ] :
			'';

		if ( empty( $nonce ) ||
		     ( false === wp_verify_nonce( $nonce, $action ) )
		) {
			return false;
		}

		return true;
	}

	function fs_is_plugin_page( $page_slug ) {
		return ( is_admin() && $page_slug === fs_request_get( 'page' ) );
	}

	/* Core UI.
	--------------------------------------------------------------------------------------------*/
	/**
	 * @param number      $module_id
	 * @param string      $page
	 * @param string      $action
	 * @param string      $title
	 * @param array       $params
	 * @param bool        $is_primary
	 * @param string|bool $icon_class   Optional class for an icon (since 1.1.7).
	 * @param string|bool $confirmation Optional confirmation message before submit (since 1.1.7).
	 * @param string      $method       Since 1.1.7
	 *
	 * @uses fs_ui_get_action_button()
	 */
	function fs_ui_action_button(
		$module_id,
		$page,
		$action,
		$title,
		$params = array(),
		$is_primary = true,
		$icon_class = false,
		$confirmation = false,
		$method = 'GET'
	) {
		echo fs_ui_get_action_button(
			$module_id,
			$page,
			$action,
			$title,
			$params,
			$is_primary,
			$icon_class,
			$confirmation,
			$method
		);
	}

	/**
	 * @author Vova Feldman (@svovaf)
	 * @since  1.1.7
	 *
	 * @param number      $module_id
	 * @param string      $page
	 * @param string      $action
	 * @param string      $title
	 * @param array       $params
	 * @param bool        $is_primary
	 * @param string|bool $icon_class   Optional class for an icon.
	 * @param string|bool $confirmation Optional confirmation message before submit.
	 * @param string      $method
	 *
	 * @return string
	 */
	function fs_ui_get_action_button(
		$module_id,
		$page,
		$action,
		$title,
		$params = array(),
		$is_primary = true,
		$icon_class = false,
		$confirmation = false,
		$method = 'GET'
	) {
		// Prepend icon (if set).
		$title = ( is_string( $icon_class ) ? '<i class="' . $icon_class . '"></i> ' : '' ) . $title;

		if ( is_string( $confirmation ) ) {
			return sprintf( '<form action="%s" method="%s"><input type="hidden" name="fs_action" value="%s">%s<a href="#" class="%s" onclick="if (confirm(\'%s\')) this.parentNode.submit(); return false;">%s</a></form>',
				freemius( $module_id )->_get_admin_page_url( $page, $params ),
				$method,
				$action,
				wp_nonce_field( $action, '_wpnonce', true, false ),
				'button' . ( $is_primary ? ' button-primary' : '' ),
				$confirmation,
				$title
			);
		} else if ( 'GET' !== strtoupper( $method ) ) {
			return sprintf( '<form action="%s" method="%s"><input type="hidden" name="fs_action" value="%s">%s<a href="#" class="%s" onclick="this.parentNode.submit(); return false;">%s</a></form>',
				freemius( $module_id )->_get_admin_page_url( $page, $params ),
				$method,
				$action,
				wp_nonce_field( $action, '_wpnonce', true, false ),
				'button' . ( $is_primary ? ' button-primary' : '' ),
				$title
			);
		} else {
			return sprintf( '<a href="%s" class="%s">%s</a></form>',
				wp_nonce_url( freemius( $module_id )->_get_admin_page_url( $page, array_merge( $params, array( 'fs_action' => $action ) ) ), $action ),
				'button' . ( $is_primary ? ' button-primary' : '' ),
				$title
			);
		}
	}

	function fs_ui_action_link( $module_id, $page, $action, $title, $params = array() ) {
		?><a class=""
		     href="<?php echo wp_nonce_url( freemius( $module_id )->_get_admin_page_url( $page, array_merge( $params, array( 'fs_action' => $action ) ) ), $action ) ?>"><?php echo $title ?></a><?php
	}

	/*function fs_error_handler($errno, $errstr, $errfile, $errline)
	{
		if (false === strpos($errfile, 'freemius/'))
		{
			// @todo Dump Freemius errors to local log.
		}

//		switch ($errno) {
//			case E_USER_ERROR:
//				break;
//			case E_WARNING:
//			case E_USER_WARNING:
//				break;
//			case E_NOTICE:
//			case E_USER_NOTICE:
//				break;
//			default:
//				break;
//		}
	}

	set_error_handler('fs_error_handler');*/

	if ( ! function_exists( 'fs_nonce_url' ) ) {
		/**
		 * Retrieve URL with nonce added to URL query.
		 *
		 * Originally was using `wp_nonce_url()` but the new version
		 * changed the return value to escaped URL, that's not the expected
		 * behaviour.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  ~1.1.3
		 *
		 * @param string     $actionurl URL to add nonce action.
		 * @param int|string $action    Optional. Nonce action name. Default -1.
		 * @param string     $name      Optional. Nonce name. Default '_wpnonce'.
		 *
		 * @return string Escaped URL with nonce action added.
		 */
		function fs_nonce_url( $actionurl, $action = - 1, $name = '_wpnonce' ) {
			return add_query_arg( $name, wp_create_nonce( $action ), $actionurl );
		}
	}

	if ( ! function_exists( 'fs_starts_with' ) ) {
		/**
		 * Check if string starts with.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.3
		 *
		 * @param string $haystack
		 * @param string $needle
		 *
		 * @return bool
		 */
		function fs_starts_with( $haystack, $needle ) {
			$length = strlen( $needle );

			return ( substr( $haystack, 0, $length ) === $needle );
		}
	}

	#region Url Canonization ------------------------------------------------------------------

	if ( ! function_exists( 'fs_canonize_url' ) ) {
		/**
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.3
		 *
		 * @param string $url
		 * @param bool   $omit_host
		 * @param array  $ignore_params
		 *
		 * @return string
		 */
		function fs_canonize_url( $url, $omit_host = false, $ignore_params = array() ) {
			$parsed_url = parse_url( strtolower( $url ) );

//		if ( ! isset( $parsed_url['host'] ) ) {
//			return $url;
//		}

			$canonical = ( ( $omit_host || ! isset( $parsed_url['host'] ) ) ? '' : $parsed_url['host'] ) . $parsed_url['path'];

			if ( isset( $parsed_url['query'] ) ) {
				parse_str( $parsed_url['query'], $queryString );
				$canonical .= '?' . fs_canonize_query_string( $queryString, $ignore_params );
			}

			return $canonical;
		}
	}

	if ( ! function_exists( 'fs_canonize_query_string' ) ) {
		/**
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.3
		 *
		 * @param array $params
		 * @param array $ignore_params
		 * @param bool  $params_prefix
		 *
		 * @return string
		 */
		function fs_canonize_query_string( array $params, array &$ignore_params, $params_prefix = false ) {
			if ( ! is_array( $params ) || 0 === count( $params ) ) {
				return '';
			}

			// Url encode both keys and values
			$keys   = fs_urlencode_rfc3986( array_keys( $params ) );
			$values = fs_urlencode_rfc3986( array_values( $params ) );
			$params = array_combine( $keys, $values );

			// Parameters are sorted by name, using lexicographical byte value ordering.
			// Ref: Spec: 9.1.1 (1)
			uksort( $params, 'strcmp' );

			$pairs = array();
			foreach ( $params as $parameter => $value ) {
				$lower_param = strtolower( $parameter );

				// Skip ignore params.
				if ( in_array( $lower_param, $ignore_params ) ||
				     ( false !== $params_prefix && fs_starts_with( $lower_param, $params_prefix ) )
				) {
					continue;
				}

				if ( is_array( $value ) ) {
					// If two or more parameters share the same name, they are sorted by their value
					// Ref: Spec: 9.1.1 (1)
					natsort( $value );
					foreach ( $value as $duplicate_value ) {
						$pairs[] = $lower_param . '=' . $duplicate_value;
					}
				} else {
					$pairs[] = $lower_param . '=' . $value;
				}
			}

			if ( 0 === count( $pairs ) ) {
				return '';
			}

			return implode( "&", $pairs );
		}
	}

	if ( ! function_exists( 'fs_urlencode_rfc3986' ) ) {
		/**
		 * @author Vova Feldman (@svovaf)
		 * @since  1.1.3
		 *
		 * @param string|string[] $input
		 *
		 * @return array|mixed|string
		 */
		function fs_urlencode_rfc3986( $input ) {
			if ( is_array( $input ) ) {
				return array_map( 'fs_urlencode_rfc3986', $input );
			} else if ( is_scalar( $input ) ) {
				return str_replace( '+', ' ', str_replace( '%7E', '~', rawurlencode( $input ) ) );
			}

			return '';
		}
	}

	#endregion Url Canonization ------------------------------------------------------------------

	/**
	 * @author Vova Feldman (@svovaf)
	 *
	 * @since  1.2.2 Changed to usage of WP_Filesystem_Direct.
	 *
	 * @param string $from URL
	 * @param string $to   File path.
	 *
	 * @return bool Is successfully downloaded.
	 */
	function fs_download_image( $from, $to ) {
		$dir = dirname( $to );

		if ( 'direct' !== get_filesystem_method( array(), $dir ) ) {
			return false;
		}

		if ( ! class_exists( 'WP_Filesystem_Direct' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		}

		$fs      = new WP_Filesystem_Direct( '' );
		$tmpfile = download_url( $from );

		if ($tmpfile instanceof WP_Error) {
			// Issue downloading the file.
			return false;
		}

		$fs->copy( $tmpfile, $to );
		$fs->delete( $tmpfile );
		
		return true;
	}

	/* General Utilities
	--------------------------------------------------------------------------------------------*/

	/**
	 * Sorts an array by the value of the priority key.
	 *
	 * @author Daniel Iser (@danieliser)
	 * @since  1.1.7
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	function fs_sort_by_priority( $a, $b ) {

		// If b has a priority and a does not, b wins.
		if ( ! isset( $a['priority'] ) && isset( $b['priority'] ) ) {
			return 1;
		} // If b has a priority and a does not, b wins.
		elseif ( isset( $a['priority'] ) && ! isset( $b['priority'] ) ) {
			return - 1;
		} // If neither has a priority or both priorities are equal its a tie.
		elseif ( ( ! isset( $a['priority'] ) && ! isset( $b['priority'] ) ) || $a['priority'] === $b['priority'] ) {
			return 0;
		}

		// If both have priority return the winner.
		return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;
	}

	#--------------------------------------------------------------------------------
	#region Localization
	#--------------------------------------------------------------------------------

	if ( ! function_exists( 'fs_text' ) ) {
		/**
		 * Retrieve a translated text by key.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.2.1.7
		 *
		 * @param string $key
		 * @param string $slug
		 *
		 * @return string
		 *
		 * @global       $fs_text, $fs_text_overrides
		 */
		function fs_text( $key, $slug = 'freemius' ) {
			return __fs( $key, $slug );
		}

		/**
		 * Output a translated text by key.
		 *
		 * @author Vova Feldman (@svovaf)
		 * @since  1.2.1.7
		 *
		 * @param string $key
		 * @param string $slug
		 */
		function fs_echo( $key, $slug = 'freemius' ) {
			echo fs_text( $key, $slug );
		}
	}

	/**
	 * @author Vova Feldman
	 * @since  1.2.1.6
	 *
	 * @param string $key
	 * @param string $slug
	 *
	 * @return string
	 */
	function fs_esc_attr( $key, $slug ) {
		return esc_attr( fs_text( $key, $slug ) );
	}

	/**
	 * @author Vova Feldman
	 * @since  1.2.1.6
	 *
	 * @param string $key
	 * @param string $slug
	 */
	function fs_esc_attr_echo( $key, $slug ) {
		echo esc_attr( fs_text( $key, $slug ) );
	}

	/**
	 * @author Vova Feldman
	 * @since  1.2.1.6
	 *
	 * @param string $key
	 * @param string $slug
	 *
	 * @return string
	 */
	function fs_esc_js( $key, $slug ) {
		return esc_js( fs_text( $key, $slug ) );
	}

	/**
	 * @author Vova Feldman
	 * @since  1.2.1.6
	 *
	 * @param string $key
	 * @param string $slug
	 */
	function fs_esc_js_echo( $key, $slug ) {
		echo esc_js( fs_text( $key, $slug ) );
	}

	/**
	 * @author Vova Feldman
	 * @since  1.2.1.6
	 *
	 * @param string $key
	 * @param string $slug
	 */
	function fs_json_encode_echo( $key, $slug ) {
		echo json_encode( fs_text( $key, $slug ) );
	}

	/**
	 * @author Vova Feldman
	 * @since  1.2.1.6
	 *
	 * @param string $key
	 * @param string $slug
	 *
	 * @return string
	 */
	function fs_esc_html( $key, $slug ) {
		return esc_html( fs_text( $key, $slug ) );
	}

	/**
	 * @author Vova Feldman
	 * @since  1.2.1.6
	 *
	 * @param string $key
	 * @param string $slug
	 */
	function fs_esc_html_echo( $key, $slug ) {
		echo esc_html( fs_text( $key, $slug ) );
	}

#endregion
