<?php

/* 
 * Social Media Footer
 */

if (is_active_sidebar('startpage-socialmediainfo')) {
?>
	<aside id="social" aria-labelledby="socialbartitle">
		<div class="container">
			<div class="flex-four-widgets">
				<h2 id="socialbartitle" class="screen-reader-text"><?php _e("Weitere Hinweise zum Webauftritt", "fau"); ?></h2>
				<?php
				dynamic_sidebar('startpage-socialmediainfo');
				?>
			</div>
		</div>
	</aside>
<?php
}
