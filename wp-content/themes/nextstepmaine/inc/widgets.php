<?php
	//Widgets
	include('widgets/widget_financial-aid.php');
	include('widgets/widget_jobs-in-demand.php');
	include('widgets/widget_institutions.php');
	add_action( 'widgets_init', 'register_widgets');
	function register_widgets () {
		register_widget( "Widget_Financial_Aid" );
		register_widget( "Widget_Jobs_in_Demand" );
		register_widget( "Widget_Institutions" );
	}//register_widgets;