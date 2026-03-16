<?php

/**
 * Plugin class to set up custom Visual Composer element
 *
 * @link        https://flexxmarketing.nl/
 * @since       1.0.0
 *
 * @package     Flexx_Client_Plugin
 * @subpackage  Flexx_Client_Plugin/VC_Elements/Custom
 */

use Flexx_Client_Plugin\VC_Elements\Template\Flexx_VC_Element_Template;

// No direct access
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists( __NAMESPACE__ . '\Flexx_VC_Video_Block' ) ) {

    class Flexx_VC_Video_Block extends Flexx_VC_Element_Template {

        /**
         * Function: set the shortcode name
         *
         * @return string
         */

        function shortcode_name(): string {
            return 'flexx-video-block';
        }


        /**
         * Function: Flexx_VC_Global_Block constructor
         */

        public function __construct() {
            parent::__construct();
        }


        /**
         * Function: map shortcode into Visual Composer (for use in content elements list)
         *
         * @throws \Exception
         */

        public function vc_map_shortcode() {

            vc_map( array(
                "name"                    => "Video blok",
                "base"                    => $this->shortcode_name(),
                "class"                   => "flexx-custom-vc-item",
                "description"             => "Voegt een video blok toe.",
                "content_element"         => true,
                "show_settings_on_create" => true,
                "icon"                    => FLEXX_CP_IMG_URL . 'vc-icon.svg',
                "category"                => "Flexxmarketing",
                'admin_enqueue_js'        => FLEXX_CP_ASSETS_URL . '/js/vc-element-view.js',
                'admin_enqueue_css'       => FLEXX_CP_ASSETS_URL . '/css/vc-element-view.css',
                'js_view'                 => 'VcCustomElementView',
                "params"                  => array(

                    array(
                        "type"        => "dropdown",
                        "heading"     => "Type",
                        "param_name"  => "type",
                        "description" => "Kies het type video dat je wilt gebruiken.",
                        "group"       => "Algemeen",
                        "value"       => array(
                            "YouTube (standaard, aanbevolen)" => "youtube",
                            "Video uploaden"                  => "upload",
                        ),
                        "save_always" => true,
                        "admin_label" => true,
                    ),

                    array(
                        "type"        => "textfield",
                        "heading"     => "YouTube link",
                        "param_name"  => "youtube_video",
                        "description" => "Vul hier de YouTube link van de video in.",
                        "group"       => "Algemeen",
                        "dependency"  => array(
                            "element" => "type",
                            "value"   => array( "youtube" ),
                        )
                    ),

                    array(
                        "type"        => "attach_image",
                        "heading"     => "Afbeelding (optioneel)",
                        "param_name"  => "youtube_placeholder",
                        "description" => "Kies of upload een afbeelding die getoond moet worden voordat de video wordt afgespeeld (optioneel, wanneer je dit veld leeg laat wordt automatisch de afbeelding van YouTube gebruikt).",
                        "group"       => "Algemeen",
                        "admin_label" => true,
                        "dependency"  => array(
                            "element" => "type",
                            "value"   => array( "youtube" ),
                        )
                    ),

                    array(
                        "type"        => "file_picker",
                        "heading"     => "Bestand",
                        "param_name"  => "upload_video",
                        "description" => "Kies of upload de video.",
                        "group"       => "Algemeen",
                        "dependency"  => array(
                            "element" => "type",
                            "value"   => array( "upload" ),
                        )
                    ),

                    array(
                        "type"        => "attach_image",
                        "heading"     => "Afbeelding",
                        "param_name"  => "upload_placeholder",
                        "description" => "Kies of upload een afbeelding die getoond moet worden voordat de video wordt afgespeeld.",
                        "group"       => "Algemeen",
                        "dependency"  => array(
                            "element" => "type",
                            "value"   => array( "upload" ),
                        )
                    ),


                )

            ) );

        }


        /**
         * Function: register the shortcode's HTML output
         *
         * @param $atts
         * @param null $content
         *
         * @return mixed
         */

        public function register_shortcode( $atts, $content = null ) {

            $html = $type = $youtube_video = $youtube_placeholder = $upload_video = $upload_placeholder = '';

            extract( shortcode_atts( array(
                'type'                => '',
                'youtube_video'       => '',
                'youtube_placeholder' => '',
                'upload_video'        => '',
                'upload_placeholder'  => '',
            ), $atts ) );

            $video_html = '';

            if ( $type == 'youtube' ) {
                wp_enqueue_script( 'flexx-custom-youtube-embed' );

                // Get YouTube video ID
                preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $youtube_video, $matches );
                $youtube_video = $matches[1];

                // Set up YouTube thumbnail urls
                $maxres = "https://img.youtube.com/vi/" . $youtube_video . "/maxresdefault.jpg";
                $lowres = "https://img.youtube.com/vi/" . $youtube_video . "/hqdefault.jpg";

                // Check if custom placeholder is present
                if ( flexx_not_empty( $youtube_placeholder ) ) {
                    $image = flexx_srcset_image( $youtube_placeholder, 'flexx-extra-large', 'cover' );
                } else {
                    $curl = curl_init();
                    curl_setopt_array( $curl, array(
                        CURLOPT_URL            => $maxres,
                        CURLOPT_HEADER         => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_NOBODY         => true
                    ) );
                    $header = explode( "\n", curl_exec( $curl ) );
                    curl_close( $curl );

                    // Check if 'maxres' image exists, otherwise use 'hqdefault' image
                    if ( strpos( $header[0], '200' ) !== false ) {
                        $image = '<img src="' . $maxres . '" alt="">';
                    } else {
                        $image = '<img src="' . $lowres . '" alt="">';
                    }
                }
            } else {
                wp_enqueue_script( 'flexx-custom-video-embed' );

                // Get video file and thumbnail
                $upload_video = wp_get_attachment_url( $upload_video );
                $image        = flexx_srcset_image( $upload_placeholder, 'flexx-extra-large', 'cover' );
            }

            $html .= '
			<div data-inview class="video-block wpb_content_element">
				<div class="video-block__player">
					<div class="video-embed video-embed--' . $type . '" data-embed="' . ( $type == 'youtube' ? $youtube_video : $upload_video ) . '" ' . ( $type == 'youtube' ? 'id="player_' . $youtube_video . '"' : '' ) . '>
						<div class="video-embed__image">
							<div class="image-holder">' . $image . '</div>
						</div>
						<a data-play-embedded-video href="javascript:void(0);" class="video-embed__play-button">
							<svg viewBox="0 0 163.861 163.861" xmlns="http://www.w3.org/2000/svg"><path d="m34.857 3.613c-14.773-8.474-26.75-1.532-26.75 15.493v125.637c0 17.042 11.977 23.975 26.75 15.509l109.813-62.977c14.778-8.477 14.778-22.211 0-30.686z"/></svg>
						</a>
					</div>
				</div>
			</div>
			 ';

            return $html;

        }

    }

    new Flexx_VC_Video_Block();

}
