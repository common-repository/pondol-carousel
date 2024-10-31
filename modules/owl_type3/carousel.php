<?php
wp_enqueue_script( 'pondolplugin-carousel-owl.carousel-script', plugins_url('owl.carousel.js', __FILE__ ));
wp_enqueue_style( 'pondolplugin-carousel-owl.carousel-style', plugins_url('owl.carousel.css', __FILE__ ));
wp_enqueue_style( 'pondolplugin-carousel-owl.carousel-theme-style', plugins_url('owl.theme.css', __FILE__ ));
wp_enqueue_style( 'pondolplugin-carousel-style', plugins_url('pondol.css', __FILE__ ));
?>
<script>
jQuery(document).ready(function($) {

	$('.owl-carousel').owlCarousel({
		singleItem : true,
		autoPlay : <?php echo $data->autoplay?>,
		slideSpeed : <?php echo $data->speed?>,
		stopOnHover : true,
		navigation : true,
		pagination : true
	});
});
</script>




<div class="pondolSkin_3 owl-carousel">
<?php	
	if (isset($data->slides) && count($data->slides) > 0) foreach ($data->slides as $slide) {
		$slide->image = $slide->displaythumbnail == "true" ? $slide->thumbnail:$slide->image;
		echo '<div class="item">
			<img src="'.$slide->image.'" alt="'.$slide->title.'" longdesc="'.$slide->description.'" cite="'.$slide->weblink.'" data-target="' . $slide->linktarget . '" />
			<div class="description">';
				if ($slide->weblink && strlen($slide->weblink) > 0){
					echo '<a href="'.$slide->weblink.'" class="title"';
					if ($slide->linktarget && strlen($slide->linktarget) > 0) $ret .= 'class="title" target="' . $slide->linktarget . '"';
					echo '>'.$slide->title.'</a>';
				} else {
					echo '<span class="title">'.$slide->title.'</span>';
				}
			echo '</div></div><!-- .item -->';
	}
?>
</div>
