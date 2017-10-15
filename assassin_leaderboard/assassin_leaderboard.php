<?php
/* 
Plugin Name: Assassin Leaderboard
Description: List all players with shortcode [assassin-leaderboard]
Author:		 Paul Salessi
Plugin URI: https://github.com/psalessi/assassin_wordpress_plugins

Copyright 2017 Paul Salessi

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

// 1. Add DataTables script tags to page header
add_action('wp_head','include_datatables');
function include_datatables() {
	$datatables = '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/dt/dt-1.10.11,r-2.0.2/datatables.min.css"/>';
	$datatables .= '<script type="text/javascript" src="https://cdn.datatables.net/t/dt/dt-1.10.11,r-2.0.2/datatables.min.js"></script>';
	echo $datatables;
} // end include_datatables

// 2. Add assassin-leaderboard shortcode and construct HTML user table
// Going to use DataTables for this
function assassin_leaderboard( $atts ){
	global $assassin_leaderboard_shortcode_used;
	
	$all_users = get_users();
	
	$assassin_users_table = '<div class="table-responsive">';
	$assassin_users_table .= '<table id="assassin_users_table" class="display">';
	$assassin_users_table .= '<thead>';
	$assassin_users_table .= '<tr>';
	$assassin_users_table .= '<th>First Name</th>';
	$assassin_users_table .= '<th>Last Name</th>';
	$assassin_users_table .= '<th>Score</th>';
	$assassin_users_table .= '<th>Avatar</th>';
	$assassin_users_table .= '</tr>';
	$assassin_users_table .= '</thead>';
	$assassin_users_table .= '<tbody>';
	
	foreach ( $all_users as $user ) {
		$assassin_users_table .= '<tr>';
		$assassin_users_table .= '<td>' . $user->first_name . '</td>';
		$assassin_users_table .= '<td>' . $user->last_name . '</td>';
		$assassin_users_table .= '<td>' . $user->score . '</td>';
		$assassin_users_table .= '<td>' . get_avatar($user->ID, 52) . '</td>';
		$assassin_users_table .= '</tr>';
	}
	
	$assassin_users_table .= '</tbody>';
	$assassin_users_table .= '</table>';
	$assassin_users_table .= '</div>';
	
	$assassin_leaderboard_shortcode_used = true;
	
	return $assassin_users_table;
} // end assassin_leaderboard
add_shortcode( 'assassin-leaderboard', 'assassin_leaderboard' );

// 3. Create DataTables Javascript object
function assassin_datatables_create() {
	global $assassin_leaderboard_shortcode_used;
	
	if ( ! $assassin_leaderboard_shortcode_used )
		return;
	?>
	<script type="text/javascript">
		jQuery(document).ready(function ( $ ) {
			$('#assassin_users_table').DataTable({
				"responsive" : true,
				"paging" : false,
                "columnDefs" : [
					{ "width": "25%", "targets": 0 },
					{ "width": "25%", "targets": 1 },
					{ "width": "25%", "targets": 2 },
					{ "width": "25%", "targets": 3 }
                ],
				"order": [[ 2, "desc" ]]
			});
			
			// Need this to fix responsive columns
			$('#assassin_users_table').css("width", "auto");
		});
	</script>
	<?php
} // end assassin_datatables_create
add_action('wp_footer', 'assassin_datatables_create');