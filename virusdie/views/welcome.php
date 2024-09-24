<?php
/**
 * The welcome page template.
 *
 * @package Virusdie
 */

// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}
?>

<div class="vd-container">
	<div class="vd-welcome">
		<div class="vd-welcome__slider-wrapper">
			<div class="vd-welcome__slider">
				<div class="slider" id="firstSlider">
<?php for ($vd_img = 1; $vd_img <= 7; $vd_img++) : ?>
					<div class="vd-welcome__slide"><img src="<?php echo VDWS_VirusdieHelper::getWelcomeImgPath($vd_img); ?>" alt="slide" /></div>
<?php endfor ?>
				</div>
			</div>
			<ul class="slider__controls --first" aria-label="Carousel Navigation" tabindex="0">
				<li class="prev" aria-controls="customize" tabindex="-1" data-controls="prev">
					<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/arrow-left.svg" alt="&lt;" />
				</li>
				<li class="next" aria-controls="customize" tabindex="-1" data-controls="next">
					<img src="<?php echo constant('VDWS_VIRUSDIE_PLUGIN_URL'); ?>assets/img/icons/arrow-right.svg" alt="&gt;" />
				</li>
			</ul>
		</div>
		<div class="vd-welcome__footer">
			<button type="button" id="vdNextSlideBtn" class="vd-btn --black">Next</button>
			<a href="<?php echo esc_attr(constant('VDWS_VIRUSDIE_PLUGIN_ADMIN_URL')); ?>" class="vd-learn-more --black --inline">Skip</a>
		</div>
	</div>
</div>
