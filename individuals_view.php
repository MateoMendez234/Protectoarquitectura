<?php
// This script and data application were generated by AppGini 5.70
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/individuals.php");
	include("$currDir/individuals_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('individuals');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "individuals";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`individuals`.`id`" => "id",
		"`individuals`.`full_name`" => "full_name",
		"`individuals`.`national_id`" => "national_id",
		"`individuals`.`phone_number`" => "phone_number",
		"`individuals`.`gender`" => "gender"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`individuals`.`id`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`individuals`.`id`" => "id",
		"`individuals`.`full_name`" => "full_name",
		"`individuals`.`national_id`" => "national_id",
		"`individuals`.`phone_number`" => "phone_number",
		"`individuals`.`gender`" => "gender"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`individuals`.`id`" => "id",
		"`individuals`.`full_name`" => "FullName",
		"`individuals`.`national_id`" => "National_ID",
		"`individuals`.`phone_number`" => "Phone_Number",
		"`individuals`.`gender`" => "Gender"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`individuals`.`id`" => "id",
		"`individuals`.`full_name`" => "full_name",
		"`individuals`.`national_id`" => "national_id",
		"`individuals`.`phone_number`" => "phone_number",
		"`individuals`.`gender`" => "gender"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`individuals` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 0;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = true;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 1;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 50;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "individuals_view.php";
	$x->RedirectAfterInsert = "individuals_view.php";
	$x->TableTitle = "individuals";
	$x->TableIcon = "resources/table_icons/administrator.png";
	$x->PrimaryKey = "`individuals`.`id`";

	$x->ColWidth   = array(  150, 150, 150, 150, 150);
	$x->ColCaption = array("id", "FullName", "National_ID", "Phone_Number", "Gender");
	$x->ColFieldName = array('id', 'full_name', 'national_id', 'phone_number', 'gender');
	$x->ColNumber  = array(1, 2, 3, 4, 5);

	// template paths below are based on the app main directory
	$x->Template = 'templates/individuals_templateTV.html';
	$x->SelectedTemplate = 'templates/individuals_templateTVS.html';
	$x->TemplateDV = 'templates/individuals_templateDV.html';
	$x->TemplateDVP = 'templates/individuals_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->ShowRecordSlots = 0;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `individuals`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='individuals' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `individuals`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='individuals' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`individuals`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: individuals_init
	$render=TRUE;
	if(function_exists('individuals_init')){
		$args=array();
		$render=individuals_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: individuals_header
	$headerCode='';
	if(function_exists('individuals_header')){
		$args=array();
		$headerCode=individuals_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: individuals_footer
	$footerCode='';
	if(function_exists('individuals_footer')){
		$args=array();
		$footerCode=individuals_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>