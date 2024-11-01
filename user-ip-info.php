<?php
/*
Plugin Name: User IP Information
Plugin URI: https://wordpress.org/plugins/user-ip-information/
Description: This plugin show finds user IP and gives some information about them on front-end.
Author: Adeel Sikander Raja
Version: 10.0
Author URI: https://profiles.wordpress.org/adeelsikander
*/



//Add action to show menu in wp-admin
add_action( 'admin_menu', 'user_ip_info' );
function user_ip_info() {

	add_options_page( 'User IP info Options', 'User IP Info', 'manage_options', 'user-ip-info', 'user_ip_info_options' );

}

//Register Option Page form setting
function uiin_register_settings() {
   add_option( 'uiin_option_name', 'Please see your IP and location detail below.');
   register_setting( 'uiin_options_group', 'uiin_option_name', 'uiin_callback' );
}
add_action( 'admin_init', 'uiin_register_settings' );

//Option page to show custom fields or setting for wordpress
function user_ip_info_options() {

	if ( !current_user_can( 'manage_options' ) )  {

		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	}

	echo '<div class="wrap">';?>

	<?php screen_icon(); ?>
  	<h2>USER IP INFORMATION</h2>
  	<form method="post" action="options.php">
  		<?php settings_fields( 'uiin_options_group' ); ?>
            <h3>Your Custom Message for User</h3>
            <table>
                <tr valign="top">
                    <th scope="row"></label></th>
                    <td><input type="text" id="uiin_option_name" name="uiin_option_name" value="<?php echo get_option('uiin_option_name'); ?>" /></td>
                </tr>
            </table>
  		<?php  submit_button(); ?>
  	</form>
	<?php 
	echo '</div>';

	}

  
function uiin_customMessage(){
	
	// Getting the form field for custom message from Plugin Option Page
	
	$mymessage=  get_option('uiin_option_name');
	
	 
	 return $mymessage;
	
}
 
 add_shortcode('custom_message', 'uiin_customMessage');

//extract User IP  

 function uiin_getUsrIpAddr() {



	  if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
        $userip = $_SERVER['HTTP_CLIENT_IP']; 
    } 
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
        $userip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
    } 
    else { 
        $userip = $_SERVER['REMOTE_ADDR']; 
    } 


	$mymessage=  get_option('uiin_option_name');?>
    <b> <?php echo $mymessage;?></b><br />
    
    
    
    <?php 
  return apply_filters('wpb_get_ip', $userip);

 }

// Store the IP address 

//$userip = getVisIPAddr(); 

  

// Display the IP address  using shortcode

//echo $userip; 

 add_shortcode('user_ip', 'uiin_getUsrIpAddr');


//Function to get extract the user info through IP
 function uiin_getlocationByUserIp($userip){
    $cl  = @$_SERVER['HTTP_CLIENT_IP'];
    $fr = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $rm  = @$_SERVER['REMOTE_ADDR'];
    if(filter_var($cl, FILTER_VALIDATE_IP)){	

       echo $userip = $cl;

    }elseif(filter_var($fr, FILTER_VALIDATE_IP)){

      echo  $userip = $fr;

    } else {

       echo  $userip = $rm;

    }
	//Geoplugin API to get Location Information of a user through IP
    $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$userip));    

     // $usip_data = '';
      //$usip_type = $userip['type'];
	 // print_r($ipdat);
		echo "<br />";
		echo 'Country Name: ' . $ipdat->geoplugin_countryName . "</br>"; 
		echo 'City Name: ' . $ipdat->geoplugin_city . "</br>"; 
		echo 'Region Name: ' . $ipdat->geoplugin_regionName . "</br>"; 
		echo 'Region Code: ' . $ipdat->geoplugin_regionCode . "</br>"; 
		echo 'Continent Name: ' . $ipdat->geoplugin_continentName . "</br>"; 
		echo 'Continent Code: ' . $ipdat->geoplugin_continentCode . "</br>"; 
		echo 'Latitude: ' . $ipdat->geoplugin_latitude . "</br>"; 
		echo 'Longitude: ' . $ipdat->geoplugin_longitude . "</br>"; 
		echo 'Currency Symbol: ' . $ipdat->geoplugin_currencySymbol . "</br>"; 
		echo 'Currency Code: ' . $ipdat->geoplugin_currencyCode . "</br>"; 
		echo 'Timezone: ' . $ipdat->geoplugin_timezone; 

 } 
 add_shortcode('user_ip_info', 'uiin_getlocationByUserIp');       
?>