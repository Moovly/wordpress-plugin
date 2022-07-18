=== Plugin Name ===
Contributors: jonathanstevens, nielscor, nielsvermaut
Tags: automation, video creation, video
Requires at least: 4.9
Tested up to: 5.7
Stable tag: 1.0
Requires PHP: 7.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Enables your content and visitors' input to create engaging videos.

== Description ==

The Moovly Wordpress Plugin will allow you to generate videos from your existing/new posts, which can be used in the
same post that is used to generate said video. We'll use your title, content (up to the <!---readmore---> line) and your
featured image to fill a template(s) of your choice. If you want to spruce up your post with that video, use the `[moovly-post-video]` shortcode.

If you want to engage your visitors, you'll definitely love our `[moovly-template]` shortcode. We'll generate a form based on your template settings and when your visitor enters his/her information, they'll be presented a video with their content.

https://vimeo.com/278291940/9bf70bf578

We recommend running it on PHP 7.4 or higher. All currently supported PHP versions are compatible with the Moovly plugin.
The plugin maintains compability with older PHP versions, but we only support the [currently supported PHP versions](https://www.php.net/supported-versions.php).

The plugin does not work with Permalink setting set to "Plain".

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
We suggest a PHP 7.4 installation, and the latest WordPress version. If you are not sure about these things, make sure
to contact your system administrator. If you are the system administrator and are not sure, you can always install a
PHPInfo plugin that will tell you all the information that is needed.

= Is this plugin free?
The plugin will always stay free. However, certain Moovly API operations (video rendering, ...) are not free. The Moovly
API does come with a free trial. For more information, you can visit our developer portal (https://developer.moovly.com)

= I've updated and the plugin stopped working. What can I do?
We have some random behavior on older WordPress versions that when you update the plugin it randomly stops working for
your visitors. Disabling and re-activating this should solve it. This is also not unique to our plugin, but rather with
more JavaScript intensive plugins like ours.

= Does this work with Gutenberg
Yes it does. It should work with any editor in theory, but some third-party editors strip [] or "" tags, which is vital
for shortcodes in general. Ones that do this, are therefore not supported with the visual editor, but you can always
resort to the code editor, which will allow you to put the shortcode in.

= Which shortcodes are available?

``

    Shortcodes

    [moovly-post-video post-id={optional:wordpress post id} width={optional:html width} class={optional:css class}]

    [moovly-templates type={optional:template type} detailEndpoint={optional:string detailEndpoint} ] optional types: 'personal' | 'user-shared' | 'group-shared' | 'public'

    [moovly-template id={required:template id} width={optional:html width} class={optional:css class} publish-to-youtube={optional:1} ]

    [moovly-project id={required:template id} width={optional:html width} class={optional:css class}]

    [moovly-renders]

    [moovly-remaining-credits]

``

== Changelog ==
= 1.1.0 =
* Update Render list styling
* Update Template list styling
* Add WP-admin warnings

= 1.0.159 =

* Add `create-project=1` & `create-render=1` support in a template shortcode.

= 1.0.154 =

* Update documentation

= 1.0.151 =

* Automatically publish to YouTube by adding `publish-to-youtube=1` in a template shortcode. Make sure to link your account to YouTube via the Moovly Dashboard first.

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
