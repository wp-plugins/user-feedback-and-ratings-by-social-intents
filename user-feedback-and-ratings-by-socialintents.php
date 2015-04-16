<?php
/*
Plugin Name: Feedback Lightbox by Social Intents
Plugin URI: http://www.socialintents.com
Description: Gather Instant Feedback, Engage Visitors, and Quickly Resolve Support Questions.  Private, Unbiased, and Customizable Visitor Feedback.
Version: 1.0.7
Author: Social Intents
Author URI: http://www.socialintents.com/
*/

$siuf_domain = plugins_url();
add_action('init', 'siuf_init');
add_action('admin_notices', 'siuf_notice');
add_filter('plugin_action_links', 'siuf_plugin_actions', 10, 2);
add_action('wp_footer', 'siuf_insert',4);

function siuf_init() {
    if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'siuf_add_settings_page');
    }
}

function siuf_insert() {
    global $current_user;

    if(strlen(get_option('siuf_widgetID')) == 32 && get_option('siuf_tab_text')) {
	get_currentuserinfo();
	echo("\n\n<!-- Social Intents Customization -->\n");
        echo("<div id=\"social-intents-tab\" class=\"social-intents-tab\" style=\"visibility:hidden;\"></div>\n");
        echo("<div id=\"socialintents-offer\"></div>\n");
        echo("<script type=\"text/javascript\">\n");
        echo("var socialintents_vars_feedback ={\n");
        echo("'widgetId':'".get_option('siuf_widgetID')."',\n");
        echo("'tabLocation':'".get_option('siuf_tab_placement')."',\n");
        echo("'tabText':'".get_option('siuf_tab_text')."',\n");
        echo("'type':'feedback',\n");
        echo("'tabColor':'".get_option('siuf_tab_color')."',\n");
        echo("'popupHeight':'".get_option('siuf_popup_height')."',\n");
echo("'popupWidth':'".get_option('siuf_popup_width')."',\n");
echo("'roundedCorners':'".get_option('siuf_rounded_corners')."',\n");
echo("'backgroundImg':'".get_option('siuf_background_img')."',\n");
        echo("'tabWidth':'240px',\n");
        echo("'marginRight':'60px', \n");
        echo("'headerTitle':'".get_option('siuf_header_text')."'\n");
        echo("};\n");
        echo("(function() {function socialintents(){\n");
        echo("    var siJsHost = ((\"https:\" === document.location.protocol) ? \"https://\" : \"http://\");\n");
        echo("    var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = siJsHost+'www.socialintents.com/api/feedback/socialintents.js';\n");
        echo("    var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);};\n");
        echo("if (window.attachEvent)window.attachEvent('onload', socialintents);else window.addEventListener('load', socialintents, false);})();\n");
        echo("</script>\n");
    }
}

function siuf_notice() {
    if(!get_option('siuf_widgetID')) echo('<div class="error"><p><strong>'.sprintf(__('Your User Feedback and Ratings Plugin is disabled. Please go to the <a href="%s">plugin settings</a> to enter a valid widget key.  Find your widget key by logging in at www.socialintents.com and selecting your Widget General Settings.  New to socialintents.com?  <a href="http://www.socialintents.com">Sign up for a Free Trial!</a>' ), admin_url('options-general.php?page=user-feedback-and-ratings-by-socialintents')).'</strong></p></div>');
}

function siuf_plugin_actions($links, $file) {
    static $this_plugin;
    if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if($file == $this_plugin && function_exists('admin_url')) {
        $settings_link = '<a href="'.admin_url('options-general.php?page=user-feedback-and-ratings-by-socialintents').'">'.__('Settings', $siuf_domain).'</a>';
        array_unshift($links, $settings_link);
    }
    return($links);
}

function siuf_add_settings_page() {
    function siuf_settings_page() {
        global $siuf_domain ?>
<div class="wrap">
        <?php screen_icon() ?>
    <h2><?php _e('User Feedback and Ratings by Social Intents', $siuf_domain) ?></h2>
    <div class="metabox-holder meta-box-sortables ui-sortable pointer">
        <div class="postbox" style="float:left;width:30em;margin-right:10px">
            <h3 class="hndle"><span><?php _e('User Feedback Settings', $siuf_domain) ?></span></h3> 
            <div class="inside" style="padding: 0 10px">
                <form id="saveSettings" method="post" action="options.php">
                    <p style="text-align:center"><?php wp_nonce_field('update-options') ?>
			<a href="http://www.socialintents.com/" title="Feedback and Social Widgets that help grow your business">
			<?php echo '<img src="'.plugins_url( 'socialintents.png' , __FILE__ ).'" height="150"  "/> ';?></a></p>

                    <p><label for="siuf_widgetID"><?php printf(__('Enter your Widget Key below to activate the plugin.  If you don\'t have your key but have already signed up, you can <a href=\'http://www.socialintents.com\' target=\'_blank\'>login here</a> to grab your key under your widget --> your code snippet..<br>', $siuf_domain), '<strong><a href="http://www.socialintents.com/" title="', '">', '</a></strong>') ?></label><br />
			<input type="text" name="siuf_widgetID" id="siuf_widgetID" placeholder="Your Widget Key" value="<?php echo(get_option('siuf_widgetID')) ?>" style="width:100%" />
		    
			<input type="hidden" name="siuf_tab_text" id="siuf_tab_text" value="Leave Feedback"/>
                    <p class="submit" style="padding:0"><input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="siuf_widgetID,siuf_tab_text" />
                        <input type="submit" name="siuf_submit" id="siuf_submit" value="<?php _e('Save Settings', $siuf_domain) ?>" class="button-primary" /> 
			</p>
                 </form>
            </div>
        </div>
        <div class="postbox" style="float:left;width:38em">
            <h3 class="hndle"><span id="siuf_noAccountSpan"><?php _e('No Account?  Sign up for a Free Social Intents Trial!', $siuf_domain) ?></span></h3>
            <div id="siuf_register" class="inside" style="padding: -30px 10px">			
		<p><?php printf(__('Social Intents is a user  feedback and social widgets platform that helps you engage visitors and grow your business with simple, effective plugins.
			Please visit %1$sSocial Intents%2$ssocialintents.com%3$s to 
				learn more.', $siuf_domain), '<a href="
http://www.socialintents.com/" title="', '">', '</a>') ?></p>
			<b>Sign Up For a Free Trial Now!</b> (or register directly on our site at <a href="http://www.socialintents.com" target="_blank">Social Intents</a>)<br>
			<input type="text" name="siuf_email" id="siuf_email" value="<?php echo(get_option('admin_email')) ?>" placeholder="Your Email" style="width:50%;margin:3px;" />
			<input type="text" name="siuf_name" id="siuf_name" value="<?php echo(get_option('user_nicename')) ?>" placeholder="Your Name" style="width:50%;margin:3px;" />
			<input type="password" name="siuf_password" id="siuf_password" value="" placeholder="Your Password" style="width:50%;margin:3px;" />
			<br><input type="button" name="siuf_inputRegister" id="siuf_inputRegister" value="Register" class="button-primary" style="margin:3px;" /> 
			
			
               
            </div>
	    <div id="siuf_registerComplete" class="inside" style="padding: -20px 10px;display:none;">
		<p>View user feedback responses and customize text, language, feedback categories, and CSS styles on our website at <a href='http://www.socialintents.com'>www.socialintents.com</a>
		</p><form id='saveDetailSettings' method="post" action="options.php">
		<?php wp_nonce_field('update-options') ?>
		<input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="siuf_background_img, siuf_rounded_corners, siuf_popup_width, siuf_popup_height, siuf_tab_text,siuf_tab_placement,siuf_header_text,siuf_intro_text,siuf_rating_text,siuf_feedback_text,siuf_time_on_page,siuf_tab_color" />
		<table width="100%" >
		<tr><td width="25%">Tab Text: </td>
		<td >
		<?php
		if(get_option('siuf_tab_text') ) {
     		?>
     			<input type="text" name="siuf_tab_text" id="siuf_tab_text" value="<?php echo(get_option('siuf_tab_text')) ?>" style="margin:3px;width:100%;" />
		
    		<?php 
			} else {
   		?>
			<input type="text" name="siuf_tab_text" id="siuf_tab_text" value="Leave Feedback" style="margin:3px;width:100%;" />
		<?php 
			}
   		?>
		</td>
		</tr>
		<tr><td width="25%">Tab Color: </td>
		<td >
		<?php
		if(get_option('siuf_tab_color') && get_option('siuf_tab_color') != '') {
     		?>
     			<input type="text" name="siuf_tab_color" id="siuf_tab_color" value="<?php echo(get_option('siuf_tab_color')) ?>" style="margin:3px;width:100%;" />
		
    		<?php 
			} else {
   		?>
			<input type="text" name="siuf_tab_color" id="siuf_tab_color" value="#00AEEF" style="margin:3px;width:100%;" />
		<?php 
			}
   		?>
		</td>
		</tr>
		<tr><td>Tab Placement: </td><td>
		<?php 
		if(get_option('siuf_tab_placement') && get_option('siuf_tab_placement') == 'bottom') {
     		?>
     		<select id="siuf_tab_placement" name="siuf_tab_placement">
			<option value="bottom" selected>Bottom</option>
			<option value="top">Top</option>
			<option value="hide">Hide</option>
		</select> 	
    		<?php 
			} else if(get_option('siuf_tab_placement') == 'top') {
   		?>
		<select id="siuf_tab_placement" name="siuf_tab_placement">
			<option value="bottom">Bottom</option>
			<option value="top" selected>Top</option>
			<option value="hide">Hide</option>
		</select> 
		<?php 
			} else if(get_option('siuf_tab_placement') == 'hide') {
   		?>
		<select id="siuf_tab_placement" name="siuf_tab_placement">
			<option value="bottom">Bottom</option>
			<option value="top">Top</option>
			<option value="hide"  selected>Hide</option>
		</select> 
		<?php 
			} else {
   		?>
		<select id="siuf_tab_placement" name="siuf_tab_placement">
			<option value="bottom">Bottom</option>
			<option value="top">Top</option>
			<option value="hide">Hide</option>
		</select> 
		<?php 
			}
   		?>
		
		</td></tr>
		<tr><td>When To Show Auto Popup: </td><td>
		<?php 
		if(get_option('siuf_time_on_page') && get_option('siuf_time_on_page') == '0') {
     		?>
     		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0" selected>Immediately</option>
			<option value="-1" >Don't Auto Pop</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 	
    		<?php 
			} else if(get_option('siuf_time_on_page') == '-1') {
   		?>
		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0">Immediately</option>
			<option value="-1" selected>Don't Auto Pop</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('siuf_time_on_page') == '10') {
   		?>
		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0">Immediately</option>
			<option value="-1">Don't Auto Pop</option>
			<option value="10"  selected>10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select>  
		<?php 
			} else if(get_option('siuf_time_on_page') == '15') {
   		?>
		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0">Immediately</option>
			<option value="-1">Don't Auto Pop</option>
			<option value="10">10 Seconds</option>
			<option value="15"  selected>15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('siuf_time_on_page') == '20') {
   		?>
		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0">Immediately</option>
			<option value="-1">Don't Auto Pop</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20"   selected>20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('siuf_time_on_page') == '30') {
   		?>
		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0">Immediately</option>
			<option value="-1">Don't Auto Pop</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20" >20 Seconds</option>
			<option value="30"  selected>30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('siuf_time_on_page') == '45') {
   		?>
		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0">Immediately</option>
			<option value="-1">Don't Auto Pop</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20" >20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45"  selected>45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('siuf_time_on_page') == '60') {
   		?>
		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0">Immediately</option>
			<option value="-1">Don't Auto Pop</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60"  selected>60 Seconds</option>
		</select>  
		<?php 
			} else {
   		?>
		<select id="siuf_time_on_page" name="siuf_time_on_page">
			<option value="0">Immediately</option>
			<option value="-1" selected>Don't Auto Pop</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select>  
		<?php 
			}
   		?>
		
		</td></tr>
		<tr><td>Header Text: </td><td>
		<?php 
		if(get_option('siuf_header_text') && get_option('siuf_header_text') != '') {
     		?>
     		<input type="text" name="siuf_header_text" id="siuf_header_text" value="<?php echo(get_option('siuf_header_text')) ?>" style="margin:3px;width:100%;" />
		
    		<?php 
			} else {
   		?>
		<input type="text" name="siuf_header_text" id="siuf_header_text" value="Have Feedback or a Question For Us?" style="margin:3px;width:100%;" />
		<?php 
			}
   		?>
		</td></tr>
		<tr><td>Intro Text: </td>
		<td>
		<?php 
		if(get_option('siuf_intro_text') && get_option('siuf_intro_text') != '') {
     		?>
     		<textarea rows="2" name="siuf_intro_text" id="siuf_intro_text" style="margin:3px;width:100%;"><?php echo(get_option('siuf_intro_text')) ?></textarea>
		<?php 
			} else {
   		?>
		<textarea rows="2" name="siuf_intro_text" id="siuf_intro_text" style="margin:3px;width:100%;">Hello, we'd love to hear your thoughts about our Website</textarea>
		<?php 
			}
   		?>
		</td></tr>

		<tr><td>Rating Question: </td>
		<td>
		<?php 
		if(get_option('siuf_rating_text') && get_option('siuf_rating_text') != '') {
     		?>
     		<textarea rows="2" name="siuf_rating_text" id="siuf_rating_text" style="margin:3px;width:100%;"><?php echo(get_option('siuf_rating_text')) ?></textarea>
		<?php 
			} else {
   		?>
		<textarea rows="2" name="siuf_rating_text" id="siuf_rating_text" style="margin:3px;width:100%;">How likely would you be to recommend us to your friends?</textarea>
		<?php 
			}
   		?>
		</td></tr>

		<tr><td>Prompt for Feedback: </td>
		<td>
		<?php 
		if(get_option('siuf_feedback_text') && get_option('siuf_feedback_text') != '') {
     		?>
     		<textarea rows="2" name="siuf_feedback_text" id="siuf_feedback_text" style="margin:3px;width:100%;"><?php echo(get_option('siuf_feedback_text')) ?></textarea>
		<?php 
			} else {
   		?>
		<textarea rows="2" name="siuf_feedback_text" id="siuf_feedback_text" style="margin:3px;width:100%;">How can we improve our website?  Do you have ideas, questions, or need help?  Let us know!</textarea>
		<?php 
			}
   		?>
		</td></tr>
<tr><td>Popup Height: </td>
		<td>
		<?php 
		if(get_option('siuf_popup_height') && get_option('siuf_popup_height') != '') {
     		?>
     		<input type="text" name="siuf_popup_height" id="siuf_popup_height" value="<?php echo(get_option('siuf_popup_height')) ?>" style="margin:3px;width:100%;" />
		<?php 
			} else {
   		?>
		<input type="text" name="siuf_popup_height" id="siuf_popup_height" value="120px" style="margin:3px;width:100%;" placeholder="Height in Pixels - 150px"/>
		<?php 
			}
   		?>
		</td></tr>
		<tr><td>Popup Width: </td>
		<td>
		<?php 
		if(get_option('siuf_popup_width') && get_option('siuf_popup_width') != '') {
     		?>
     		<input type="text" name="siuf_popup_width" id="siuf_popup_width" value="<?php echo(get_option('siuf_popup_width')) ?>" style="margin:3px;width:100%;" />
		<?php 
			} else {
   		?>
		<input type="text" name="siuf_popup_width" id="siuf_popup_width" value="500px" style="margin:3px;width:100%;" placeholder="Width in Pixels - 560px"/>
		<?php 
			}
   		?>
		</td></tr>
		<tr><td>Background Image: </td>
		<td>
		<?php 
		if(get_option('siuf_background_img') && get_option('siuf_background_img') != '') {
     		?>
     		<input type="text" name="siuf_background_img" id="siuf_background_img" value="<?php echo(get_option('siuf_background_img')) ?>" style="margin:3px;width:100%;" />
		<?php 
			} else {
   		?>
		<input type="text" name="siuf_background_img" id="siuf_background_img" value="" style="margin:3px;width:100%;" placeholder="Absolute URL:  https://www.yourdomain.com/bg.jpg"/>
		<?php 
			}
   		?>
		</td></tr>
		<tr><td>Rounded Corners: </td>
		<td>
		<?php 
		if(get_option('siuf_rounded_corners') && get_option('siuf_rounded_corners') == 'yes') {
     		?>
     		<select id="siuf_rounded_corners" name="siuf_rounded_corners">
			<option value="yes" selected>Yes</option>
			<option value="no">No</option>
		</select> 	
    		<?php 
			} else if(get_option('siuf_rounded_corners') == 'no') {
   		?>
		<select id="siuf_rounded_corners" name="siuf_rounded_corners">
			<option value="yes">Yes</option>
			<option value="no" selected>No</option>
		</select>
		<?php 
			} else{
   		?>
		<select id="siuf_rounded_corners" name="siuf_rounded_corners">
			<option value="yes">Yes</option>
			<option value="no">No</option>
		</select>
		<?php 
			} 
   		?>
		</tr></td>

		<tr><td></td><td>
		<input id='siuf_inputSaveSettings' type="button" value="<?php _e('Save Settings', $siuf_domain) ?>" class="button-primary" /> 
		<br><small >If you don't see your latest settings reflected in your site, please refresh your browser cache
		or close and open the browser.
		</small>	
		</td></tr>
		</table> 
			
		</form>
	    </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {

var siuf_wid= $('#siuf_widgetID').val();
if (siuf_wid=='') 
{}
else
{
	$( "#siuf_register" ).hide();
	$( "#siuf_registerComplete" ).show();
	$( "#siuf_noAccountSpan" ).html("Configure your User Feedback and Ratings Widget");

}
$(document).on("click", "#siuf_inputSaveSettings", function () {

var siuf_wid= $('#siuf_widgetID').val();
var siuf_tt= $('#siuf_tab_text').val();
var siuf_ht= encodeURIComponent($('#siuf_header_text').val());
var siuf_intro= encodeURIComponent($('#siuf_intro_text').val());
var siuf_rating= $('#siuf_rating_text').val();
var siuf_fb= encodeURIComponent($('#siuf_feedback_text').val());

var siuf_ww= $('#siuf_popup_width').val();
var siuf_wh= $('#siuf_popup_height').val();
var siuf_rc= $('#siuf_rounded_corners').val();
var siuf_bi= encodeURIComponent($('#siuf_background_img').val());

var siuf_tp= $('#siuf_tab_placement').val();
var siuf_top= $('#siuf_time_on_page').val();
var url = 'https://www.socialintents.com/json/jsonSaveFeedbackSettings.jsp?tt='+siuf_tt+'&ht='+siuf_ht+'&wid='+siuf_wid+'&intro='+siuf_intro+'&rate='+siuf_rating+'&fb='+siuf_fb+'&wh='+siuf_wh+'&ww='+siuf_ww+'&rc='+siuf_rc+'&bi='+siuf_bi
+'&tp='+siuf_tp+'&top='+siuf_top+'&callback=?';
sessionStorage.removeItem("si_settings");
$.ajax({
   type: 'GET',
    url: url,
    async: false,
    jsonpCallback: 'jsonCallBack',
    contentType: "application/json",
    dataType: 'jsonp',
    success: function(json) {
       $('#siuf_widgetID').val(json.key);
	sessionStorage.removeItem("si_settings");
	sessionStorage.setItem("si_hasSeenPopup","false");
	$( "#saveDetailSettings" ).submit();
	
    },
    error: function(e) {
    }
});

  });

$(document).on("click", "#siuf_inputRegister", function () {

var siuf_email= $('#siuf_email').val();
var siuf_name= $('#siuf_name').val();
var siuf_password= $('#siuf_password').val();
var url = 'https://www.socialintents.com/json/jsonSignup.jsp?type=feedback&name='+siuf_name+'&email='+siuf_email+'&pw='+siuf_password+'&callback=?';
$.ajax({
   type: 'GET',
    url: url,
    async: false,
    jsonpCallback: 'jsonCallBack',
    contentType: "application/json",
    dataType: 'jsonp',
    success: function(json) {
	if (json.msg=='') {
         	$('#siuf_widgetID').val(json.key);
		alert("Thanks for signing up!");
		$( "#saveSettings" ).submit();
		
	}
	else {
		alert(json.msg);
	}
    },
    error: function(e) {
       
    }
});

});
});

</script>
    <?php }
    add_submenu_page('options-general.php', __('Social Intents', $siuf_domain), __('Social Intents', $siuf_domain), 'manage_options', 'user-feedback-and-ratings-by-socialintents', 'siuf_settings_page');
}?>
