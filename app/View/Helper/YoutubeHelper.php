 <?php
/**
 * @name       Youtube Helper
 * @author     Carly Marie
 * @version    1.4
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
*/

    App::uses('Helper', 'Html');
    class YoutubeHelper extends HtmlHelper {

        // An array of Youtube API's this helper will use
        var $apis = array(
            'image'  => 'http://i.ytimg.com/vi/%s/%s.jpg', // Location of youtube images
            'player' => 'http://www.youtube.com/v/%s?%s'   // Location of youtube player
        );

        // All these settings can be changed on the fly using the $player_variables option in the video function
        var $player_variables = array(
            'type'              => 'application/x-shockwave-flash',
            'class'             => 'youtube',
            'width'             => 624,          // Sets player width
            'height'            => 369,          // Sets player height
            'allowfullscreen'   => 'true',       // Gives script access to fullscreen (This is required for the fs player setting to work)
            'allowscriptaccess' => 'always',
            'wmode'             => 'transparent' // Ensures player stays under overlays such as lightbox/fancybox
        );

        // All these settings can be changed on the fly using the $player_settings option in the video function
        var $player_settings = array(
            'fs'        => true,   // Enables / Disables fullscreen playback
            'hd'        => true,   // Enables / Disables HD playback (Chromeless player does not support this setting)
            'egm'       => false,  // Enables / Disables advanced context (Right-Click) menu
            'rel'       => false,  // Enables / Disables related videos at the end of the video
            'loop'      => false,  // Loops video once its finished
            'start'     => 0,      // Start the video at X seconds
            'version'   => 3,      // For chromeless player set version to 3
            'autoplay'  => false,  // Automatically starts video when page is loaded
            'autohide'  => false,  // Automatically hides controls once the video begins
            'controls'  => true,   // Enables / Disables player controls (Chromeless Only)
            'showinfo'  => false,  // Enables / Disables information like the title before the video starts playing
            'disablekb' => false,  // Enables / Disables keyboard controls
            'theme'     => 'light' // Dark / Light style themes
        );

        // Outputs Youtube video image
        function thumbnail($url, $size = 'thumb', $options = array()) {

            // Sets the video ID for the image API
            $video_id = $this->getVideoId($url);

            // Humanized array of allowed image sizes
            $accepted_sizes = array(
                'thumb'  => 'default', // 120px x 90px
                'large'  => 0,         // 480px x 360px
                'thumb1' => 1,         // 120px x 90px at position 25%
                'thumb2' => 2,         // 120px x 90px at position 50%
                'thumb3' => 3          // 120px x 90px at position 75%
            );

            // Build url to image file
            $image_url = sprintf($this->apis['image'], $video_id, $accepted_sizes[$size]);

            return $this->image($image_url, $options);
        }

        // Outputs embedded Youtube player
        function video($url, $settings = array(), $variables = array()) {

            // Sets the video ID for the player API
            $video_id = $this->getVideoId($url);

            // Sets flash player settings if different than default
            $settings  = array_merge($this->player_settings, $settings);

            // Sets flash player variables if different than default
            $variables = array_merge($this->player_variables, $variables);

            // Sets src variable for a valid object
            $variables['src'] = sprintf($this->apis['player'], $video_id, http_build_query($settings));

            // Returns embedded video
            return $this->tag('object',
                $this->tag('param', null, array('name' => 'movie',             'value' => $variables['src'])).
                $this->tag('param', null, array('name' => 'allowFullScreen',   'value' => $variables['allowfullscreen'])).
                $this->tag('param', null, array('name' => 'allowscriptaccess', 'value' => $variables['allowscriptaccess'])).
                $this->tag('param', null, array('name' => 'wmode',             'value' => $variables['wmode'])).
                $this->tag('embed', null, $variables), array(
                    'width'  => $variables['width'],
                    'height' => $variables['height'],
                    'data'   => $variables['src'],
                    'type'   => $variables['type'],
                    'class'  => $variables['class']
                )
            );
        }

        // Extracts Video ID's from a Youtube URL
        function getVideoId($url = null){

            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            return (isset($params['v']) ? $params['v'] : $url);

        }
    }
?> 