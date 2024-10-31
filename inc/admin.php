<?php
function bbpsampledata_add_admin_menu() {
	$confHook = add_management_page('bbPress SampleData', 'bbPress SampleData', 'delete_forums', 'bbpsampledata', 'bbpsampledata_page');
}
add_action('admin_menu', 'bbpsampledata_add_admin_menu');

function bbpsampledata_page() {
	$min_bbpress_version = '2.6.0';
	echo '<h2>Sample Data for bbPress</h2>';

	// Check bbPress is running and all functions are present.
	$pass = true;
	if ( !function_exists('bbp_insert_forum') )  $pass = false;
	if ( !function_exists('bbp_get_version') )   $pass = false;
	if ( !function_exists('bbp_set_user_role') ) $pass = false;
	if ( !function_exists('bbp_insert_topic') )  $pass = false;
	if ( !function_exists('bbp_insert_reply') )  $pass = false;
	
	if ( !$pass ) {
		echo 'Sorry but bbPress needs to be running, and at least at version ' . $min_bbpress_version . '!<br>';
		return;
	}

	if ( !empty ( $_REQUEST['_wpnonce'] ) ) {
		$nonce = $_REQUEST['_wpnonce'];
		if ( !wp_verify_nonce( $nonce, 'createbasic' ) ) {
			// This nonce is not valid.
			echo 'Security check failed.<br>'; 
		} else {
			if ( !empty( $_GET['createbasic'] ) ) {
				$time_start = microtime(true);
				$ret = bbpsd_create_basic_structure();
				if ( !$ret ) echo 'ERROR: Unable to create the basic sample structure!<br>';
				$time_end = microtime(true);
				$time_diff_sec = intval($time_end - $time_start);
				echo '<br>' . $time_diff_sec . ' secs needed.<br><br>';
					$arr = array(
						'time' => $time_diff_sec,
						);
					update_option('bbpsd_basic_structure', $arr, false);
			}
		}
	}		

	$arr = get_option( 'bbpsd_basic_structure', array() );
	if ( !empty ( $arr['time'] ) ) {
		echo '<h3><font color="red">WARNING: You have already run this before. Running again will almost double all forums, users, topics and replies!</font></h3>';
	}
	
	echo 'Hit "Create basic structure" to insert a basic bbPress structure:<br>';
	echo '- Users: 2 forum moderators, 5 Participants<br>';
	echo '- Forums: 1 main forum category, 2 sub forum categories, 4 third level forums<br>';
	echo '- Topics: 16 logged-in user and 2 anonymous topics<br>';
	echo '- Replies: 32 logged-in user and 2 anonymous replies<br>';
	echo '<br>';
	$base_url = admin_url( 'tools.php?page=bbpsampledata&createbasic=y' );
	$complete_url = wp_nonce_url( $base_url, 'createbasic');
	echo '<a class="button button-primary" href="' . $complete_url . '">Create basic structure</a><br>';
	echo '<br>';
	echo 'After creating the basic structure, you can add extra information in the standard way.<br>';
	

}
