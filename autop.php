<?php
/*
Plugin Name: Make Autop
Plugin URI: http://www.alekseykostin.ru/339/
Description: Allows you to apply wpautop() function to all posts and pages
Version: 0.1
Author: Kostin Aleksey
Author URI: http://www.alekseykostin.ru/
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html

	Copyright (c) 2011 Kostin Aleksey

	Permission is hereby granted, free of charge, to any person obtaining a
	copy of this software and associated documentation files (the "Software"),
	to deal in the Software without restriction, including without limitation
	the rights to use, copy, modify, merge, publish, distribute, sublicense,
	and/or sell copies of the Software, and to permit persons to whom the
	Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
	DEALINGS IN THE SOFTWARE.
*/


if ( ! function_exists( 'autop_init' ) )
{
	function autop_init()
	{
		load_plugin_textdomain('autop', 'wp-content/plugins/'.basename(dirname(plugin_basename(__FILE__))), dirname(plugin_basename(__FILE__)));
	}
	add_action('init', 'autop_init');
}

if ( ! function_exists( 'autop_options' ) )
{
	function autop_options()
	{
		GLOBAL $wpdb;
		
		// Check form submission and update options...
		if(isset($_GET['submit']))
		{
			$sth = $wpdb->get_results('SELECT ID, post_content FROM wp_posts WHERE post_type IN (\'page\', \'post\', \'revision\')', ARRAY_A);
			foreach ($sth as $rs)
				$wpdb->update($wpdb->posts, array('post_content'=>wpautop($rs['post_content'])), array('ID'=>$rs['ID']), array('%s'), array('%d'));
			
			// Output any action message (note, can only be from a POST or GET not both).
			echo "<div id='message' class='updated fade'><p>", __('Done', 'autop'), "</p></div>";
		}
		?>
		<div class="wrap">
			<h2><?php echo __('Autop', 'autop'); ?></h2>
			<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="page" value="<?php echo basename(__FILE__); ?>">
				<p class="submit"><input type="submit" name="submit" class="button-primary" value="<?php echo __('Apply wpautop()', 'autop'); ?>" /></p>
			</form>
		</div>
		<?php
	}
}

if ( ! function_exists( 'autop_menu' ) )
{
	function autop_menu()
	{
		add_options_page(__('Autop', 'autop'), __('Autop', 'autop'), 1, basename(__FILE__), 'autop_options');
	}
	add_action('admin_menu', 'autop_menu');
}

?>