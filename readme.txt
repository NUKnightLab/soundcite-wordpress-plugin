=== Knight Lab SoundciteJS ===
Contributors: paulschreiber, KnightLab, joegermuska
Tags: soundcite, audio, soundcloud, knightlab
Requires at least: 4.7.5
Tested up to: 5.3.2
Stable tag: 0.9.1
License: Mozilla Public License 2.0
License URI: https://opensource.org/licenses/MPL-2.0

This plugin lets you easily use Knight Lab's SoundciteJS tool to embed audio clips on your WordPress site.

== Description ==

[SoundciteJS](https://soundcite.knightlab.com) is a tool from [Northwestern University Knight Lab](https://knightlab.northwestern.edu) to make it easy to embed audio in your webpage. Historically, SoundciteJS has not been easy to use on Wordpress.org sites because it uses custom attributes which are filtered out of posts.

To learn about using SoundciteJS in general, visit the [SoundciteJS](https://soundcite.knightlab.com) website. SoundciteJS can play audio hosted on SoundCloud, or audio which you upload using the WordPress Media Library, or any other audio files (MP3, AAC, Ogg Vorbis, or WAV) which can found with a URL.

To use SoundciteJS on your WordPress site, simply install this plugin. Then create clips at [soundcite.knightlab.com](https://soundcite.knightlab.com) and paste them into the "text" view of your post editor. You will be able to see your clips and edit their text in the "visual" WordPress editor, but to change the configuration values, you must use the "text" WordPress editor. While clips will show the SoundciteJS "play" button in the visual editor, playing them is not supported in the editor, although you should be able to play them in post preview mode.

This plugin supports two configuration values, which can be set in the Wordpress Settings controls, on the "Media" page.

* **Soundcloud Client ID:** If you use SoundCloud, you may want to register for a client ID. If you don't, your clips may occasionally become unplayable because of the amount of traffic against the SoundciteJS default SoundCloud client ID. See the [SoundCloud Developers website](https://developers.soundcloud.com/docs/api/guide) to register for a client ID.
* **Background Color:** If you choose, you may set the background color of your clips. All clips on a single page must have the same color, and the WordPress settings only support one custom color for your entire site.

== Installation ==

The best way to install this plugin is using the Wordpress plugin directory. Activate the plugin on the plugins page.
