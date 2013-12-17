=== No Slug Conflicts with Trash ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: slug, post_name, post, trash, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 1.0.1

Prevent the slug of a trashed page or post from conflicting with the slug desired for a new page or post.


== Description ==

This plugin implements the belief that a trashed page or post should not in any way conflict with a new page or post when it comes to slugs. In essence, a new page or post takes precedence over anything in the trash. After all, the page/post is in the trash for a reason.

By default, WordPress takes into account posts and pages that have been trashed when deciding if the slug for a new post is already in use. Obviously WordPress does not want to allow duplicate slugs because that could interfere with permalinks. The thinking behind WordPress's handling of the situation is that trashed posts/pages are still technically present, just inaccessible. It is possible that an author or admin would choose to restore a post from the trash, which WordPress feels should then occupy that same permalink as before it was trashed.

If what WordPress does is unclear, here's an example to help clarify things:

* WordPress ships with a page called "About" with the slug of "about". The page's URL would be http://yoursite/about/
* Let's say you trash that page and start a new page with the name "About".
* Due to a trashed page having the slug that would normally have been assigned to the new page ("about"), the new page gets the slug of "about-2", resulting in the page's URL being http://yoursite/about-2/

With this plugin, for this example, the new "About" page would get the slug "about" as one would hope.

That said, the plugin tries its best to restore untrashed posts to their original slug. The only time it fails to do so is if a new page or post has claimed the trashed post's original slug, in which case the untrashed post is assigned a new slug.

See the FAQ section for more insight into the plugin's functionality. See WP core [ticket #11863](http://core.trac.wordpress.org/ticket/11863) for discussion on the matter.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/no-slug-conflicts-with-trash/) | [Plugin Directory Page](http://wordpress.org/plugins/no-slug-conflicts-with-trash/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `no-slug-conflicts-with-trash.zip` inside the plugins directory for your site (typically `/wp-content/plugins/`). Or install via the built-in WordPress plugin installer)
2. Activate the plugin through the 'Plugins' admin menu in WordPress


== Frequently Asked Questions ==

= What happens if I trash a post and then restore it? =

The post retains its original slug, as was always the case.

= What happens if I trash a post, publish a new post with that same slug, then restore the original post? =

Because the trashed post's original slug is in use by a new post at the time it gets restored from the trash, the original post would use a reassigned slug. Once an untrashed post is given a reassigned slug, it will no longer have the ability to return to its original slug without manual intervention.

= What happens if I trash a post, publish a new post with that same slug, then trash the second post and restore the original post? =

Upon restoration, the original post will retain its original slug. The plugin keeps track when a trashed post's slug gets changed. It tries to restore the post's original slug if it isn't in use at the time the post gets untrashed.

= What slug gets assigned to a trashed post when a newer post wants to have the same slug? =

When a new post gets created, WordPress tries to determine if a conflict exists. If one does, WordPress appends "-" and then a number to the slug until a unique slug is found. Therefore, if "about" is taken, then it tries "about-2". If that's taken, then it tries "about-3" and so on. Rather than let WP assign the "about-2" to the new post, this plugin flips things and gives the new post "about" and the trashed post "about-2".

= Does this plugin include unit tests? =

Yes.


== Changelog ==

= 1.0.1 =
* Add `c2c_No_Slug_Conflicts_With_Trash::version()` to return version number for plugin (with unit test)
* Note compatibility through WP 3.8+
* Update copyright date (2014)
* Change donate link

= 1.0 =
* Initial public release


== Upgrade Notice ==

= 1.0.1 =
Trivial update: added version() function to return plugin's version number; noted compatibility through WP 3.8+

= 1.0 =
Initial public release.
