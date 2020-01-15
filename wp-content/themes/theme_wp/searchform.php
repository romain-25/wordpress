<form class="search-box" action="/" method="get">
    <label for="search" placeholder="Search here..."></label>
    <input type="search" name="s" id="search" value="<?php the_search_query(); ?>" />
    <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
</form>
