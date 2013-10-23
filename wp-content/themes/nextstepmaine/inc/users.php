<?php
	function modify_contact_methods($profile_fields) {
		// Add new fields
		$profile_fields['title_iv_code'] = 'Title IV Code';
		$profile_fields['address'] = 'Address';
		$profile_fields['phone'] = 'Phone';
		$profile_fields['finaid_contact'] = 'Financial Aid Contact';
		$profile_fields['admission_contact'] = 'Admission Contact';
		$profile_fields['type'] = 'Institution Type';

		return $profile_fields;
	}
	add_filter('user_contactmethods', 'modify_contact_methods');