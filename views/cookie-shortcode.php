<?php
$url = '/privacy-policy';
if(get_page_by_title('Privacy Policy') == null) {
	$url = '/cookie-policy';
}
?>
<div id="wp-notification" class="closed">
	<div class="wp-notification-container">
		<p>This website uses cookies to enhance your browsing experience... <a href="<?= site_url($url) ?>">more</a><span id="wp-notification-toggle">got it</span></p>
	</div>
</div>