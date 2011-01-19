<?php
/**
 * THIS FILE IS AN IMPLEMENTATION STUB
 *
 * Plugins take care of small things, that may involve
 *
 * 1. Adding or (even) removing parts from the template
 * 2. Processing POST/GET requests
 * 3. Performing some database actions
 *
 * Plugins are like app-light: always called *after* an app has ran,
 * *before* the final template is rendered.
 *
 * Plugins provide url-paths. these will be matched against the router.
 * If a route matches for a plugin, the plugin will be dispatched.
 *
 * The dispatch function for the plugin will take care of internal routing.
 * Plugins may register helpers, namespaces. Propably this will be achieved
 * by writing these settings in the config.ini
 */

class Nano_Application_Plugin{

}

class Plugin_Caroussel{

    public function getRequest(){

    }

    public function postRequest(){

    }
}
