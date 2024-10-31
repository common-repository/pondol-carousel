<?php
$cfg	= json_decode($config);
?>
<script>
	var itemid	= "<?php echo $_GET["itemid"];?>";
	var config	= <?php echo $config; ?>;
	var PONDOLPLUGIN_CAROUSEL_URL = "<?php echo PONDOLPLUGIN_CAROUSEL_URL;?>";
	
	jQuery( document ).ready(function( $ ) {
		updateMediaTable();//display current carosel items
	});
</script>

<h3><?php _e( 'General Options', 'pondolplugin_carousel' ); ?></h3>
				
		<div style="margin:0 12px;">
		<table class="pondolplugin-form-table">
			<tr>
				<th><?php _e( 'Name', 'pondolplugin_carousel' ); ?></th>
				<td><input name="pondolplugin-carousel-name" type="text" id="pondolplugin-carousel-name" value="<?php echo $cfg->name; ?>" class="regular-text" /></td>
			</tr>
		</table>
		</div>
		
		<h3><?php _e( 'Designing', 'pondolplugin_carousel' ); ?></h3>
		
		<div style="margin:0 12px;">
		<input class="button button-primary btn_save_pondolplugin_carousel" type="button" value="<?php _e( 'Save & Publish', 'pondolplugin_carousel' ); ?>"></input>
		<ul class="pondolplugin-tab-buttons" id="pondolplugin-carousel-toolbar">
			<li class="pondolplugin-tab-button step1 pondolplugin-tab-buttons-selected"><?php _e( 'Images', 'pondolplugin_carousel' ); ?></li>
			<li class="pondolplugin-tab-button step2"><?php _e( 'Skins', 'pondolplugin_carousel' ); ?></li>
			<li class="pondolplugin-tab-button step3"><?php _e( 'Options', 'pondolplugin_carousel' ); ?></li>
		</ul>
		
		
		<ul class="pondolplugin-tabs" id="pondolplugin-carousel-tabs">
			<li class="pondolplugin-tab pondolplugin-tab-selected">	
			
				<div class="pondolplugin-toolbar">	
					<input type="button" class="button" id="pondolplugin-add-image" value="<?php _e( 'Add Image', 'pondolplugin_carousel' ); ?>" />
				</div>
        		
        		<table class="pondolplugin-table" id="pondolplugin-carousel-media-table">
			        <thead>
			          	<tr>
			            	<th>#</th>
			            	<th><?php _e( 'Media', 'pondolplugin_carousel' ); ?></th>
			            	<th><?php _e( 'Actions', 'pondolplugin_carousel' ); ?></th>
			          	</tr>
			        </thead>
			        <tbody>
			        	<!-- updateMediaTable 에서 처리 -->
			        </tbody>
			      </table>
      
			</li>
			
			<li class="pondolplugin-tab">
				<form>
					<fieldset>
						
						<?php 
						
						$dirList = PONDOLPLUGIN_CAROUSEL_PATH."modules/";
						$open_dir = opendir($dirList);
						$skins = array();
						while($opendir = readdir($open_dir)) {
							if(($opendir != ".") && ($opendir != "..") ) {
								array_push($skins, array("dir"=>$dirList.$opendir, "name"=>$opendir));
							}
						}
						closedir($open_dir);

						foreach ($skins as $key => $val) {
							$selected	= $cfg->skin == $val["name"] ? " checked":"";
						?>
							<div class="pondolplugin-tab-skin">
							<label>
								<input type="radio" name="pondolplugin-carousel-skin" value="<?php echo $val["name"]; ?>"<?php echo $selected;?>> <?php echo $val["name"]; ?> 
								<br />
								<img class="selected" style="width:300px;" src="<?php echo PONDOLPLUGIN_CAROUSEL_URL; ?>modules/<?php echo $val["name"]; ?>/thumb.png" />
								</label>
							</div>
						<?php
						}
						?>
						
					</fieldset>
				</form>
			</li>
			<li class="pondolplugin-tab">
				<div class="pondolplugin-carousel-options-tabs" id="pondolplugin-carousel-options-tabs">
					<table class="pondolplugin-form-table-noborder">
						<tr>
							<th>Width / Height</th>
							<td><label>
									<input name="pondolplugin-carousel-width" type="text" id="pondolplugin-carousel-width" value="<?php echo $cfg->width; ?>" class="small-text" /> 
									/ 
									<input name="pondolplugin-carousel-height" type="text" id="pondolplugin-carousel-height" value="<?php echo $cfg->height; ?>" class="small-text" />
								</label>
							</td>
						</tr>
						
						<tr>
							<th>Play mode</th>
							<td>
								<label><input name='pondolplugin-carousel-autoplay' type='checkbox' id='pondolplugin-carousel-autoplay'<?php echo $cfg->autoplay=="true"?" checked":""; ?> /> Auto play</label>
									<br />
								<label><input name='pondolplugin-carousel-random' type='checkbox' id='pondolplugin-carousel-random'<?php echo $cfg->random=="true"?" checked":""; ?> /> Random</label>
							</td>
						</tr>
						<tr>
							<th>Visible items</th>
							<td><label><input name='pondolplugin-carousel-visibleitems' type='text' size="10" id='pondolplugin-carousel-visibleitems' value='<?php echo $cfg->visibleitems; ?>' /></label></td>
						</tr>
						<tr>
							<th>speed</th>
							<td><label><input name='pondolplugin-carousel-speed' type='text' size="10" id='pondolplugin-carousel-speed' value='<?php echo $cfg->speed; ?>' /></label></td>
						</tr>
						<tr>
							<th>direction</th>
							<td>
								<label><input name='pondolplugin-carousel-direction' type='text' size="10" id='pondolplugin-carousel-direction' value='<?php echo $cfg->direction; ?>' /> left or right</label>
								
							</td>
						</tr>
						</table>
				</div>
			</li>
			<li class="pondolplugin-tab">
				<div id="pondolplugin-carousel-preview-tab">
					<div id="pondolplugin-carousel-preview-container">
					</div>
				</div>
			</li>
			<li class="pondolplugin-tab">
				<div id="pondolplugin-carousel-publish-loading"></div>
				<div id="pondolplugin-carousel-publish-information"></div>
			</li>
		</ul>
		</div>