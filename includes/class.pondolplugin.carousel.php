<?php 

class PondolPlugin_Carousel {


	function __construct() {

		$this->init();
	}
	
	function init() {

	}

	
	//function add_metaboxes() {
		//add_meta_box('overview_upgrade', __('Upgrade to Commercial Version', 'pondolplugin_carousel'), array($this, 'show_upgrade_to_commercial'), 'pondolplugin_carousel_overview', 'upgrade', '');
		//add_meta_box('overview_news', __('PondolPlugin News', 'pondolplugin_carousel'), array($this, 'show_news'), 'pondolplugin_carousel_overview', 'news', '');
		//add_meta_box('overview_contact', __('Contact Us', 'pondolplugin_carousel'), array($this, 'show_contact'), 'pondolplugin_carousel_overview', 'contact', '');
	//}
/*
	function show_upgrade_to_commercial() {
		?>
		<ul class="pondolplugin-feature-list">
			<li>Use on commercial websites</li>
			<li>Remove the shop-wiz.com watermark</li>
			<li>Priority techincal support</li>
			<li><a href="http://www.shop-wiz.com/order/?product=carousel" target="_blank">Upgrade to Commercial Version</a></li>
		</ul>
		<?php
	}
*/
	/*
	function show_news() {
		
		include_once( ABSPATH . WPINC . '/feed.php' );
		
		$rss = fetch_feed( 'http://www.shop-wiz.com.com/feed/' );
		
		$maxitems = 0;
		if ( ! is_wp_error( $rss ) )
		{
			$maxitems = $rss->get_item_quantity( 5 );
			$rss_items = $rss->get_items( 0, $maxitems );
		}
		?>
		
		<ul class="pondolplugin-feature-list">
		    <?php if ( $maxitems > 0 ) {
		        foreach ( $rss_items as $item )
		        {
		        	?>
		        	<li>
		                <a href="<?php echo esc_url( $item->get_permalink() ); ?>" target="_blank" 
		                    title="<?php printf( __( 'Posted %s', 'pondolplugin_carousel' ), $item->get_date('j F Y | g:i a') ); ?>">
		                    <?php echo esc_html( $item->get_title() ); ?>
		                </a>
		                <p><?php echo $item->get_description(); ?></p>
		            </li>
		        	<?php 
		        }
		    } ?>
		</ul>
		<?php
	}
	*/

	/*
	function show_contact() {
		?>
		<p>Priority technical support is available for Commercial Version users at support@pondolplugin.com. Please include your license information, WordPress version, link to your carousel, all related error messages in your email.</p> 
		<?php
	}
	*/
	
	/**
	 * printing admin overview
	 * admin.php?page=pondolplugin_carousel_overview
	 */
	function show_overview() {
		//ob_start();
		@include_once PONDOLPLUGIN_CAROUSEL_PATH . 'pages/admin_overview.php';
		//return ob_get_clean();
	}
	
	/**
	 * print carousel List
	 */
	function show_items() {
		//ob_start();
		@include_once PONDOLPLUGIN_CAROUSEL_PATH . 'pages/admin_items_list.php';
		//return ob_get_clean();
	}
	
	function show_item()
	{
		if ( !isset( $_REQUEST['itemid'] ) )
			return;
		
		?>
		<div class="wrap">
		<div id="icon-pondolplugin-carousel" class="icon32"><br /></div>
		<div id="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo PONDOLPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
					
		<h2><?php _e( 'View Carousel', 'pondolplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=pondolplugin_carousel_edit_item') . '&itemid=' . $_REQUEST['itemid']; ?>" class="add-new-h2"> <?php _e( 'Edit Carousel', 'pondolplugin_carousel' ); ?>  </a> </h2>

		<?php
		//echo $_REQUEST['itemid'];
		echo $this->generate_body_code( $_REQUEST['itemid'], true ); 
		?>	 
		
		</div>
		<?php
	}
	
	function process_actions()
	{
		
		if ( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete') && isset( $_REQUEST['itemid'] ) )
		{
			$deleted = 0;
			
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					$rtn = $this->delete_item($id);
					if ($rtn > 0)
						$deleted += $rtn;
				}
			}
			else
			{
				$deleted = $this->delete_item( $_REQUEST['itemid'] );
			}
			
			if ($deleted > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d carousel deleted.', '%d carousels deleted.', $deleted), $deleted );
				echo '</p></div>';
			}
		}
		
		if ( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'clone') && isset( $_REQUEST['itemid'] ) )
		{
			$cloned_id = $this->clone_item( $_REQUEST['itemid'] );
			if ($cloned_id > 0)
			{
				echo '<div class="updated"><p>';
				printf( 'New carousel created with ID: %d', $cloned_id );
				echo '</p></div>';
			}
			else
			{
				echo '<div class="error"><p>';
				printf( 'The carousel cannot be cloned.' );
				echo '</p></div>';
			}
		}
	}

	function add_new() {
		//wp_enqueue_script('thickbox');
		//wp_enqueue_style('thickbox');
		//wp_enqueue_script('media-upload');
		wp_enqueue_media();
		?>
		<div class="wrap">

		<h2><?php _e( 'New Carousel', 'pondolplugin_carousel' ); ?> 
			<a href="<?php echo admin_url('admin.php?page=pondolplugin_carousel_show_items'); ?>" class="add-new-h2"> <?php _e( 'Manage Carousels', 'pondolplugin_carousel' ); ?></a>
		</h2>
		<?php 
		$id		= -1;
		$config	= $this->get_item_data();
		
		@include_once PONDOLPLUGIN_CAROUSEL_PATH . 'pages/admin_create.php';
	}
	
	
	/**
	 * edit current carosel itmes(images, attr. etc)
	 */
	function edit_item()
	{
		if ( !isset( $_REQUEST['itemid'] ) )
			return;
		//wp_enqueue_media();
		//wp_enqueue_script('thickbox');
		//wp_enqueue_style('thickbox');
		//wp_enqueue_script('media-upload');
		if(function_exists( 'wp_enqueue_media' )){
    wp_enqueue_media();
}else{
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}
		?>
		<div class="wrap">

		<h2>
			<?php _e( 'Edit Carousel', 'pondolplugin_carousel' ); ?> 
			<a href="<?php echo admin_url('admin.php?page=pondolplugin_carousel_show_item') . '&itemid=' . $_REQUEST['itemid']; ?>" class="add-new-h2"> <?php _e( 'View Carousel', 'pondolplugin_carousel' ); ?>  </a>
			<a href="<?php echo admin_url('admin.php?page=pondolplugin_carousel_show_items'); ?>" class="add-new-h2"> <?php _e( 'Manage Carousels', 'pondolplugin_carousel' ); ?></a>
		</h2>
		
		<?php 
		$id		= $_REQUEST['itemid'];
		$config	= $this->get_item_data( $_REQUEST['itemid'] );
		@include_once PONDOLPLUGIN_CAROUSEL_PATH . 'pages/admin_create.php';
	}
	
	
	
	function get_upload_path() {
		
		$uploads = wp_upload_dir();
		return $uploads['basedir'] . '/pondolplugin-carousel/';
	}
	
	function get_upload_url() {
	
		$uploads = wp_upload_dir();
		return $uploads['baseurl'] . '/pondolplugin-carousel/';
	}
	
	function generate_body_code($id, $has_wrapper) {
		
		global $wpdb;
		$table_name = $wpdb->prefix . "pondolplugin_carousel";
		
		$rtn = "";
		$item_row = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id = ".$id);
		if ($item_row != null)
		{
			$data = str_replace('\\\"', '"', $item_row->data);
			$data = str_replace("\\\'", "'", $data);
			$data = json_decode($data);
			ob_start();
			@include_once PONDOLPLUGIN_CAROUSEL_PATH . 'modules/'.$data->skin.'/carousel.php';
			$rtn  = ob_get_contents();
			ob_end_clean();
		}
		else//if ($item_row != null)
		{
			$rtn = '<p>The specified carousel id does not exist.</p>';
		}
		return $rtn;
	}
	
	function delete_item($id) {
		
		global $wpdb;
	
		$rtn = $wpdb->query( $wpdb->prepare(
				"
				DELETE FROM ".$this->get_table_name()." WHERE id=%s
				",
				$id
		) );
		
		return $rtn;
	}
	
	function clone_item($id) {
	
		global $wpdb, $user_ID;
	
		$cloned_id = -1;
		
		$item_row = $wpdb->get_row("SELECT * FROM ".$this->get_table_name()." WHERE id = $id");
		if ($item_row != null)
		{
			$time = current_time('mysql');
			$authorid = $user_ID;
			
			$rtn = $wpdb->query( $wpdb->prepare(
					"
					INSERT INTO ".$this->get_table_name()." (name, data, time, authorid)
					VALUES (%s, %s, %s, %s)
					",
					$item_row->name,
					$item_row->data,
					$time,
					$authorid
			) );
				
			if ($rtn)
				$cloned_id = $wpdb->insert_id;
		}
	
		return $cloned_id;
	}
	
	function is_db_table_exists() {

		global $wpdb;
	
		return ( $wpdb->get_var("SHOW TABLES LIKE '".$this->get_table_name()."'") == $this->get_table_name() );
	}
	
	function is_id_exist($id)
	{
		global $wpdb;
	
		$carousel_row = $wpdb->get_row("SELECT * FROM ".$this->get_table_name()." WHERE id = ".$id);
		return ($carousel_row != null);
	}
	
	function create_db_table() {
	
		global $wpdb;
		
		$charset = '';
		if ( !empty($wpdb -> charset) )
			$charset = "DEFAULT CHARACTER SET ".$wpdb->charset;
		if ( !empty($wpdb -> collate) )
			$charset .= " COLLATE ".$wpdb->collate;
	
		$sql = "CREATE TABLE ".$this->get_table_name()." (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext DEFAULT '' NOT NULL,
		data text DEFAULT '' NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		authorid tinytext NOT NULL,
		PRIMARY KEY  (id)
		) ".$charset.";";
			
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	function save_item($item) {
		
		if ( !$this->is_db_table_exists() )
			$this->create_db_table();
//echo "adfadf";
		global $wpdb, $user_ID;
		$table_name = $wpdb->prefix . "pondolplugin_carousel";
		
		$id		= $item["id"];
		$name	= $item["name"];
		
		unset($item["id"]);
		$data = json_encode($item);
		
		$time = current_time('mysql');
		$authorid = $user_ID;
		
		if ( ($id > 0) && $this->is_id_exist($id) )
		{
			$rtn = $wpdb->query( $wpdb->prepare(
					"
					UPDATE ".$this->get_table_name()."
					SET name=%s, data=%s, time=%s, authorid=%s
					WHERE id=%d
					",
					$name,
					$data,
					$time,
					$authorid,
					$id
			) );
			
			if (!$rtn)
			{
				return array(
						"success" => false,
						"id" => $id, 
						"message" => "Cannot update the carousel in database"
					);
			}
		}
		else
		{
			$rtn = $wpdb->query( $wpdb->prepare(
					"
					INSERT INTO ".$this->get_table_name()." (name, data, time, authorid)
					VALUES (%s, %s, %s, %s)
					",
					$name,
					$data,
					$time,
					$authorid
			) );
			
			if (!$rtn)
			{
				return array(
						"success" => false,
						"id" => -1,
						"message" => "Cannot insert the carousel to database"
				);
			}
			
			$id = $wpdb->insert_id;
		}
		
		return array(
				"success" => true,
				"id" => intval($id),
				"message" => "Carousel published!"
		);
	}
	
	function get_list_data() {
		
		global $wpdb;
		
		$rows = $wpdb->get_results( "SELECT * FROM ".$this->get_table_name(), ARRAY_A);
		
		$rtn = array();
		
		if ( $rows )
		{
			foreach ( $rows as $row )
			{
				$rtn[] = array(
							"id" => $row['id'],
							'name' => $row['name'],
							'data' => $row['data'],
							'time' => $row['time'],
							'author' => $row['authorid']
						);
			}
		}
	
		return $rtn;
	}
	
	function get_item_data($id=null)
	{
		global $wpdb;
	
		$rtn = "";
		
		if($id){
			$item_row = $wpdb->get_row("SELECT * FROM ".$this->get_table_name()." WHERE id = ".$id);
			if ($item_row != null)
			{
				$rtn = $item_row->data;
			}
		}else{//set init. data
			
			$init["name"]			= "My Carousel";
			$init["slides"]			= array();
			$init["skin"]			= "default";
			$init["width"]			= "300";
			$init["height"]			= "300";
			$init["autoplay"]		= "false";
			$init["random"]			= "false";
			$init["speed"]			= "300";
			$init["direction"]		= "left";
			$init["visibleitems"]	= "1";
			
			$rtn = json_encode($init);			
		}

		return $rtn;
	}
	

	function get_table_name(){
		global $wpdb;
		return $wpdb->prefix . "pondolplugin_carousel";
	}
	
	
}