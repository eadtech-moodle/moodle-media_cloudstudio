<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Main class for plugin "media_cloudstudio"
 *
 * @package   media_cloudstudio
 * @copyright 2024 EadTech {@link https://www.eadtech.com.br}
 * @author    2024 Eduardo Kraus {@link https://www.eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use media_cloudstudio\local\cloudstudio_media_video;


/**
 * Class media_cloudstudio_plugin
 */
class media_cloudstudio_plugin extends core_media_player_external {
    /**
     * List supported urls.
     *
     * @param array $urls
     * @param array $options
     *
     * @return array
     */
    public function list_supported_urls(array $urls, array $options = []) {
        $result = [];
        foreach ($urls as $url) {
            // If cloudstudio support is enabled, URL is supported.

            if (strpos($url->get_host(), ".cloudstudio.com.br") > 0) {
                $result[] = $url;
            } else if ($url->get_host() === "player.cloudstudio.com.br") {
                $result[] = $url;
            }
        }

        return $result;
    }

    /**
     * Embed external.
     *
     * @param moodle_url $url
     * @param string $name
     * @param int $width
     * @param int $height
     * @param array $options
     *
     * @return string
     */
    protected function embed_external(moodle_url $url, $name, $width, $height, $options) {
        global $USER, $COURSE;

        $config = get_config("cloudstudio");

        $safetyplayer = "";
        if ($config->safety && $config->safety != "none") {
            $safety = $config->safety;
            if (strpos($safety, "profile") === 0) {
                $safety = str_replace("profile_", "", $safety);
                $safetyplayer = $USER->profile->$safety;
            } else {
                $safetyplayer = $USER->$safety;
            }
        }

        preg_match('/\/\w+\/\w+\/([A-Z0-9\-\_]{3,255})/', $url->get_path(), $path);
        if (isset($path[0])) {
            $identifier = $path[1];
            return cloudstudio_media_video::getplayer($COURSE->id, $identifier, $safetyplayer);
        }

        preg_match('/\/\w+\/([A-Z0-9\-\_]{3,99})/', $url->get_path(), $path);
        if (isset($path[0])) {
            $identifier = $path[1];
            return cloudstudio_media_video::getplayer($COURSE->id, $identifier, $safetyplayer);
        }
    }

    /**
     * Supports Text.
     *
     * @param array $usedextensions
     *
     * @return mixed|string
     */
    public function supports($usedextensions = []) {
        return get_string("support_cloudstudio", "media_cloudstudio");
    }

    /**
     * Get embeddable markers.
     *
     * @return array
     */
    public function get_embeddable_markers() {
        $markers = [
            "cloudstudio.com.br",
            "player.cloudstudio.com.br",
        ];

        return $markers;
    }


    /**
     * Default rank
     *
     * @return int
     */
    public function get_rank() {
        return 2001;
    }

    /**
     * Checks if player is enabled.
     *
     * @return bool True if player is enabled
     */
    public function is_enabled() {
        $config = get_config("cloudstudio");
        if (!isset($config->urlcloudstidio[5])) {
            return false;
        }

        return true;
    }
}
