<?php
/*
Plugin Name: Replicopost
Plugin URI: http://localtest/multitest/replicopost
Description: Bobdog.  That is all...
Author: Michael Mulligan
Version: 0.1
Author URI: http://www.belineperspectives.com/
*/

class Replicoter {

	private $wpdb;
	private $wpurl;
	
	/* Take care of a few little things, set up our init for the correct time.  Nothing Major here.
	 */
	public function __construct() {
	
		global $wpdb;
		$this->wpdb = &$wpdb;
   		$this->wpurl = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__), '', plugin_basename(__FILE__));
     	
     	add_action('init', array(&$this, 'replicopost_init'),99,2);
	}
	
	/* Create the Replico type.  I really started beating this word
	 * up over the course of this little plugin...
	 * 
	 * Also, register our hooks.
	 */
	public function replicopost_init() {
		
		/////
		// HOOKS
		/////
		add_filter('the_content', array(&$this, 'replicoMAULER'),99,2);
   		
		if(is_admin()){
			add_action('add_meta_boxes', array(&$this, 'replicoter_meta'),99,2);
			add_action('save_post', array(&$this, 'save_replicopost'),99,2);
			add_action('admin_head',array(&$this, 'replico_js'), 99, 2);
		}
		
		/////
		// CUSTOM TYPE
		/////
		register_post_type('replicopost', array(
			'labels' => array(
				'name' => 'Replico',
				'singular_name' => 'Replico',
				'add_new' => 'Birth a Replico',
				'add_new_item' => 'Replico',
				'edit_item' =>  'Mutilate Replico',
				'new_item' => 'Spawn Replicos',
				'view_item' => 'Repliviewer',
				'search_items' => 'Find Replicoldo',
				'not_found' => 'There Are No Replicos Here',
				'not_found_in_trash' => 'You Need to Delete More Replicos (All Of Them, yes)'
			),
			'singular_label' => __('REPLICO'),
			'public' => true,
			'show_ui' => true, // UI in admin panel
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array("slug" => "replico"), // Permalinks format
			'supports' => array('title','editor','thumbnail')
		));
	}
	
	public function replicoter_meta() {
		add_meta_box("replicoter_meta", "Replicoter", array(&$this, 'add_replicoter'), "replicopost", "side", "low");
	}
	
	public function add_replicoter( $post) {

		// Use nonce for verification
		wp_nonce_field( $this->wpurl, 'replicopost_noncename' );
		
		// Load post meta
		$meta = get_post_meta( $post->ID );
		$needle = (isset($meta['needle'] )) ? $meta['needle'][0] : '' ;
		$replac = (isset($meta['replac'] )) ? $meta['replac'][0] : '' ;
		
		// Save typing
		$checked = 'checked="checked"';
		
		// Are we even active?  If not, and we have data, that will fix it's self shortly...
		if (!isset($meta['active'] )) 
			$checked = '';
		
		echo '<label id="replibut" for="active">Activate the REPLICOTER?</label><input id="replibox" type="checkbox" name="active" '.$checked.' /><br />';
		echo '<span id="replicopts" ><label for="needle">Replace: </label><input type="text" name="needle" value="'.$needle.'" /></br>
			  <label for="replac">With: </label><input type="text" name="replac" value="'.$replac.'" /></span>';
	}
	
	
	public function save_replicopost( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		if ( !wp_verify_nonce( $_POST['replicopost_noncename'], $this->wpurl ) ) return;

		// Check permissions
		if ( 'page' == $_POST['post_type'] ) if ( !current_user_can( 'edit_page', $post_id ) ) return;
		else if ( !current_user_can( 'edit_post', $post_id ) ) return;

		// OK, we're authenticated: we need to find and save our data
		if (isset($_POST['active'])){
			update_post_meta($post_id,'active', $_POST['active']);
			update_post_meta($post_id,'needle', $_POST['needle']);
			update_post_meta($post_id,'replac', $_POST['replac']);
		} else {
			delete_post_meta($post_id,'active');
			delete_post_meta($post_id,'needle');
			delete_post_meta($post_id,'replac');
		}
	}
	
	/* Here is where we beat the pants out of the post content.
	 * We're only interested in out post-type, and only if the
	 * option is activated.
	 *
	 * You'll also note I didn't care about compounds.  Meh...
	 */
	
	public function replicoMAULER($content) {
		
		$meta = get_post_meta($GLOBALS['post']->ID);

		if ($GLOBALS['post']->post_type !== 'replicopost' || !isset($meta['active']))
			return $content;
		
		$content = str_replace($meta['needle'][0],$meta['replac'][0],$content );
		return $content;
	
	}
	
	/* Javascript and CSS for Interactivity and Pizaz...
	 * If JS is enabled, we stylize the lable into a button and make it interactive.
	 * Showing and hiding the options for effect.  It gets the fields out of the way.
	*/
	public function replico_js() {  ?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		$('#replicoter_meta').addClass('js').find('#replibut').text('Replicoter Is ');
		
		if ($('#replibox').attr('checked') == 'checked')
			$('#replicoter_meta').addClass('active');
		
		$('#replibut').click(function(){
			if ($('#replibox').attr('checked') != 'checked') {
				$('#replibox').attr('checked','checked');
				$('#replicoter_meta').addClass('active');
			} else {
				$('#replibox').removeAttr('checked');
				$('#replicoter_meta').removeClass('active');
			}
		});
		
	});
	</script>
	<style type="text/css">
		#replicoter_meta.js #replicopts, #replicoter_meta.js #replibox {
			display: none;
		}
		
		#replicoter_meta.js.active #replicopts {
			display: block;
		}
		
		#replicoter_meta.js.active #replibut:after {
			content: "Active";
		}
		#replicoter_meta.js #replibut:after {
			content: "Inactive";
		}
		
		#replicoter_meta.js.active #replibut {
			background: green;
		}
		
		#replicoter_meta.js #replibut {
			display: block;
			background: white;
			border: 1px gray solid;
			cursor: finger;
			padding: 3px;
			margin-bottom: 10px;
			margin: auto;
			content: 'Replicoter Inactive';
			background-color: red;
			color: white;
			
			/* Cross-Browser Corners */
			-moz-border-radius: 5px;
			border-radius: 5px;
			
			/* Cross-Browser Shadows */
			-moz-box-shadow: 2px 2px 3px #000;
			-webkit-box-shadow: 2px 2px 3px #000;
			box-shadow: 2px 2px 3px #000;
			-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#000000')";
			filter: progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#000000');
		}
	</style>
	<?
	
	}
	

}
$Replicoter = new Replicoter();

?>