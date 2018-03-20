# WordPress/Giphy Integration

This is a plugin for WordPress that builds an GIF searching tool into the WordPress editor using the Giphy API*.  

## How it Works

### The UI

* Type a search term into the field and Giphy will return 8 thumbnails that match.
* The thumbnails are still versions, because having all of those animations would be chaotic.
* Mouse over each thumbnail and the still is replaced with the animated version.
* Click a thumbnail, and it is added above to the list of "Chosen GIFs".
* Pagination buttons appear at the bottom enabling you to view 8 more results, etc.

### The WordPress side

* The Giphy IDs corresponding to the thumbnails under Chosen GIFs are tracked via jQuery in hidden fields creating an array that is posted to WordPress when the user Updates the post
* When editing an existing post, those thumbnails appear in the same place
* Click a thumbnail to flag it for deletion when you Update.  (It will disappear from the list.)
* Conduct another search and new GIF thumbnails will be appended to the "Chosen GIFs" list to be added upon Update

## In Need of Improvement

* The Chosen GIFs thumbnails should be reordered via drag/drop
* Confirm dialog for deleting should be coded instead of the native JS confirm()
* It should be possible to use the Return key to invoke the search instead of having to click the button
* Figure out a way to lay out the search result thumbnails more neatly

_____
\* - A Giphy API key needs to be defined locally in a config.js file that is not included here.
