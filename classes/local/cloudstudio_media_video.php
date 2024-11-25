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
 * Privacy provider implementation for media_cloudstudio.
 *
 * @package   media_cloudstudio
 * @copyright 2024 EadTech {@link https://www.eadtech.com.br}
 * @author    2024 Eduardo Kraus {@link https://www.eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace media_cloudstudio\local;

/**
 * Class cloudstudio_media_video
 *
 * @package media_cloudstudio
 */
class cloudstudio_media_video {

    /**
     * Function get_url
     *
     * @return mixed|string
     */
    public static function get_url() {
        $config = get_config("cloudstudio");
        $url = trim($config->urlcloudstidio);
        if (!preg_match('/^https?:/', $url)) {
            $url = "http://{$url}";
        }
        $url = parse_url($url, PHP_URL_HOST);

        if ($url != $config->urlcloudstidio) {
            set_config("urlcloudstidio", $url, "cloudstudio");
        }

        return $url;
    }

    /**
     * Call for get player code.
     *
     * @param int $cmid
     * @param string $identifier
     * @param string $safetyplayer
     *
     * @return string
     * @throws \dml_exception
     */
    public static function getplayer($cmid, $identifier, $safetyplayer) {
        global $USER, $OUTPUT, $PAGE;
        $config = get_config("cloudstudio");

        $payload = [
            "identifier" => $identifier,
            "matricula" => $cmid,
            "nome" => fullname($USER),
            "email" => $USER->email,
            "safetyplayer" => $safetyplayer,
        ];

        require_once(__DIR__ . "/jwt.php");
        $token = jwt::encode($config->token, $payload);

        $PAGE->requires->js_call_amd("media_cloudstudio/player", "resize", [$identifier]);
        return $OUTPUT->render_from_template("media_cloudstudio/player", [
            "tags" => [
                'sandbox="allow-scripts allow-same-origin allow-popups"',
                'allow=":encrypted-media; :picture-in-picture"',
                'frameborder="0" allowfullscreen',
                'style="width:100%;height:calc(100vw * .5625);"',
            ],
            "identifier" => $identifier,
            "token" => $token,
            "url" => self::get_url(),
        ]);
    }
}
