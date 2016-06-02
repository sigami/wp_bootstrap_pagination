# wp_bootatrap_pagination
Cover all types of pagination within wordpress: Link pages, numeric archives, next and previous posts and comments

## Usage
include 'wp_boostrap_pagination.php' on your theme
on index.php, archive.php and search.php replace 'the_posts_navigation();' for 'wp_boostrap_pagination::numeric();'
on single.php replace 'the_post_navigation();' for 'wp_boostrap_pagination::posts();'
on comments.php use 'wp_boostrap_pagination::comments_simple()'
