// This file contains the logic to derigister BlockStyles registered in wp-core
wp.domReady(() => {
	wp.blocks.unregisterBlockStyle('core/image', 'rounded');
  wp.blocks.unregisterBlockStyle('core/quote', 'plain');
  wp.blocks.unregisterBlockStyle('core/separator', 'wide');
  wp.blocks.unregisterBlockStyle('core/separator', 'dots');
});