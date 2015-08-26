<?php /* Static Name: Footer text */ ?>
<div id="footer-text" class="footer-text">
	<?php $myfooter_text = of_get_option('footer_text'); ?>
	
	<?php if($myfooter_text){?>
		<?php echo of_get_option('footer_text'); ?>
	<?php } else { ?>
		<a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>" class="site-name"><strong><?php bloginfo('name'); ?></strong></a> &copy; <?php echo date('Y'); ?> |
		Website designed by <a href="http://www.templatemonster.com/" target="_blank" rel="nofollow">TemplateMonster.com</a> |
		<a href="<?php echo home_url(); ?>/privacy-policy/" title="<?php echo theme_locals('privacy_policy'); ?>"><?php echo theme_locals("privacy_policy"); ?></a>		
	<?php } ?>
	<?php if( is_front_page() ) { ?>
		More <a rel="nofollow" href="http://www.templatemonster.com/category/business-wordpress-templates/" target="_blank">Business WordPress Templates at TemplateMonster.com</a>
	<?php } ?>
</div>