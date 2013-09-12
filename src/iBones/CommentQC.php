<?php
/**
 * CommentQC
 * Filter out broken/txtspeak comments from your application.
 * 
 * @author iBones
 * @package default
 */

namespace iBones;

class CommentQC
{
	/**
	 * @see __construct()
	 */
	protected $comment;
	
	/**
	 * @param	string	the comment to validate.
	 * @return	void
	 */
	public function __construct( $comment )
	{
		$this->comment = $comment;
	}
	
	/**
	 * The beef, does all the validation legwork.
	 * @return	boolean	Did it pass?
	 */
	public function commentPasses()
	{
		// If it's less than 4 chars then it's probably junk.
		if ( strlen($this->comment) <= 4 ) {
			return false;
		}
		
		// Is there a long stretch of the same letter? Helloooooooooooo?
		if ( preg_match("/([a-zA-Z\?\!\.\,\:\;])\\1{5}/", $this->comment) ) {
			return false;
		}
		
		// Are there spaces before punctuation ? like this ???? isn't this annoying ????!
		if ( preg_match("/ [\?\!\.\,\:\;]/", $this->comment) ) {
			return false;
		}
		
		// Any inexcusable shorthand? PLZZZZ BRO???????????
		if ( preg_match("/\b(pls|plz|ur)\b/i", $this->comment) ) {
			return false;
		}
		
		// If we got this far, we're all good.
		return true;
	}
	
	/**
	 * Some syntactic sugar if you want to check the reverse.
	 * @return	boolean	Did it fail?
	 */
	public function commentFails()
	{
		return !$this->commentPasses();
	}
}