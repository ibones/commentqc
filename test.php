<?php
/**
 * A quick way to make sure you didn't break something. Run "php test.php"
 * in the command line.
 *
 * @todo a proper test
 */

require 'src/iBones/CommentQC.php';

$should_pass = array(
	'Grumpy wizards make toxic brew for the evil Queen and Jack.',
	'The quick brown fox jumps over the lazy dog.',
	'I enjoyed your blog post.',
	'freshen your drink, governor?',
);
 
$should_fail = array(
	'Plz help meeeeeee',
	'how to do this ??',
	'ur a faggot',
	'PLS PLSSS I NEED IT',
	'ur a FAGGOT!!!'
);

foreach ( $should_pass as $comment ) {
	$checker = new \iBones\CommentQC( $comment );
	if ( $checker->commentFails() ) {
		exit("[Fail] A failing comment should have passed. ('{$comment}')");
	}
}

foreach ( $should_fail as $comment ) {
	$checker = new \iBones\CommentQC( $comment );
	if ( $checker->commentPasses() ) {
		exit("[Fail] A passing comment should have failed. ('{$comment}')");
	}
}

exit('[Pass] All good!');