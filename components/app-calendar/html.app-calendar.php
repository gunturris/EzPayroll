<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP Calendar version 1.0.2 (28.08.2009)                                  #
##  Developed by:  ApPhp <info@apphp.com>                                      #
##  License:       GNU GPL v.2                                                 #
##  Site:          http://www.apphp.com/php-calendar/                          #
##  Copyright:     ApPHP Calendar (c) 2009. All rights reserved.               #
##                                                                             #
################################################################################

class Calendar{
	
	
	// PUBLIC
	// --------
	// __construct()
	// __destruct()
	// Show()
	// SetCalendarDimensions
	// SetCaption
	// SetWeekStartedDay
	// SetWeekDayNameLength
	// 
	// STATIC
	// ----------
	// Version
	// 
	// PRIVATE
	// --------
	// SetDefaultParameters
	// GetCurrentParameters
	// DrawJsFunctions
	// DrawYear
	// DrawMonth	
	// DrawMonthSmall
	// DrawWeek
	// DrawDay
	// DrawTypesChanger
	// DrawDateJumper
	// DrawTodayJumper
	// --------
	// isYear
	// isMonth
	// isDay
	// ConvertToDecimal

	//--- PUBLIC DATA MEMBERS --------------------------------------------------
	public $error;
	
	//--- PROTECTED DATA MEMBERS -----------------------------------------------
	protected $weekDayNameLength;
	
	//--- PRIVATE DATA MEMBERS -------------------------------------------------
	private  $arrWeekDays;
	private  $arrMonths;
	private  $arrViewTypes;
	private  $defaultView;
	private  $defaultAction;
	
	private  $arrParameters;
	private  $arrToday;
	private  $prevYear;
	private  $nextYear;
	private  $prevMonth;
	private  $nextMonth;
	
	private  $isDrawNavigation;
	
	private  $crLt;	
	private  $caption;		
	private  $calWidth;		
	private  $calHeight;
	private  $cellHeight;

	static private $version = "1.0.2";
	
		
	//--------------------------------------------------------------------------
    // CLASS CONSTRUCTOR
	//--------------------------------------------------------------------------
	function __construct()
	{
		$this->defaultView   = "monthly";
		$this->defaultAction = "view";
		
		// possible values 1,2,....7
		$this->weekStartedDay = 1;
		
		$this->weekDayNameLength = "short"; // short|long
		
		$this->arrWeekDays = array();
		$this->arrWeekDays[0] = array("short"=>"Sun", "long"=>"Minggu");
		$this->arrWeekDays[1] = array("short"=>"Mon", "long"=>"Senin");
		$this->arrWeekDays[2] = array("short"=>"Tue", "long"=>"Selasa");
		$this->arrWeekDays[3] = array("short"=>"Wed", "long"=>"Rabu");
		$this->arrWeekDays[4] = array("short"=>"Thu", "long"=>"Kamis");
		$this->arrWeekDays[5] = array("short"=>"Fri", "long"=>"Jumat");
		$this->arrWeekDays[6] = array("short"=>"Sat", "long"=>"Sabtu");
		
		$this->arrMonths = array();
		$this->arrMonths["1"] = "January";
		$this->arrMonths["2"] = "February";
		$this->arrMonths["3"] = "March";
		$this->arrMonths["4"] = "April";
		$this->arrMonths["5"] = "May";
		$this->arrMonths["6"] = "June";
		$this->arrMonths["7"] = "July";
		$this->arrMonths["8"] = "August";
		$this->arrMonths["9"] = "September";
		$this->arrMonths["10"] = "October";
		$this->arrMonths["11"] = "November";
		$this->arrMonths["12"] = "December";
		
		$this->arrViewTypes = array();
		$this->arrViewTypes["daily"]   = "Daily";
		$this->arrViewTypes["weekly"]  = "Weekly";
		$this->arrViewTypes["monthly"] = "Monthly";
		$this->arrViewTypes["yearly"]  = "Yearly";
		
		$this->arrParameters = array();
		$this->SetDefaultParameters();

		$this->arrToday  = array();
		$this->prevYear  = array();
		$this->nextYear  = array();
		$this->prevMonth = array();
		$this->nextMonth = array();
		
		$this->isDrawNavigation = true;
		
		$this->crLt = "\n";
		$this->caption = "";
		$this->calWidth = "800px";
		$this->calHeight = "470px";
		$this->celHeight = number_format(((int)$this->calHeight)/6, "0")."px";
	}
	
	//--------------------------------------------------------------------------
    // CLASS DESTRUCTOR
	//--------------------------------------------------------------------------
    function __destruct()
	{
		// $htmlTextView .= 'this object has been destroyed';
    }

	
	//==========================================================================
    // PUBLIC DATA FUNCTIONS
	//==========================================================================			
	/**
	 *	Show Calendar 
	 *
	*/	
	function Show()
	{	$htmlTextView ="";
		$this->GetCurrentParameters( date('Y') , '02' , date('d') );
		//$this->DrawJsFunctions();
		return $this->DrawMonth();
		 
	}
	
	
	/**
	 *	Set calendar dimensions
	 *  	@param $width
	 *  	@param $height
	*/	
	function SetCalendarDimensions($width = "", $height = "")
	{
		$this->calWidth = ($width != "") ? $width : "800px";
		$this->calHeight = ($height != "") ? $height : "470px";
		$this->celHeight = number_format(((int)$this->calHeight)/6, "0")."px";
	}

	/**
	 *	Check if parameters is 4-digit year
	 *  	@param $year - string to be checked if it's 4-digit year
	*/	
	function SetCaption($caption_text = "")
	{
		$this->caption = $caption_text;	
	}
	
	/**
	 *	Set week started day
	 *  	@param $started_day - started day of week 1...7
	*/	
	function SetWeekStartedDay($started_day = "1")
	{
		if(is_numeric($started_day) && (int)$started_day >= 1 && (int)$started_day <= 7){
			$this->setWeekStartedDay = (int)$started_day;				
		}
	}

	/**
	 *	Set week day name length 
	 *  	@param $length_name - "short"|"long"
	*/	
	function SetWeekDayNameLength($length_name = "short")
	{
		if(strtolower($length_name) == "long"){
			$this->weekDayNameLength = "long";
		}
	}
	
	//==========================================================================
    // STATIC
	//==========================================================================		
	/**
	 *	Return current version
	*/	
	static function Version()
	{
		return self::$version;
	}
	
	
	
	//==========================================================================
    // PRIVATE DATA FUNCTIONS
	//==========================================================================		
	/**
	 *	Set default parameters
	 *
	*/	
	function SetDefaultParameters()
	{
		$this->arrParameters["year"]  = date("Y");
		$this->arrParameters["month"] = date("m");
		$this->arrParameters["month_full_name"] = date("F");
		$this->arrParameters["day"]   = date("d");
		$this->arrParameters["view_type"] = $this->defaultView;
		$this->arrParameters["action"] = "display";
		$this->arrToday = getdate();

		// get current file
		$this->arrParameters["current_file"] = $_SERVER["SCRIPT_NAME"];
		$parts = explode('/', $this->arrParameters["current_file"]);
		$this->arrParameters["current_file"] = $parts[count($parts) - 1];		
	}

	/**
	 *	Get current parameters - read them from URL
	 *
	*/	
	function GetCurrentParameters($year,$month,$day)
	{
		$view_type 	= (isset($_GET['view_type']) && array_key_exists($_GET['view_type'], $this->arrViewTypes)) ? $_GET['view_type'] : "monthly";
	
		$cur_date = getdate(mktime(0,0,0,$month,$day,$year));
		
		///$htmlTextView .= "<br>3--";
		///print_r($cur_date);
		
		$this->arrParameters["year"]  = $cur_date['year'];
		$this->arrParameters["month"] = $this->ConvertToDecimal($cur_date['mon']);
		$this->arrParameters["month_full_name"] = $cur_date['month'];
		$this->arrParameters["day"]   = $day;
		$this->arrParameters["view_type"] = $view_type;
		$this->arrParameters["action"] = "display";
		$this->arrToday = getdate();

		$this->prevYear = getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters["day"],$this->arrParameters['year']-1));
		$this->nextYear = getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters["day"],$this->arrParameters['year']+1));

		$this->prevMonth = getdate(mktime(0,0,0,$this->arrParameters['month']-1,$this->arrParameters["day"],$this->arrParameters['year']));
		$this->nextMonth = getdate(mktime(0,0,0,$this->arrParameters['month']+1,$this->arrParameters["day"],$this->arrParameters['year']));
	}

	/**
	 *	Draw javascript functions
	 *
	*/	
	private function DrawJsFunctions()
	{
		$htmlTextView ="";
		$htmlTextView .= "<script type='text/javascript'>";
		$htmlTextView .= "
			function JumpToDate(){
				var jump_day   = (document.getElementById('jump_day')) ? document.getElementById('jump_day').value : '';
				var jump_month = (document.getElementById('jump_month')) ? document.getElementById('jump_month').value : '';
				var jump_year  = (document.getElementById('jump_year')) ? document.getElementById('jump_year').value : '';
				var view_type  = (document.getElementById('view_type')) ? document.getElementById('view_type').value : '';
				
				__doPostBack('view', view_type, jump_year, jump_month, jump_day);
			}
		
			function __doPostBack(action, view_type, year, month, day)
			{			
				var action    = (action != null) ? action : 'view';
				var view_type = (view_type != null) ? view_type : 'monthly';
				var year      = (year != null) ? year : '".$this->arrToday["year"]."';
				var month     = (month != null) ? month : '".$this->ConvertToDecimal($this->arrToday["mon"])."';
				var day       = (day != null) ? day : '".$this->arrToday["mday"]."';
			
				document.location.href = '".$this->arrParameters["current_file"]."?action='+action+'&view_type='+view_type+'&year='+year+'&month='+month+'&day='+day;		
			}
		";
		$htmlTextView .= "</script>";
		return $htmlTextView;
	}

	/**
	 *	Draw yearly calendar
	 *
	*/	
	private function DrawYear()
	{
		$htmlTextView ="";
		$this->celHeight = "20px";
		$htmlTextView .= "<table class='year_container'>".$this->crLt;
		$htmlTextView .= "<tr>".$this->crLt;
			$htmlTextView .= "<th colspan='3'>";
				$htmlTextView .= "<table class='table_navbar'>".$this->crLt;
				$htmlTextView .= "<tr>";
				$htmlTextView .= "<th class='tr_navbar_left' valign='middle'>
					  ".$this->DrawDateJumper(false, false, false)."
					  </th>".$this->crLt;
				$htmlTextView .= "<th class='tr_navbar'></th>".$this->crLt;
				$htmlTextView .= "<th class='tr_navbar_right'>				
					  <a href=\"javascript:__doPostBack('view', 'yearly', '".$this->prevYear['year']."', '".$this->arrParameters['month']."', '".$this->arrParameters['day']."')\">".$this->prevYear['year']."</a> |
					  <a href=\"javascript:__doPostBack('view', 'yearly', '".$this->nextYear['year']."', '".$this->arrParameters['month']."', '".$this->arrParameters['day']."')\">".$this->nextYear['year']."</a>
					  </th>".$this->crLt;
				$htmlTextView .= "</tr>".$this->crLt;
				$htmlTextView .= "</table>".$this->crLt;
			$htmlTextView .= "</td>".$this->crLt;
		$htmlTextView .= "</tr>".$this->crLt;

		$htmlTextView .= "<tr>";
		for($i = 1; $i <= 12; $i++){
			$htmlTextView .= "<td align='center' valign='top'>";
			$htmlTextView .= "<a href=\"javascript:__doPostBack('view', 'monthly', '".$this->arrParameters['year']."', '".$this->ConvertToDecimal($i)."', '".$this->arrParameters['day']."')\"><b>".$this->arrMonths["$i"]."</b></a>";
			$this->DrawMonthSmall($this->arrParameters['year'], $this->ConvertToDecimal($i));
			$htmlTextView .= "</td>";
			if(($i != 1) && ($i % 3 == 0)) $htmlTextView .= "</tr><tr>";
		}
		$htmlTextView .= "</tr>";
		$htmlTextView .= "<tr><td nowrap height='5px'></td></tr>";
		$htmlTextView .= "</table>";
		return $htmlTextView;
	}

	/**
	 *	Draw monthly calendar
	 *
	*/	
	private function DrawMonth()
	{
	
		$htmlTextView ="";
		// today, first day and last day in month
		$firstDay = getdate(mktime(0,0,0,$this->arrParameters['month'],1,$this->arrParameters['year']));
		$lastDay  = getdate(mktime(0,0,0,$this->arrParameters['month']+1,0,$this->arrParameters['year']));
		  
		// Create a table with the necessary header informations
		$htmlTextView .= "<table class='month'>".$this->crLt;
		 
		$htmlTextView .= "<tr class='tr_days'>";
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				$htmlTextView .= "<td class='th'>".$this->arrWeekDays[($i % 7)][$this->weekDayNameLength]."</td>";		
			}
		$htmlTextView .= "</tr>".$this->crLt;
		
		// Display the first calendar row with correct positioning
		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$max_empty_days = $firstDay['wday']-($this->weekStartedDay-1);		
		if($max_empty_days < 7){
			$htmlTextView .= "<tr class='tr' style='height:".$this->celHeight.";'>".$this->crLt;			
			for($i = 1; $i <= $max_empty_days; $i++){
				$htmlTextView .= "<td class='td_empty'>&nbsp;</td>".$this->crLt;
			}			
			$actday = 0;
			for($i = $max_empty_days+1; $i <= 7; $i++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $this->arrParameters["month"])) {
					$class = " class='td_actday'";			
				} else if ($actday == $this->arrParameters['day']){				
					$class = " class='td_selday'";				
				} else {
					$class = " class='td'";
				} 
				$content_label = display_report( $actday , $this->arrParameters['month'] , $this->arrParameters['year'] );
				$background_color = is_hari_libur($this->arrParameters['year'].'-'.$this->arrParameters['month'].'-'.sprintf('%02d' , $actday )) ? 'style="background-color:#f9ebae" ': '' ;
				$htmlTextView .= "<td {$background_color}{$class}>$actday{$content_label}</td>".$this->crLt;
			}
			$htmlTextView .= "</tr>".$this->crLt;
		}
		
		//Get how many complete weeks are in the actual month
		$fullWeeks = floor(($lastDay['mday']-$actday)/7);
		
		for ($i=0;$i<$fullWeeks;$i++){
			$htmlTextView .= "<tr class='tr' style='height:".$this->celHeight.";'>".$this->crLt;
			for ($j=0;$j<7;$j++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $this->arrParameters["month"])) {
					$class = " class='td_actday'";
				} else if ($actday == $this->arrParameters['day']){				
					$class = " class='td_selday'";				
				} else {
					$class = " class='td'";
				}
				$content_label = display_report( $actday , $this->arrParameters['month'] , $this->arrParameters['year'] );
				$background_color = is_hari_libur($this->arrParameters['year'].'-'.$this->arrParameters['month'].'-'.sprintf('%02d' , $actday )) ? 'style="background-color:#f9ebae" ': '' ;
				$htmlTextView .= "<td {$background_color}{$class}>$actday{$content_label}</td>".$this->crLt;
			}
			$htmlTextView .= "</tr>".$this->crLt;
		}
		
		//Now display the rest of the month
		if ($actday < $lastDay['mday']){
			$htmlTextView .= "<tr class='tr' style='height:".$this->celHeight.";'>".$this->crLt;
			
			for ($i=0; $i<7;$i++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $this->arrParameters["month"])) {
					$class = " class='td_actday'";
				} else {
					$class = " class='td'";
				}				
				if ($actday <= $lastDay['mday']){
					$content_label = display_report( $actday, $this->arrParameters['month'] , $this->arrParameters['year'] );
					$background_color = is_hari_libur($this->arrParameters['year'].'-'.$this->arrParameters['month'].'-'.sprintf('%02d' , $actday )) ? 'style="background-color:#f9ebae" ': '' ;
					$htmlTextView .= "<td {$background_color}$class>$actday{$content_label}</td>".$this->crLt;
				} else {
					$htmlTextView .= "<td class='td_empty'>&nbsp;</td>".$this->crLt;
				}
			}					
			$htmlTextView .= "</tr>".$this->crLt;
		}		
		$htmlTextView .= "</table>".$this->crLt;  
		return $htmlTextView;
	}


	/**
	 *	Draw small monthly calendar
	 *
	*/	
	private function DrawMonthSmall($year = "", $month = "")
	{
		if($month == "") $month = $this->arrParameters['month'];
		if($year == "") $year = $this->arrParameters['year'];
		$week_rows = 0;
		$htmlTextView ="";
		// today, first day and last day in month
		$firstDay = getdate(mktime(0,0,0,$month,1,$year));
		$lastDay  = getdate(mktime(0,0,0,$month+1,0,$year));
		
		///print_r($firstDay);
		
		// create a table with the necessary header informations
		$htmlTextView .= "<table class='month_small'>".$this->crLt;
		$htmlTextView .= "<tr class='tr_small_days'>";
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				$htmlTextView .= "<td class='th_small'>".$this->arrWeekDays[($i % 7)]["short"]."</td>";		
			}
		$htmlTextView .= "</tr>".$this->crLt;
		
		// display the first calendar row with correct positioning
		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$max_empty_days = $firstDay['wday']-($this->weekStartedDay-1);		
		if($max_empty_days < 7){
			$htmlTextView .= "<tr class='tr_small' style='height:".$this->celHeight.";'>".$this->crLt;			
			for($i = 1; $i <= $max_empty_days; $i++){
				$htmlTextView .= "<td class='td_small_empty'>&nbsp;</td>".$this->crLt;
			}			
			$actday = 0;
			for($i = $max_empty_days+1; $i <= 7; $i++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $this->arrParameters["month"])) {
					$class = " class='td_small_actday'";			
				} else if ($actday == $this->arrParameters['day']){				
					$class = " class='td_small_selday'";				
				} else {
					$class = " class='td_small'";
				} 
				$htmlTextView .= "<td$class>$actday</td>".$this->crLt;
			}
			$htmlTextView .= "</tr>".$this->crLt;
			$week_rows++;
		}
		
		// get how many complete weeks are in the actual month
		$fullWeeks = floor(($lastDay['mday']-$actday)/7);
		
		for ($i=0;$i<$fullWeeks;$i++){
			$htmlTextView .= "<tr class='tr_small' style='height:".$this->celHeight.";'>".$this->crLt;
			for ($j=0;$j<7;$j++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $month) && ($this->arrToday['year'] == $year)) {
					$class = " class='td_small_actday'";
				} else if ($actday == $this->arrParameters['day'] && ($this->arrToday['mon'] == $month)){				
					$class = " class='td_small_selday'";				
				} else {
					$class = " class='td_small'";
				}
				$htmlTextView .= "<td$class>$actday</td>".$this->crLt;
			}
			$htmlTextView .= "</tr>".$this->crLt;
			$week_rows++;			
		}
		
		// now display the rest of the month
		if ($actday < $lastDay['mday']){
			$htmlTextView .= "<tr class='tr_small' style='height:".$this->celHeight.";'>".$this->crLt;			
			for ($i=0; $i<7;$i++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $month) && ($this->arrToday['year'] == $year)) {
					$class = " class='td_small_actday'";
				} else {
					$class = " class='td_small'";
				}				
				if ($actday <= $lastDay['mday']){
					$htmlTextView .= "<td$class>$actday</td>".$this->crLt;
				} else {
					$htmlTextView .= "<td class='td_small_empty'>&nbsp;</td>".$this->crLt;
				}
			}					
			$htmlTextView .= "</tr>".$this->crLt;
			$week_rows++;
		}
		
		// complete last line
		if($week_rows < 5){
			$htmlTextView .= "<tr class='tr_small' style='height:".$this->celHeight.";'>".$this->crLt;			
			for ($i=0; $i<7;$i++){
				$htmlTextView .= "<td class='td_small_empty'>&nbsp;</td>".$this->crLt;
			}					
			$htmlTextView .= "</tr>".$this->crLt;
			$week_rows++;			
		}
		
		$htmlTextView .= "</table>".$this->crLt;
		return $htmlTextView;
	}
	

	/**
	 *	Draw weekly calendar
	 *
	*/	
	private function DrawWeek()
	{
		$htmlTextView .= "<br /><font color='#a60000'>This type of calendar view is not available in free version</font>";	        
	    return false;
	
		// today, first day and last day in month
		$firstDay = getdate(mktime(0,0,0,$this->arrParameters['month'],1,$this->arrParameters['year']));
		$lastDay  = getdate(mktime(0,0,0,$this->arrParameters['month']+1,0,$this->arrParameters['year']));
		
		
		// Create a table with the necessary header informations
		$htmlTextView .= "<table class='month'>".$this->crLt;
		$htmlTextView .= "<tr>";
		$htmlTextView .= "<th class='tr_navbar_left' colspan='2'>
			  ".$this->DrawDateJumper(false)."	
			  </th>".$this->crLt;
		$htmlTextView .= "<th class='tr_navbar' colspan='3'>".$this->arrParameters['month_full_name']." - ".$this->arrParameters['year']."</th>".$this->crLt;
		$htmlTextView .= "<th class='tr_navbar_right' colspan='2'>				
			  <a href=\"javascript:__doPostBack('view', 'monthly', '".$this->prevYear['year']."')\">".$this->prevYear['year']."</a> |
			  <a href=\"javascript:__doPostBack('view', 'monthly', '".$this->nextYear['year']."')\">".$this->nextYear['year']."</a>
			  </th>".$this->crLt;
		$htmlTextView .= "</tr>".$this->crLt;
		$htmlTextView .= "<tr class='tr_days'>";
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				$htmlTextView .= "<td class='th'>".$this->arrWeekDays[($i % 7)][$this->weekDayNameLength]."</td>";		
			}
		$htmlTextView .= "</tr>".$this->crLt;

		
		
		// Display the first calendar row with correct positioning
		$htmlTextView .= "<tr>".$this->crLt;
		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		for($i = 1; $i <= $firstDay['wday']-($this->weekStartedDay-1); $i++){
			$htmlTextView .= "<td class='td'>&nbsp;</td>".$this->crLt;
		}
		$actday = 0;
		for($i = ($firstDay['wday']-($this->weekStartedDay-1))+1; $i <= 7; $i++){
			$actday++;
			if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $this->arrParameters["month"])) {
				$class = " class='td_actday'";
			} else {
				$class = " class='td'";
			}
			
			$htmlTextView .= "<td$class>$actday</td>".$this->crLt;
		}
		$htmlTextView .= "</tr>".$this->crLt;
		$htmlTextView .= "</table>".$this->crLt;
		return $htmlTextView;
	}



	/**
	 *	Draw daily calendar
	 *
	*/	
	private function DrawDay()
	{
		$htmlTextView .= "<br /><font color='#a60000'>This type of calendar view is not available in free version</font>";	        
	}

	/**
	 *	Draw calendar types changer
	 *  	@param $draw - draw or return
	*/	
	private function DrawTypesChanger($draw = true)
	{
		$result = "<select class='form_select' name='view_type' id='view_type' onchange=\"document.location.href='".$this->arrParameters["current_file"]."?action=view&view_type='+this.value\">";
		foreach($this->arrViewTypes as $key => $val){
			$result .= "<option value='".$key."' ".(($this->arrParameters['view_type'] == $key) ? "selected='selected'" : "").">".$val."</option>";
		}
		$result .= "</select>";
		
		if($draw){
			$htmlTextView .= $result;
		}else{
			return $result;
		}
	}

	/**
	 *	Draw today jumper
	 *  	@param $draw - draw or return
	*/	
	private function DrawTodayJumper($draw = true)
	{
		$result = "<input class='form_button' type='button' value='Today' onclick=\"javascript:__doPostBack('".$this->defaultAction."', '".$this->defaultView."', '".$this->arrToday["year"]."', '".$this->ConvertToDecimal($this->arrToday["mon"])."', '".$this->arrToday["mday"]."')\" />";
	
		if($draw){
			$htmlTextView .= $result;
		}else{
			return $result;
		}
	}
	
	/**
	 *	Draw date jumper
	 *  	@param $draw - draw or return
	*/	
	private function DrawDateJumper($draw = true, $draw_day = true, $draw_month = true, $draw_year = true)
	{
		$result = "<form name='frmCalendarJumper' class='class_form'>";

		// draw days ddl
		if($draw_day){
			$result = "<select class='form_select' name='jump_day' id='jump_day'>";
			for($i=1; $i <= 31; $i++){
				$i_converted = $this->ConvertToDecimal($i);
				$result .= "<option value='".$this->ConvertToDecimal($i)."' ".(($this->arrParameters["day"] == $i_converted) ? "selected='selected'" : "").">".$i_converted."</option>";
			}
			$result .= "</select> ";			
		}else{
			$result .= "<input type='hidden' name='jump_day' id='jump_day' value='".$this->arrToday["mday"]."' />";			
		}

		// draw months ddl
		if($draw_month){			
			$result .= "<select class='form_select' name='jump_month' id='jump_month'>";
			for($i=1; $i <= 12; $i++){
				$i_converted = $this->ConvertToDecimal($i);
				$result .= "<option value='".$this->ConvertToDecimal($i)."' ".(($this->arrParameters["month"] == $i_converted) ? "selected='selected'" : "").">".$this->arrMonths[$i]."</option>";
			}
			$result .= "</select> ";			
		}else{
			$result .= "<input type='hidden' name='jump_month' id='jump_month' value='".$this->ConvertToDecimal($this->arrToday["mon"])."' />";			
		}

		// draw years ddl
		if($draw_year){			
			$result .= "<select class='form_select' name='jump_year' id='jump_year'>";
			for($i=$this->arrParameters["year"]-10; $i <= $this->arrParameters["year"]+10; $i++){
				$result .= "<option value='".$i."' ".(($this->arrParameters["year"] == $i) ? "selected='selected'" : "").">".$i."</option>";
			}
			$result .= "</select> ";
		}else{
			$result .= "<input type='hidden' name='jump_year' id='jump_year' value='".$this->arrToday["year"]."' />";			
		}
		
		$result .= "<input class='form_button' type='button' value='Go' onclick='JumpToDate()' />";
		$result .= "</form>";
		
		if($draw){
			$htmlTextView .= $result;
		}else{
			return $result;
		}
	}

	////////////////////////////////////////////////////////////////////////////
	// Auxilary
	////////////////////////////////////////////////////////////////////////////
	/**
	 *	Check if parameters is 4-digit year
	 *  	@param $year - string to be checked if it's 4-digit year
	*/	
	private function isYear($year = "")
	{
		if(!strlen($year) == 4 || !is_numeric($year)) return false;
		for($i = 0; $i < 4; $i++){
			if(!(isset($year[$i]) && $year[$i] >= 0 && $year[$i] <= 9)){
				return false;	
			}
		}
		return true;
	}

	/**
	 *	Check if parameters is month
	 *  	@param $month - string to be checked if it's 2-digit month
	*/	
	private function isMonth($month = "")
	{
		if(!strlen($month) == 2 || !is_numeric($month)) return false;
		for($i = 0; $i < 2; $i++){
			if(!(isset($month[$i]) && $month[$i] >= 0 && $month[$i] <= 9)){
				return false;	
			}
		}
		return true;
	}

	/**
	 *	Check if parameters is day
	 *  	@param $day - string to be checked if it's 2-digit day
	*/	
	private function isDay($day = "")
	{
		if(!strlen($day) == 2 || !is_numeric($day)) return false;
		for($i = 0; $i < 2; $i++){
			if(!(isset($day[$i]) && $day[$i] >= 0 && $day[$i] <= 9)){
				return false;	
			}
		}
		return true;
	}

	/**
	 *	Convert to decimal number with leading zero
	 *  	@param $number
	*/	
	private function ConvertToDecimal($number)
	{
		return (($number < 10) ? "0" : "").$number;
	}

}

function display_report( $mday , $mmonth , $myear  ){
	$mday = sprintf('%02d', $mday); 
	
	$content_iz = get_info_kehadiran( "{$myear}-{$mmonth}-{$mday}" , "IZ" );
	$content_dn = get_info_kehadiran( "{$myear}-{$mmonth}-{$mday}" , "DN" );
	$content_ct = get_info_kehadiran( "{$myear}-{$mmonth}-{$mday}" , "DN" );
	return '<div style="width:100%;text-align:center;padding-top:10px;">
		<b><span style="color:blue">'.$content_iz.'</span>
		<span style="color:brown">'.$content_dn.'</span>
		<span style="color:orange">'.$content_ct.'</span></b>
	</div>';
}

function get_info_kehadiran( $mday , $type ){
	$data = data_periode_lalu();
	return $data[$mday][$type];
}

function data_periode_lalu(){
	$path = '../files/services/date_lalu.json';  
	$contents = (string) file_get_contents($path);  
	$datas = array();
	$datas = json_decode($contents ,true); 
	return $datas; 
} 
 
?>