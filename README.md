# Piggly View

The **Piggly Views WordPress Plugin** allows you to track views for each post. Once you install, Wordpress will be able to store views in `{wp->sufix}_pigglyviews` table.
The plugin is quite simple to use. See below.

1. It will store all views for a Single Post. You can configure it to ignore views to logged users and disable for some posts.
2. In the Posts View Table you will see the View column. You can remove it in configurations.
3. All views will be cached for 24 hours. You can disabled it or change the flush cache hour.

For now, **Piggly Views** is available in:

* English,
* Portuguese Brazilian.

## Some additional resources

* You can use the shortcode `[piggly_views]` to get views to the current post OR use `[piggly_views id="post_id"]` to a specific post. It will return the number of views following the format set in the settings.
* You can get a collection of most viewed posts by using the method `piggly_view_collection($limit,$days)`. Where `$days` is the range between NOW and X(`$days`) days, `$limit` is the number of posts and `$types` is one or more post types slug into an array, such as: `post`, `page` or `attachment`.
* You can use the shortcode `[piggly_views_collection limit="X" days="X" types="post, page, attachment"]` to get a collection of most viewed posts returning the default template.

## Getting the most viewed posts

An easy way to get the most viewed posts is using the global function `piggly_view_collection()`. The default `$days` values is 30, default `$limit` value is 5 and default `$types` array is post.

```
// 10 most viewed posts in the last 180 days.
$most_viewed = piggly_view_collection( 10, 180 );

if ( !empty( $most_viewed ) ) :
    foreach ( $most_viewed as $post ) :
        $postID = $post->post_id;
    endforeach;
endif;
```

In the other side, you can use the shortcode `[piggly_views_collection limit="10" days="180" types="post"]` to return the default template.

When you use `[piggly_views_collection]` shortcode, it will return the following template for each post. Customize CSS as your needs by using `.pgl-views-post` as parent.

```
<a href="{{link}}" title="{{title}}" rel="bookmark" class="post">
	<div class="left">
		<div class="cover" style="background-image: url({{thumbnail}});" data-item="{{index}}"></div>
	</div>
	<div class="right">
		<h3 class="title">{{title}}</h3>
		<div class="prop">
			<span class="category">{{category}}</span>
			<span class="author">{{author}}</span>
			<span class="date">{{date}}</span>
		</div>
	</div>
</a>
```

## How to Install

### From the Wordpress Plugin Directory

The Official **Piggly Views WordPress Plugin** can be found here: https://wordpress.org/plugins/piggly-views/

### From this Repository

Go to the [releases](https://github.com/piggly-dev/piggly-views-wordpress/releases) section of the repository and download the most recent release.

Then, from your WordPress administration panel, go to `Plugins > Add New` and click the `Upload Plugin` button at the top of the page.

### From Source

You will need Git installed to build from source. To complete the following steps, you will need to open a command line terminal.

Clone the Github repository:

`git clone https://github.com/piggly-dev/piggly-views-wordpress.git`

## How to Use

From your WordPress administration panel go to `Plugins > Installed Plugins` and scroll down until you find `Piggly Views`. You will need to activate it first, then click on `Settings` to configure it.

### Configuration

* Display or not View Column in Post Table.
* Disable tracking for Logged Users.
* Convert Views from 1000 to 1K format.
* Cache Views Data.
* Flush cache after X hours.

* Keep options and/or table when uninstall the plugin.

### Changelog

#### 1.1.0
Added the shortcode `[piggly_views_collection]` to return a collection based in a default template.

#### 1.0.1
Now you can select one or more post type when getting a collection with `piggly_views_colletion`.

#### 1.0.0
Initial release.

### Developers

The **Piggly Views WordPress Plugin** welcomes additions from developers. To ensure code is consistent and that all developers can focus on building great features rather than deciphering various coding styles, the **Piggly Views WordPress Plugin** adheres to the WordPress Boilerplate Plugin coding standards.

To ensure your code is formatted the WordPress way, you can run the PHP code sniffer using the WordPress coding ruleset. There are various ways to install both PHPCS and the WordPress coding ruleset, but the easiest method is to install it globally using Composer. Assuming you have Composer and PHPCS installed and available from the command line, run the following from a terminal (assuming you are on Linux or a *nix derivative):

You can run PHP CodeSniffer with composer run-script lint and composer run-script clean to automatically fix the style errors if possible.

## [Contributing](https://github.com/piggly-dev/piggly-views-wordpress/blob/master/CONTRIBUTING.md)