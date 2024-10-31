<?php
/*
Plugin Name: Sample data for bbPress
Description: Test your plugin, theme, performance or unit tests with sample data for bbPress.
Plugin URI: https://wordpress.org/plugins/bbpress-sample-data
Author: Pascal Casier
Author URI: http://casier.eu/wp-dev/
Version: 1.0.0
License: GPLv2
*/

// No direct access
if ( !defined( 'ABSPATH' ) ) exit;

include('inc/admin.php');
include('inc/functions.php');

/*
TODO:
- Allow to bulk add extra users, forums, topics, replies
- Mark a user as spammer and topic/replies ( bbp_make_spam_user )
- Add a moderation to a forum : bbp_add_moderator( $forum_id, $user->ID ) )
- Add some subscription to forum and topics : bbp_add_user_subscription( $user->ID, $assoc_args['object-id'] ) )
- Create pages with forum structure
- Create widget with last posts
- i18n
*/

