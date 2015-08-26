<?php
	$rand_id = uniqid();

	// WPML filter
	$suppress_filters = get_option('suppress_filters');

	// Get Order & Orderby Parameters
	$orderby = ( of_get_option('slider_posts_orderby') ) ? of_get_option('slider_posts_orderby') : 'date';
	$order   = ( of_get_option('slider_posts_order') ) ? of_get_option('slider_posts_order') : 'DESC';

	// query
	$args = array(
		'post_type'        => 'slider',
		'posts_per_page'   => -1,
		'post_status'      => 'publish',
		'orderby'          => $orderby,
		'order'            => $order,
		'suppress_filters' => $suppress_filters
		);
	$slides = get_posts($args);
	if (empty($slides)) return;
?>

<script type="text/javascript">
		jQuery(function() {
			var isparallax = true;
			if(!device.mobile() && !device.tablet()){
				isparallax = true;
			}else{
				isparallax = false;
			}

				jQuery('#parallax-slider-<?php echo $rand_id ?>').parallaxSlider({
					animateLayout: 'simple-fade-eff'
				,	duration: 1000
				,	parallaxEffect: isparallax
				});
			
		});
		jQuery(window).load(function(){
			if (window.addEventListener) window.addEventListener('DOMMouseScroll', wheel, false);
				window.onmousewheel = document.onmousewheel = wheel;

				var time = 330;
				var distance = 100;

				function wheel(event) {
					if (event.wheelDelta) delta = event.wheelDelta / 90;
					else if (event.detail) delta = -event.detail / 3;
					handle();
					if (event.preventDefault) event.preventDefault();
						event.returnValue = false;
				}

				function handle() {
					jQuery('html, body').stop(stop).animate({
						scrollTop: jQuery(window).scrollTop() - (distance * delta)
					}, time);
				}
		})
</script>

<?php
	$resutlOutput.= '<div id="parallax-slider-'.$rand_id.'" class="parallax-slider">';
		$resutlOutput.= '<ul class="baseList">';
			foreach( $slides as $k => $slide ) {
				$url                = get_post_meta($slide->ID, 'my_slider_url', true);
				//$sl_image_url       = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'slider-post-thumbnail');
				$sl_image_url       = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'full');
				$caption            = get_post_meta($slide->ID, 'my_slider_caption', true);

				if ( $sl_image_url[0]=='' ) {
					$sl_image_url[0] = PARENT_URL."/images/blank.gif";
				}
				if ( $url!='' ) {
					$url = "data-link='$url'";
				}
				$resutlOutput.= '<li data-preview="'. $sl_image_url[0] .'" data-img-width="'. $sl_image_url[1] .'" data-img-height="'. $sl_image_url[2] .'">';
					if ($caption) {
						$resutlOutput.= '<div class="slider_caption>">';
						$resutlOutput.= stripslashes(htmlspecialchars_decode($caption));
						$resutlOutput.= '</div>';
					}
				$resutlOutput.= '</li>';
				
			}
		$resutlOutput.= '</ul>';
	$resutlOutput.= '</div>';

	echo $resutlOutput;
	wp_reset_postdata();
?>

