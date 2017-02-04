<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="mini-search">
	<input type="text" placeholder="Search" value="<?php echo  get_search_query() ?>" name="s" />
    <input type="hidden" name="post_type" value="post">
</form>