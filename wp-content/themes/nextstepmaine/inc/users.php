<?php
	function modify_contact_methods($profile_fields) {
		// Add new fields
		$profile_fields['title_iv_code'] = 'Title IV Code';
		$profile_fields['address'] = 'Address';
		$profile_fields['general_phone'] = 'General Phone';
		$profile_fields['general_email'] = 'General Email';
		$profile_fields['finaid_phone'] = 'Financial Aid Phone';
		$profile_fields['finaid_email'] = 'Financial Aid Email';
		$profile_fields['admission_phone'] = 'Admission Phone';
		$profile_fields['admission_email'] = 'Admission Email';
		$profile_fields['type'] = 'Institution Type';

		/*
			admission_contact
			finaid_contact
		*/

		return $profile_fields;
	}
	add_filter('user_contactmethods', 'modify_contact_methods');