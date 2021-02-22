<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	
	<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/apple-touch-iphone.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/images/apple-touch-ipad.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/images/apple-touch-iphone4.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/images/apple-touch-ipad-retina.png" />
	<link rel="shortcut icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<?php 
	$homeClass = '';
	if(is_front_page()){
		$homeClass = 'home';
	}
	global $post;
	$post_slug = '404';
	if(!empty($post)){
		$post_slug = $post->post_name;
	}
?>
<body <?php body_class('page-'.$post_slug.''); ?> >
	<div id="dtBox"></div>
	<div class="siteWrapper">
		<div class="contactBar">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<?php echo do_shortcode('[gtranslate]'); ?>
						<!-- <ul class="selectLanguage">
							<li class="active">English</li>
							<li>عربى</li>
							<li>語言</li>
							<li>భాష</li>
							<li>மொழி</li>
						</ul> -->
						<div class="digitalClock"><div id="fHours"></div><span>:</span><div id="fMins"></div><span>:</span><div id="fSecs"></div> <div id="fAmPm"></div></div>
						<script>
							function clock(){
								var now = new Date();
								var hour = now.getHours();
								var min = now.getMinutes();
								var sec = now.getSeconds();
								var mid = 'am';
								if (sec < 10) { sec = "0" + sec; }
								if (min < 10) { min = "0" + min; }
								if (hour > 12) { 
									mid = 'pm';
									hour = hour - 12;
								}
								if(hour==0){ hour=12; }
								if (hour < 10) { hour = "0" +hour; }
								document.getElementById("fHours").innerHTML = hour;
								document.getElementById("fMins").innerHTML = min;
								document.getElementById("fSecs").innerHTML = sec;
								document.getElementById("fAmPm").innerHTML = mid;
								setTimeout(clock, 1000);
							}clock();
						</script>
						<?php wp_nav_menu(array('theme_location' => 'contactDetails', 'container' => false, 'menu_class' => 'contactMenu' )); ?>
					</div>
				</div>
			</div>
		</div>
		<header>
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="topMenu">
							<button class="hamburger hamburger--squeeze" type="button">
								<span class="hamburger-box">
									<span class="hamburger-inner"></span>
								</span>
							</button>
							<?php
								$custom_logo_id = get_theme_mod( 'custom_logo' );
								$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
							?>
							<a class="logo" href="<?php echo get_site_url(); ?>"><?php
								if ( has_custom_logo() ) {
									echo '<img src="'. esc_url( $logo[0] ) .'">';
								} else {
									echo '<h1>'. get_bloginfo( 'name' ) .'</h1>';
								}
							?></a>
							<?php wp_nav_menu(array('theme_location' => 'mainMenu', 'container' => false, 'menu_class' => 'menuList' )); ?>
						</div>
					</div>
				</div>
			</div>
		</header>

		<?php if(!is_front_page()){ ?>
			<?php
			$img = get_template_directory_uri().'/images/about-eagle-driving-school.jpg';
			if(has_post_thumbnail()){ $img = get_the_post_thumbnail_url(); }
			?>
			<div class="pageHeaderBG" style="background-image: url('<?php echo $img; ?>')">
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<h1><?php wp_title(''); ?></h1>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

