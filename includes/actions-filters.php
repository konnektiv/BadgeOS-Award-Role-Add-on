<?php

if ( !function_exists('get_editable_roles') ) {
	require_once( ABSPATH . '/wp-admin/includes/user.php' );
}

add_filter('badgeos_achievement_data_meta_box_fields', function($fields) {
	$prefix = "_badgeos_";
	$options = array(
		array('value' => 'none', 'name' => __('None', 'badgeos-award-role'))
	);
	foreach (get_editable_roles() as $role_name => $role_info) {
		$options[] = array( 'value' => $role_name, 'name' => $role_info['name']);
	}
	$fields[] = array(
		'name' => __( 'Award Role', 'badgeos-award-role' ),
		'desc' => ' '.__( 'Role which should be awarded to the user when earning this achievement.', 'badgeos-award-role' ),
		'id'   => $prefix . 'award_role',
		'type' => 'select',
		'options' => $options,
		'default' => 'none',
	);
	return $fields;
});

add_action('badgeos_award_achievement', function($user_id, $achievement_id){
	$role = get_post_meta( $achievement_id, '_badgeos_award_role', true );

	error_log("Got role:$role:$achievement_id");
	if ($role && $role != 'none' ) {
		$user = new WP_User( $user_id );

		// Add role
		error_log("Adding role:$role");
		$user->add_role( $role );
	}
}, 10, 2);