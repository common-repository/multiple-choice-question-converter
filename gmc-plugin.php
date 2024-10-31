<?php
/*
Plugin Name: Generate Quiz Using Short code
Plugin URI: http://gmcwebtech.wordpress.com
Description: With this plugin you can create stylized quiz / multiple choice question in your post using easy to remember short codes.
Version: 1.0.0
Author: Santosh Kalra
Author URI: 
*/

function gmc_quiz_box()
{
	$gmc_quiz_box = get_option('gmc_quiz_box');
	if($gmc_quiz_box=='1')
	{
        	if ( !defined('WP_CONTENT_URL') ) define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
	        $plugin_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));
        	echo '<link rel="stylesheet" href="'.$plugin_url.'/question-maker-default-style.css"'.' type="text/css" media="screen" />';
	        echo '<script src="'.$plugin_url.'/try-question.js"'.' type="text/javascript" /></script>';
	}
}

function gmc_question_shortcode( $atts, $content = null )
{
	//empty question
	$question = "";

	//this is random question id
	$q_id = "".rand(1000,9999).rand(1000,9999).rand(1000,9999).rand(1000,9999);

	$content = str_replace("\r","\n",$content);

	$content = strip_tags(trim($content));

	for($i=1;$i<=10; $i++)
	{
		$content = str_replace("\n\n","\n",$content);
	}

	//$output = "<div>".$content."</div>";

	$output = "";

	//now again make a fresh array
	$all_lines = explode("\n", trim($content) );

	$all_lines = array_filter($all_lines);

	$wrong_options = array();

	$right_options = array();

	$length = count($all_lines);

	$question = $all_lines[0];

	$right = $all_lines[1];

	$all_options = array();

	for($i=2;$i<$length;$i++)
	{
		$opt = trim($all_lines[$i]);
		$all_options[] = "<div class=gmc_options_padding><option class=gmc_options value=wrong>". $opt ."</option></div>";
	}

	$all_options[] = "<div class=gmc_options_padding><option class=gmc_options id=right_option_" . $q_id . " value=right>". trim($right) ."</option></div>";

	shuffle($all_options);

	$output .= "<div class=gmc_question_template>";

	$question = "<div class=gmc_question id=question>" . trim($question) . "</div>";

	$output = $output . $question;

	$output = $output .  "<div class=gmc_question><select id=\"question_options_$q_id\" size=6 class=gmc_options>";

	$output = $output .  "<div class=gmc_options_padding><option class=gmc_options value=\"not_attempted\" selected >Not Attempted</option></div>";

	foreach( $all_options as $option)
	{
		$output = $output . $option;
	}

	$output = $output . "</select></div>";

	$output = $output . "<div class=gmc_options_padding><input noclass=gmc_options type=button onclick=\"javascript: gmc_try_question('$q_id');\" value=Submit></input></div>";

	$output = $output . "<div><textarea disabled=true id=\"message_$q_id\" style=\"width: 100%; height: 50px;\">Message</textarea></div>";

	$output = $output . "</div>";

	return $output;
}
add_shortcode( 'gmc-question', 'gmc_question_shortcode' );


function fetch_page_shortcode( $atts, $content = null )
{
	return file_get_contents_curl( $atts["url"] );
}
add_shortcode( 'fetch-page', 'fetch_page_shortcode' );

function activate_gmc_quiz_box()
{
        add_option('gmc_quiz_box','1','Activate the Plugin');
}

function deactivate_gmc_quiz_box()
{
    delete_option('gmc_quiz_box');
}

add_action('wp_head', 'gmc_quiz_box');

register_activation_hook(__FILE__,'activate_gmc_quiz_box');
register_deactivation_hook(__FILE__,'deactivate_gmc_quiz_box');


function file_get_contents_curl($url)
{
	// create curl resource 
	$ch = curl_init(); 

	// spoofing FireFox 2.0
	$useragent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/3.0.0.1";

	// set user agent
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

	// set url 
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);

	$output = str_replace("\r"," ",$output);

	$output = str_replace("\n"," ",$output);

	$output = str_replace("\t"," ",$output);

	return $output;

}

?>