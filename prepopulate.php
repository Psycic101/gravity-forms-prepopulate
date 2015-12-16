<?php
/*
Plugin Name: Gravity Forms Pre-Populate
Plugin URI: http://mediacause.org
Description: A simple addon to prepopulate fields based on query parameters. 
Version: 0.1
Author: Asitha de Silva
Author URI: http://asithadesilva.com

------------------------------------------------------------------------
Copyright 2012-2013 Rocketgenius Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/


//------------------------------------------
if (class_exists("GFForms")) {
    GFForms::include_addon_framework();

    class GFPrePopulate extends GFAddOn {

        protected $_version = "0.1.1";
        protected $_min_gravityforms_version = "1.7.9999";
        protected $_slug = "prepopulate";
        protected $_path = "aprepopulate/prepopulate.php";
        protected $_full_path = __FILE__;
        protected $_title = "Gravity Forms Pre-Populate";
        protected $_short_title = "Prepopulate";

        public function init(){
            parent::init();
        }

        public function pre_init() {
            parent::pre_init();
            add_action("init", array($this, "prepopulate_frontend"), 10, 2);
        }

        public function init_admin(){
            parent::init_admin();
        }

        public function plugin_settings_fields() {
            return array(
                array(
                    "title"  => "Pre-Populate Settings",
                    "fields" => array(
                        array(
                            "name"    => "parameters",
                            "tooltip" => "Example: utm_source,utm_campaign,utm_medium",
                            "label"   => "Query Parameters",
                            "type"    => "text",
                            "class"   => "large",
                            "feedback_callback" => array($this, "update_prepop_options")
                        )
                    )
                ), 
                array(
                    "title"  => "Pre-Populate Settings",
                    "fields" => array(
                        array(
                            "name"    => "parameters",
                            "tooltip" => "Example: utm_source,utm_campaign,utm_medium",
                            "label"   => "Query Parameters",
                            "type"    => "text",
                            "class"   => "large",
                            "feedback_callback" => array($this, "update_prepop_options")
                        )
                    )
                )
            );
        }

        public function update_prepop_options($value){
            update_option('gravitypopulate_options', stripslashes($value));
            return true;
        }

        function prepopulate_frontend(){
            $gravitypopulate = explode(',', esc_attr(get_option('gravitypopulate_options')));
            $gravitypopulate = array_map('trim', $gravitypopulate);

            //stores GET varaible in cookies if available
            foreach ($gravitypopulate as $key) {
                if (isset($_GET[$key])){
                    setcookie($key, htmlspecialchars($_GET[$key], ENT_QUOTES), time() + 99999999, '/', NULL);
                }
            }

            if (isset($_COOKIE['HTTP_REFERER'])) {
                $_POST['input_-2']=htmlspecialchars($_COOKIE['HTTP_REFERER']);
            } elseif(isset($_SERVER['HTTP_REFERER']) and $_SERVER['HTTP_REFERER']!='') {
                setcookie('HTTP_REFERER', htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES), time() + 99999999, '/', NULL);
                $_POST['input_-2']=htmlspecialchars($_SERVER['HTTP_REFERER']);
            }

            foreach ($gravitypopulate as $key) {
                add_filter('gform_field_value_' . $key, function($arg) use ($key){
                    if (isset($_GET[$key])) {
                        return htmlspecialchars($_GET[$key], ENT_QUOTES);
                    } else if (isset($_COOKIE[$key])) {
                        return htmlspecialchars($_COOKIE[$key], ENT_QUOTES);
                    } else {
                        return '';
                    }
                }, -999);
            }
        }
    }

    new GFPrePopulate();
}