# panopress-lite
A WordPress plugin. It displays 360° images as they are output by PTGui or Marzipano for use on websites. The plugin is extremely small and works with < iframe> integration.

* The plugin serves as a replacement for the outdated Panopress plugin based on Flash technology.
* It offers a settings page under “Settings → PanoPress Lite”
* Storage of the values in the WordPress options database
* Use of these settings in the shortcode [pano file="..."]

## Install Plugin
* WordPress Admin → Plugins → “Install” → “Upload plugin” → Upload ZIP.
* Activate the plugin.
* In the WordPress admin, go to:
    * Settings → PanoPress Lite
    * Enter your desired base_url, margin-left, max-width and ratio.
* That's it!

## Plugin use
<table class="has-fixed-layout"><thead><tr><th>Attribut</th><th>Effect</th><th>Example</th></tr></thead><tbody><tr><td><code>file</code></td><td>Path to the tour (relative to base_url)</td><td><code>file="tour1/index.html"</code></td></tr><tr><td><code>ratio</code></td><td>Aspect ratio or fixed height</td><td><code>ratio="4:3"</code> oder <code>800px</code></td></tr><tr><td><code>max_width</code></td><td>Maximum width</td><td><code>max_width="90%"</code></td></tr><tr><td><code>margin_left</code></td><td>	CSS value for left margin</td><td><code>margin_left="auto"</code></td></tr></tbody></table>

* In your wordpress blog entry create a shortcode block.
* The settings are automatically applied to all pages that use the shortcode <code>[pano file="..."]</code>.
* You may overrule the setting per entry. Example of how to use the shortcode: <br>
<code>[pano file="panorama.jpg" ratio="16:9" max_width="100%" margin_left="auto"]</code>

## Download
* See Releases at the right and click it. 
* Download the zip file.

## Create your own Wordpress plugin
* place panopress-lite.php in a folder named panopress-lite. 
* Edit whatever you like to change.
* zip the folder (no compression!)
* install the zip package.