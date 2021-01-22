=== Plugin Name ===
Contributors: jonathanstevens, nielscor, nielsvermaut
Tags: automation, video creation, video
Requires at least: 4.9
Tested up to: 5.2
Stable tag: 5.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Enables your content and visitors' input to create engaging videos.

== Description ==

The Moovly Wordpress Plugin will allow you to generate videos from your existing/new posts, which can be used in the same post that is used to generate said video. We'll use your title, content (up to the <!---readmore---> line) and your featured image to fill a template(s) of your choice. If you want to spruce up your post with that video, use the [moovly-post-video] shortcode.

If you want to engage your visitors, you'll definitely love our [moovly-template] shortcode. We'll generate a form based on your template settings and when your visitor enters his/her information, they'll be presented a video with their content.

https://vimeo.com/278291940/9bf70bf578

## Warning: This plugin is still in beta

We have been busy working on this plugin for a couple of months and it has been verified in our environments. However, we cannot guarantee 100% that it will work on your website. If you want to install this **make sure to backup your website and if possible, run it on a test environment first**.

We recommend running it on PHP 7.3.

Our plugin does not work with Permalink setting set to "Plain".

== Installation ==

1. Make sure the Settings -> Permalinks 'Common settings' is not set to 'Plain'.
1. Take a backup of your website
1. Install the WordPress plugin
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Go to the Moovly -> Settings page and enter your Personal Access Token
1. Pick your post template in the list of templates.
1. Create a new post or save an existing post.

== Frequently Asked Questions ==

= I ran into some issues with the plugin. Can I get some help?
You can contact our technical support at api@moovly.com.

= What is the recommended installation
We suggest a PHP 7.3 installation, and the latest WordPress version. If you are not sure about these things, make sure
to contact your system administrator. If you are the system administrator and are not sure, you can always install a
PHPInfo plugin that will tell you all the information that is needed.

= Is this plugin free?
The plugin will always stay free. However, the rendering of videos amongst other things can become paying when the API
goes out of beta. To keep up to date with this, you can visit our developer portal (https://developer.moovly.com)

To create the Templates needed for this plugin to work, you currently do need a paid subscription for Moovly. A Free
Trial does not cover this functionality.

= I've updated and the plugin stopped working. What can I do?
We have some random behavior on older WordPress versions that when you update the plugin it randomly stops working for
your visitors. Disabling and re-activating this should solve it. This is also not unique to our plugin, but rather with
more JavaScript intensive plugins like ours.

= Does this work with Gutenberg
Yes it does. It should work with any editor in theory, but some third-party editors strip [] or "" tags, which is vital
for shortcodes in general. Ones that do this, are therefore not supported with the visual editor, but you can always
resort to the code editor, which will allow you to put the shortcode in.

= Do you support Internet Explorer =

No, we do no support Internet Explorer. It is unsupported by Microsoft and all active development has stopped. The
technologies we use do not work on Internet Explorer. However, if you do want to use this plugin, use a modern browser
like Chrome, Firefox or Safari.

If you want to make sure that your users upgrade, consider installing a plugin like WP Browser Update, which will notify
users of old browsers to upgrade as well.

== Changelog ==
= 1.0.151 =

* Publish template to youtube

= 1.0.149 =

* Add renders, templates & remaining credits shortcode

= 1.0.134 =

* Remove PHP 5.6 support

= 1.0.123 =

* Add screen and webcam recording to template
* Update template styling

= 1.0.120 =

* Fixed a bug where the video preview would ignore the size restrictions and break your layout.

= 1.0.118 =

* Fixed a bug where the status of a job was cached, making some jobs stuck on pending forever.

= 1.0.110 =

* As we have moved away from unsigned uploads, one of our upload calls got broken. If you cannot upload any videos,
please update to this version and your troubles will be resolved.

= 1.0.108 =

* Ensure compability with Microsoft Edge browser.

= 1.0.105 =

* Had to rollback some technical improvements since it broke on the WordPress plugin repository install process.
* Changed Template Form required field "Name" to "Video title" to avoid conflicts with template variables.

= 1.0.102 =

* Improve project fetching. If you are being throttled, we'll retry in 10 seconds to make sure that video gets shown.
* Humanize template variable names in forms, for easier reading
* Prevent shortcodes for unrendered projects to show up
* Add thumbnail to project view

= 1.0.100 =

* Fixed a bug where some WordPress themes broke the post-code shortcode by not setting the height of the HTML element
correctly.

= 1.0.95 =

* We have gone golden. A lot of bugfixes, improvements and tweaks from the 1.0 version and we now have
a stable plugin, which is live on the WordPress.org plugin repo! Huurray! We haven't added any other features
since the 1.0, but rather fleshed everything out.

= 1.0 =
* The first release of our plugin, including Templates, Projects and Post automation.
