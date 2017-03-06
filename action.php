<?php
/**
 * DokuWiki Plugin namespacemessage (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Christoph Ziehr <info@einsatzleiterwiki.de>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_namespacemessage extends DokuWiki_Action_Plugin {


    public function register(Doku_Event_Handler $controller) {

       $controller->register_hook('TPL_ACT_RENDER', 'BEFORE', $this, 'handle_tpl_act_render');
   
    }


    public function handle_tpl_act_render(Doku_Event &$event, $param) {

        // make the global DokuWiki-variables usable in this function
        global $ACT;
        global $INFO;

        // Only show the message when the page is displayed, but not in admin for example
        if ($ACT !== 'show') {
            return;
        }

        // Save the first-level-namespace in the variable $actual_ns
        $actual_ns = strtok ( $INFO["namespace"] , (':') );

        // Fetch the namespaces in which the message shouldn't shown from the configuration and save them in an array
        $not_in_namespaces_array = explode(" ", $this->getConf('not_in_namespaces'));

        // Compare each namespace from the array with the actual first-level-namespace of the page
        foreach ($not_in_namespaces_array as $ns_to_compare) {
            // Replace "rootns" from configuration for the root namespace with "nothing" to make it comparable
            $ns_to_compare = str_replace("rootns", "", $ns_to_compare);
            // If the actual namespace matches the configured, finish the function without displaying the message
            if($actual_ns == $ns_to_compare) {
                return;
            }
        }
        // Display the message
        echo $this->getConf('message');
    }

}

// vim:ts=4:sw=4:et:
