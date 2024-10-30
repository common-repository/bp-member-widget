<?php

include('../../../wp-config.php');
include('../../../wp-load.php');

$text = $_GET['s'];
$res_num = $_GET['n'];


$users = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE display_name LIKE '%$text%' OR user_email LIKE '%$text%' LIMIT $res_num;");


foreach($users as $user) {
				$last_activity[] = get_usermeta($user->ID,'last_activity');
				$members[] = $user->ID;
				arsort($last_activity);
				}
foreach ($last_activity as $activity => $items){

				$user = new WP_User( $members[$activity] );
				$roler = $user->roles[0];
				
				$company_name= xprofile_get_field_data( "Firmenname" ,$members[$activity]);
				
				if ($company_name && (xprofile_get_field_data( "Welcher Name soll in Listen angezeigt werden?" ,$author->ID)) == "Firmenname") {
				$displayed_name = $company_name;
				} else {
				$displayed_name = get_the_author_meta('display_name', $members[$activity]);
				}
				
				$last_activitys = get_usermeta($members[$activity],'last_activity');

				if (function_exists('bp_core_get_user_domain')) {
					$link = bp_core_get_user_domain($members[$activity]);

				}
				elseif (function_exists('bp_core_get_userurl')) { // BP versions < 1.1
					$link = bp_core_get_userurl($members[$activity]);
				}

				echo "<div id='users-list'>";
				echo "<a href='$link'>";
				echo get_avatar($members[$activity]);
				echo "</a>";
				echo "<a href='$link'>";
				echo $displayed_name;
				echo "</a></div>";
				}
				
				?>