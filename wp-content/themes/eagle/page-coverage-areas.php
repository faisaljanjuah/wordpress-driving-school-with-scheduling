<?php get_header(); ?>

<?php $aboutMain = 9; ?>
<div class="page-content page-<?php the_ID(); ?>-content">
    <div class="container">
        <div class="row">
			<div class="col-xs-12">
				<div class="coverageContents">
					<?php
						if(have_posts()):
							while(have_posts()) : the_post();
								the_content();
							endwhile;
						endif;
						wp_reset_postdata();
					?>
				</div>
			</div>
            <div class="col-xs-12">
                <div id="coverageMap" data-image="<?php echo get_template_directory_uri(); ?>/images/markeryellow.png" class="coverageMap"></div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
	var locations = [
		['Ashburn', 39.043758, -77.487442],
		['Sterling', 39.0067088, -77.4299787],
		['Fairfax', 38.846226, -77.306374],
		['South Riding', 38.913694, -77.496576],
		['Leesburg', 39.115662, -77.563599],
		['McLean', 38.93653,-77.179184],
		['Stone Ridge', 38.941304, -77.542388],
		['Great Falls', 39.011495,-77.358157],
		['Woodbridge', 38.658173, -77.249702],
		['Arlington', 38.881062, -77.109484] // Default last location
	];
	var map = new google.maps.Map(document.getElementById('coverageMap'), {
		zoom: 10,
		scrollwheel:false,
		draggable: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		center: new google.maps.LatLng(38.9030741,-77.3120187)
	});

	var marker, i;
	for (i = 0; i < locations.length; i++) {  
		marker = new google.maps.Marker({
			icon: jQuery('.coverageMap').attr('data-image'),
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map
		});
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}

	var infowindow = new google.maps.InfoWindow({
		content: 'Eagle Driving School'
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
	// infowindow.open(map,marker); // open default info window on start
</script>
<?php get_footer(); ?>