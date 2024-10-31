<div class="wrap">		
	<h2><?php _e( 'Manage Carousels', 'pondolplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=pondolplugin_carousel_add_new'); ?>" class="add-new-h2"> <?php _e( 'New Carousel', 'pondolplugin_carousel' ); ?></a> </h2>
	<?php $this->process_actions(); ?>
	<form id="carousel-list-table" method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<table class="wp-list-table widefat fixed">
			<col width="200px" />
			<col width="200px" />
			<col width="300px" />
			<col width="*" />
			<col width="200px" />
			<thead>
				<tr>
					<th scope="col" id="id" class="manage-column column-id">
						ID
					</th>
					<th scope="col" id="name" class="manage-column column-name">
						Name
					</th>
					<th scope="col" id="shortcode" class="manage-column">
						Shortcode
					</th>
					<th scope="col" id="phpcode" class="manage-column column-phpcode">
						PHP code
					</th>
					<th scope="col" id="time" class="manage-column column-time">
						Created
					</th>
				</tr>
			</thead>
			
			<tbody id="the-list">
			
				<?php 
				$list_data	= $this->get_list_data();
				if(is_array($list_data)) foreach($list_data as $key => $val){
				?>
				<tr<?php echo $key%2 == 0?' class="alternate"':'';?>>
					<td class="id column-id">
					<?php echo $val["id"];?>
					<div class="row-actions">
					<span class="delete"><a href="?page=pondolplugin_carousel_show_items&amp;action=delete&amp;itemid=<?php echo $val["id"];?>">Delete</a>
					| </span><span class="clone"><a href="?page=pondolplugin_carousel_show_items&amp;action=clone&amp;itemid=<?php echo $val["id"];?>">Clone</a>
					| </span><span class="view"><a href="?page=pondolplugin_carousel_show_item&amp;itemid=<?php echo $val["id"];?>">View</a>
					| </span><span class="edit"><a href="?page=pondolplugin_carousel_edit_item&amp;itemid=<?php echo $val["id"];?>">Edit</a>
					</span>
					</div>
					</td>
					<td class="name column-name"><?php echo $val["name"];?></td>
					<td class="shortcode column-shortcode">[pondolplugin_carousel id="<?php echo $val["id"];?>"]</td>
					<td class="phpcode column-phpcode">&lt;?php echo do_shortcode('[pondolplugin_carousel id="<?php echo $val["id"];?>"]'); ?&gt;</td>
					<td class="time column-time"><?php echo $val["time"];?></td>
				</tr>
				<?php
				}else{
					echo "<tr><td colspan='5'>No data</td></tr>";
				}
				?>
			</tbody>
		</table>
	</form>
</div>