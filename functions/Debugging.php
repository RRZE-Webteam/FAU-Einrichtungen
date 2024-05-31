<?php

/**
 * @package WordPress
 * @subpackage FAU
 * @since FAU 2.5
 */

namespace RRZE\THEME\EINRICHTUNGEN;

defined('ABSPATH') || exit;

class Debugging {
   
    /*-----------------------------------------------------------------------
     * Gets an Variable, that can be an string, array or object and modifies
     *  its output in a more readable form     
     *----------------------------------------------------------------------*/ 
   public static function get_html_var_dump($input) {

         $out = self::get_dump_debug($input);
        
        /*
        $out = Debug::get_var_dump($input);
        $patterns_replacements = array( 
            '/=>[\r\n\s]+/'         => ' => ',            
            "/\s+bool\(true\)/"     => ' <span style="color:green">TRUE</span>,',
            "/\s+bool\(false\)/"    => ' <span style="color:red">FALSE</span>,',
            "/,([\r\n\s]+})/"       => "$1",
            "/\s+string\(\d+\)/"    => '',
            '/\[\"([a-z\-_0-9]+)\"\]/i' => '["<span style="color:#dd8800">$1</span>"]',
            '/\s\[(\d+)\]\s/'         => " <strong>[$1]</strong> ",
            '/\sarray\((\d+)\)\s/'         => " <strong>array($1)</strong> ",
            "/^\s+/"    => '',
        );
        $processed_string = preg_replace(array_keys($patterns_replacements), array_values($patterns_replacements), $out);
        

        if (!empty($processed_string)) {
            $out = $processed_string;
        }
         */
         return '<div class="var_dump">'.$out.'</div>';

    }
    
    /*-----------------------------------------------------------------------
     * gets a uri string with parameters and splits in parts for debug output
     *----------------------------------------------------------------------*/ 
    public static function get_html_uri_encoded($uri_string) {
         // Extrahiere den Query String aus der URL
        $query_string = parse_url($uri_string, PHP_URL_QUERY);

        // Splitten des Query Strings nach den &-Zeichen und Umwandlung in ein Array
        $params = explode('&', $query_string);
       
        $pattern = '/%[0-9A-Fa-f]{2}/';
        $out = "<code>$uri_string</code>";
        $out .= '<br>=&gt; URI Parts: <ul class="nolist">';
        $first = true;
        foreach ($params as $value) {
            if ($first) {
                $out .= '<li><span style="color:red">?</span>';
                $first = false;
            } else {
                $out .= '<li><span style="color:green">&</span>';
            }
            $rawoutstring = rawurldecode($value);
            $rawoutstring = preg_replace("/&/", '<br>&nbsp;&nbsp;<em style="color: blue;">$0</em>', $rawoutstring);
            $rawoutstring = preg_replace($pattern, '<em style="color: #ff8800;">$0</em>', $rawoutstring);
            $out .= '<code>'.$rawoutstring.'</code>';
            $out .= '</li>';
        }
        $out .= '</ul>';
      

        return $out;
    }

    /*-----------------------------------------------------------------------
    * prints var dump as variable, using old var_dump
    *----------------------------------------------------------------------*/ 
    public static function get_var_dump($input) { 
        ob_start(); 
        var_dump($input);
        return "\n" . ob_get_clean();
    }
    
    
    /*-----------------------------------------------------------------------
    * prints debugs as var dump, but in more usable format
    *----------------------------------------------------------------------*/ 
    public static function get_dump_debug($input, $collapse = false, $console = false, $htmlbreak = true) {
        
        
        $recursive = function($data, $level=0) use (&$recursive, $collapse, $console, $htmlbreak) {
            global $argv;
            $output = '';
            
                
            // $indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $indent = '';  
            $isTerminal = isset($argv);
            if ($console) {
                $isTerminal = true;
            }
            if (!$isTerminal && $level == 0 && !defined("DUMP_DEBUG_SCRIPT")) {
                define("DUMP_DEBUG_SCRIPT", true);

                $output .= '<script language="Javascript">function toggleDisplay(id) {';
                $output .=  'var state = document.getElementById("container"+id).style.display;';
                $output .=  'document.getElementById("container"+id).style.display = state == "block" ? "none" : "block";';
                $output .=  'document.getElementById("plus"+id).style.display = state == "block" ? "inline" : "none";';
                $output .=  '}</script>'."\n";
                $output .=  '<style>.dump_container { border-left: 1px dotted #000 !important; border-bottom: 1px dotted #000 !important; margin-left: 0; padding-left: 2em; overflow-x: auto;}'
                        . ' .dump_debug { display: inline; }'
                        . ' .dump_fieldname_col {font-family: monospace; min-width: 12em; display: inline-block;}'
                        . ' .dump_fieldname { padding: 0; font-family: monospace; color:#dd8800; background-color: inherit;}'
                        . ' .dump_fieldname_indent { font-family: monospace; }'
                        . ' .dump_fieldname_numeric { color: darkgreen !important; font-style: italic; }'
                        . ' .dump_value { font-family: Courier, Monospace; font-weight: bold; white-space: pre; }'
                        . ' .dump_empty_array { color: blue !important;}'
                        . ' .dump_nullvalue { color: blue !important; font-style: italic; }'
                        . ' .dump_typeinfo { margin-left: 1em; color:#666666 !important; font-size:0.9em; font-style:italic;} </style>'."\n";
                
            }

            $type = !is_string($data) && is_callable($data) ? "Callable" : ucfirst(gettype($data));
            $type_data = null;
            $type_color = null;
            $type_length = null;

            switch ($type) {
                case "String": 
                    $type_color = "";
                    $type_length = strlen($data);
                    $type_data = "\"" . htmlentities($data) . "\""; break;

                case "Double": 
                case "Float": 
                    $type = "Float";
                    $type_color = "#0099c5";
                    $type_length = strlen($data);
                    $type_data = htmlentities($data); break;

                case "Integer": 
                    $type_color = "darkgreen";
                    $type_length = strlen($data);
                    $type_data = htmlentities($data); break;

                case "Boolean": 
                    if ($data) {
                        $type_color = "green";
                    } else {
                        $type_color = "red";
                    }
                    $type_length = strlen($data);
                    $type_data = $data ? "TRUE" : "FALSE"; break;
                
                    

                case "NULL": 
                    $type_length = 0; 
                    break;

                case "Array": 
                    $type_length = count($data);
            }

            if (in_array($type, array("Object", "Array"))) {
                $notEmpty = false;

                foreach($data as $key => $value) {
                    if (!$notEmpty) {
                        $notEmpty = true;

                        if ($isTerminal) {
                            $output .=  $type . ($type_length !== null ? "(" . $type_length . ")" : "")."\n";

                        } else {
                            $id = substr(md5(rand().":".$key.":".$level), 0, 8);
                            $output .=  '<div class="dump_debug">';
                            $output .=  "<a href=\"javascript:toggleDisplay('". $id ."');\" style=\"text-decoration:none\">";
                            $output .=  "<strong style='color:blue'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</strong>";
                            $output .=  "</a>";
                            $output .=  "<span id=\"plus". $id ."\" style=\"color: red; display: " . ($collapse ? "inline" : "none") . ";\">&nbsp;&#10549;</span>";
                            if ($collapse) {
                                 $output .=  "<div id=\"container". $id ."\" class=\"dump_container\" style=\"display: none; \"><br>";
                            } else {
                                 $output .=  "<div id=\"container". $id ."\" class=\"dump_container\" style=\"display: block;\">";
                            }
                             $output .=  "\n";
                           
          
                        }

                        for ($i=0; $i <= $level; $i++) {
                            $output .=  $isTerminal ? "|    " : "$indent";
                        }

                        if ($isTerminal) {
                            $output .=  "\n";
                        }
    
                    }

                    for ($i=0; $i <= $level; $i++) {
                        $output .=  $isTerminal ? "|    " : "$indent";
                    }
                    
                    if ($isTerminal) {
                        $output .=  "[" . $key . "] => ";
                    } else {
                        $output .= "<span class=\"dump_fieldname_col\">";
                        if (is_numeric($key)) {
                            $output .= "[<code class=\"dump_fieldname dump_fieldname_numeric\"'>" . $key . "</code>]";
                        } else {
                            $output .= "[\"<code class=\"dump_fieldname\"'>" . $key . "</code>\"]";                   
                        }
                        $output .= '</span>';
                        $output .= " &#8658; ";
                    }

                    $output .= call_user_func($recursive, $value, $level+1);
                }

                if ($notEmpty) {
                    for ($i=0; $i <= $level; $i++) {
                        $output .=  $isTerminal ? "|    " : "$indent";
                    }

                    if (!$isTerminal) {
                        $output .=  "</div>";
                        $output .=  "</div>";
                    }

                } else {
                    $output .=  $isTerminal ? 
                            $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "  " : 
                            "<span class=\"dump_value dump_empty_array\">" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>&nbsp;&nbsp;";
                }

            } else {
                if ($type_data != null) {
                    if (empty($type_color)) {
                         $output .=  $isTerminal ? $type_data : "<span class=\"dump_value\">" . $type_data . "</span>";
                    } else {
                         $output .=  $isTerminal ? $type_data : "<span class=\"dump_value\" style='color:" . $type_color . "'>" . $type_data . "</span>";
                    }
                    $output .=  $isTerminal ? 
                        $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "  " : 
                        " <span class=\"dump_typeinfo\">" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>";
                } else {
                    
                    if ($isTerminal) {
                         $output .=  "NULL"; 
                    } else {
                        $output .= '<span class="dump_nullvalue">NULL</span>';
                    }
                     
                }
              

                
            }

            if ($isTerminal) {
                 $output .=  "\n";
            } else {
                 if ($htmlbreak) {
                    $output .=  "<br />\n";   
                 } else {
                    $output .=  "\n";
                 }
            }
            return $output;
        };

        $output = call_user_func($recursive, $input);
        
        return $output;
    }
    
    /*-----------------------------------------------------------------------
    * prints a variable as notice string
    *----------------------------------------------------------------------*/ 
    public static function get_notice($text) {
        if (!isset($_GET['debug'])) {
            return;
        }
        if (!self::isDevUser()) {
            return;
        }
        return '<div class="alert clearfix clear alert-info">'.$text.'</div>';

    }
    
    /*-----------------------------------------------------------------------
    * prints a message on browser console
    *----------------------------------------------------------------------*/ 
    public static function console_log(string $msg = '', float $tsStart = 0) {
        if (isset($_GET['debug'])) {
            
            if (!self::isDevUser()) {
                return;
            }
            
            $msg .= ' execTime: ' . sprintf('%.2f', microtime(true) - $tsStart) . ' s';
            echo '<script>console.log(' . json_encode($msg, JSON_HEX_TAG) . ');</script>';
        }
    }
    
    /*-----------------------------------------------------------------------
    * Log to standard error/debug log
    *----------------------------------------------------------------------*/ 
    public static function log(string $level,  string $msg = '', string $location = '') {
        global $defaultoptions;
        if (!$defaultoptions['debugmode']) {
             return;
        }
        if (is_array($msg) || is_object($msg)) {
            $msg = print_r($msg, true);
        }
        switch (strtolower($level)) {
            case 'e':
            case 'error':
                $level = 'Error';
                break;
            case 'i':
            case 'info':
                $level = 'Info';
                break;
            case 'd':
            case 'debug':
                $level = 'Debug';
                break;
            default:
                $level = 'Info';
        }
        $logmessage = date("[d-M-Y H:i:s \U\T\C]") . " WP $level: ";
        if ($location) {
             $logmessage .= $location. ': '; 
        }
        $logmessage .= $msg;

        error_log($logmessage);
           

       return;
    }
    

    /*-----------------------------------------------------------------------
    * check for privilegued user for getting debug outputs
    *----------------------------------------------------------------------*/ 
    private static function isDevUser() { 
        if (class_exists('\RRZE\Settings\Helper')) {
            // rrze-settings is used
            return \RRZE\Settings\Helper::isRRZEAdmin();
        } elseif (is_user_logged_in()) {
            return true;
        }

        $knownhostsuser = [
            '/localhost/i'
        ];
         $knownhostadresses = [
            '/^192\.168\./i',
            '/^127\.0\.0/i',
            '/^10\.10\./i',
            '/^10\.11\./i',
         ];

        $found = false;	  
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $remip = $_SERVER['REMOTE_ADDR'];

            foreach ($knownhostadresses as $regexp) {
                if (preg_match($regexp,$remip)) {
                    $found = true;
                    break;
                }
            }
        }
        if (isset($_SERVER['REMOTE_HOST'])) {
            $remotehost = 	 $_SERVER['REMOTE_HOST']; 
            foreach ($knownhostsuser as $regexp) {
                if (preg_match($regexp,$remotehost)) {
                    $found = true;
                    break;
                }
            }
        }
        return $found;
    }

}