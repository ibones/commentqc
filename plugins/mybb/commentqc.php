<?php
/**
 * CommentQC plugin for MyBB
 * Filter junk/txtspeak comments from your forum
 *
 * @license http://github.com/ibones/commentqc/blob/master/license.txt
 * @link http://github.com/ibones/commentqc
 * @todo Ignore the contents of [code] tags, easy potential for false positive
 * @todo Provide setting to either reject comment (current functionality) or place in mod queue
 */
 
// Disallow direct access to this file for security reasons
if ( !defined('IN_MYBB') ) {
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

// Add hooks
$plugins->add_hook('global_start', 'commentqc_load_file');
$plugins->add_hook('datahandler_post_validate_post', 'commentqc_check_post');
$plugins->add_hook('datahandler_post_validate_thread', 'commentqc_check_post');

/**
 * Info... aduhhh
 * @return array The info
 */
function commentqc_info()
{
	$desc = 'Filter junk/txtspeak comments from your forum.';
	if ( !commentqc_is_installed() ) {
		$desc .= ' Ensure <span style="font-weight: bold;">/inc/3rdparty/CommentQC.php</span> exists before installing.';
	}
	
	return array(
		'name'			=> 'CommentQC for MyBB',
		'description'	=> $desc,
		'website'		=> 'http://github.com/ibones/commentqc',
		'author'		=> 'iBones',
		'authorsite'	=> 'http://github.com/ibones',
		'version'		=> '1.0.0',
		'compatibility' => '16*'
	);
}

/**
 * Evidently MyBB does not have any form of autoloading... sigh
 * @return void
 */
function commentqc_load_file()
{
	require MYBB_ROOT . 'inc/3rdparty/CommentQC.php';
}

/**
 * There's probably a better way of doing this but I couldn't figure it out from the code
 * or MyBB's docs. This is only used in the installer anyway.
 * @return void
 */
function commentqc_error_message( $message )
{
	die('<script>
		window.onload = function() {
			window.alert(\'CommentQC Error: {$message}\');
		}
	</script>');
}

/**
 * Install the plugin
 * @return void
 */
function commentqc_install()
{
	// Am I seriously doing this?
	global $db;
	
	// Perhaps you forgot the library file
	if ( !is_readable(MYBB_ROOT . 'inc/3rdparty/CommentQC.php') ) {
		commentqc_error_message('Unable to find or read the CommentQC.php file. Please ensure it exists in /inc/3rdparty');
	}
	
	// Add a settings group for the plugin
	$db->insert_query('settinggroups', array(
		'name' => 'commentqc',
		'title' => 'CommentQC',
		'description' => 'Settings for the CommentQC plugin.',
		'disporder' => '100',
		'isdefault' => 'no',
	));
	
	// Get key for above query
	$group_id = (int) $db->insert_id();
	
	// Insert setting (error to present if comment fails to pass)
	$db->insert_query('settings', array(
		'name'			=> 'commentqc_error_message',
		'title'			=> 'Message',
		'description'	=> 'Error to show if comment fails.',
		'optionscode'	=> 'text',
		'value'			=> "Your post appears to contain txt speak or potentially incomprehensible language, please revise it and try submitting again.",
		'disporder'		=> '1',
		'gid'			=> $group_id,
	));
	
	// For some reason this isn't automatic
	rebuild_settings();
}

/**
 * Uninstalls the plugin
 * @return void
 */
function commentqc_uninstall()
{
	global $db;
	
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='commentqc'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='commentqc_error_message'");
	
	rebuild_settings();
}

function commentqc_is_installed()
{
	global $mybb;
	
	return array_key_exists('commentqc_error_message', $mybb->settings);
}

/**
 * Check the post
 * @see /inc/3rdparty/CommentQC.php
 * @return void
 */
function commentqc_check_post( $handler )
{
	global $mybb;
	
	// If you absolutely must use PHP 5.2, I'm pretty sure all you need to do is de-namespace
	// the class. While you're at it, get yourself one of them fancy new Moto Razrs.
	$checker = new \iBones\CommentQC( $handler->data['message'] );
	
	if ( $checker->commentFails() ) {
		$handler->set_error( $mybb->settings['commentqc_error_message'] );
	}
}
