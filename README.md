# wp_bootstrap_pagination
Cover all types of pagination within wordpress: Link pages, numeric archives, next and previous posts and comments

## Usage
1. Include 'wp_boostrap_pagination.php' on your theme
1. On index.php, archive.php and search.php replace 'the_posts_navigation();' for 'wp_boostrap_pagination::numeric();'
1. On single.php replace 'the_post_navigation();' for 'wp_boostrap_pagination::posts();'
1. On comments.php use 'wp_boostrap_pagination::comments_simple()'

## How it looks

Archive and index 

![numeric navi](http://image.prntscr.com/image/b76723c06e3a477f9411463fdb0a6513.png "Numeric Navi")

Posts pagination

![posts navi](http://image.prntscr.com/image/c6bb563bb031419aad46531430a4fb8a.png "Posts Navigation")

Post pages navigation

![posts pages navi](http://image.prntscr.com/image/5772a2330ea9431d809284f5d8c8e7a4.png "Posts Pages Navigation")

Comments navigation

![comments navigation](http://image.prntscr.com/image/ef055361988449f0be84b70cbc04910a.png "Comments Navigation")
