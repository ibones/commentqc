CommentQC
----------------------------------------------------

CommentQC (quality control) is an experiment of mine to see how well programmatic filtering of useless txt speak or otherwise broken comments are. I can't say how production ready it is, you'll probably get some false positives/negatives, but it's worth a shot.

### What is CommentQC for?

* Filtering out painful comments like "plz help me plzzzzzzz"
* Filtering out standard txt speak
* Filtering out some obvious spam

### What is CommentQC *not* for?
* Being a grammar nazi. It doesn't, nor should you make it, reject comments for petty mistakes like _your_ and _you're_. There's a line between quality control and being an ass.
* Fixing broken comments. It won't attempt to rescue a bad comment, the system either accepts or rejects.

### I'm sold, how do I use it?
A codeblock speaks a thousand words.

```php
require 'src/iBones/CommentQC.php';

$checker = new \iBones\CommentQC( $_POST['comment'] );
if ( $checker->commentPasses() ) {
    // $database->query('INSERT INTO...');
    echo 'OK';
} else {
    echo "We're sorry, but your comment does not meet our quality standards.";
}
```

If you want to check for failure instead of success, the `commentFails()` method will simply return the reverse.

### Plugins
A plugin for [MyBB](http://mybb.com) exists in the plugins directory. If you write one for another platform, a pull request is appreciated.

### To-Do
* A way of ignoring syntax between configurable markers, such as quote and code tags
* Allowing opt-in or opt-out of each check