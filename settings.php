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
 * cloudstudio configuration settings.
 *
 * @package   media_cloudstudio
 * @copyright 2024 EadTech {@link https://www.eadtech.com.br}
 * @author    2024 Eduardo Kraus {@link https://www.eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once($CFG->libdir . "/resourcelib.php");

    $setting = new admin_setting_configtext("cloudstudio/url",
        get_string("url_title", "media_cloudstudio"),
        get_string("url_desc", "media_cloudstudio"), "eadtech.cloudstudio.com.br");
    $setting->set_validate_function(function ($url) {
        $url = trim($url);
        if (!preg_match('/^https?:/', $url)) {
            $url = "http://{$url}";
        }
        return parse_url($url, PHP_URL_HOST);
    });
    $settings->add($setting);

    $setting = new admin_setting_configtext("cloudstudio/token",
        get_string("token_title", "media_cloudstudio"),
        get_string("token_desc", "media_cloudstudio"), "");
    $settings->add($setting);

    $itensseguranca = [
        "none" => get_string("safety_none", "media_cloudstudio"),
        "id" => get_string("safety_id", "media_cloudstudio"),
    ];

    $infofields = $DB->get_records("user_info_field");
    foreach ($infofields as $infofield) {
        $itensseguranca["profile_{$infofield->id}"] = $infofield->name;
    }

    $setting = new admin_setting_configselect("cloudstudio/safety",
        get_string("safety_title", "media_cloudstudio"),
        get_string("safety_desc", "media_cloudstudio"), "id",
        $itensseguranca
    );
    $settings->add($setting);
}
