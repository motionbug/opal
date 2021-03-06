#!/usr/bin/php
<?php
/**
* Calculate the start and end times for every day in the timesheet.txt file 
*
* @package Opal
* @subpackage Punch
* @author Andrew Woods <atwoods1@gmail.com>
*/ 

$short_options = "dh";
$long_options = array('debug', 'help');

$option = getopt( $short_options, $long_options );

clean_argv( $short_options, $long_options );

if ( isset($option['h']) || isset($option['help']) )
{
	echo "HOME=" . getenv('HOME') . "\n";
	help();
	exit();
}
 
if ( isset($option['d']) || isset($option['debug']) )
{
	echo "command line options are:\n";
	print_r($option);
	print_r($argv);
}

$program = array_shift($argv);

if ( sizeof($argv) == 0 )
{
	$file = getenv('HOME') . "/timesheet.txt";
	if ( isset($option['debug']) || isset($option['d']) )
	{ 
		echo "Using Default filename=$file";
	}
}
else
{
	$file = array_shift($argv);
}


$fh = fopen($file, "r");
if (! $fh)
{
    die("Cannot open file\n");
}

$start_time = null;
$end_time = null;
$total_time = 0;
$task = null;
while ( ! feof($fh) )
{
    $record = fgets($fh);
    $record = rtrim($record);
    if ( preg_match("/^\s*$/", $record) )
    {
        continue; // skip empty lines
    }
    
     
    // separate the record into fields - space delimited
    // day, date, time, type, mesage
    $data = preg_split("/\s/", $record, 5); 
    if (empty($data[4]))
    {
        $data[4] = "";
    }
    list($day, $date, $time, $op, $message) = $data;

    if (! $message)
    {
        $message = "";
    }

	if ( isset($option['d']) || isset($option['debug']) )
	{
		printf("%s (%s) time=%s op=%s message=%s\n", $date, $day, $time, $op, $message);
	}
	 
	
	if ($op == 'IN')
	{
		$start_time = new DateTime($date . ' ' . $time);
		$task = $message;
	}

	if ($op == 'OUT')
	{
		$end_time = new DateTime($date . ' ' . $time);
		
		$interval = $end_time->diff($start_time);
		printf("%s on %s (%s) for %s\n", $interval->format("%H hours %I minutes"),  $date, $day, $message);
		// echo $interval->format("%H hours %I minutes") . " on " . $date . " for " . $task . "\n";
	}

}

fclose($fh);

/*
 ===============================================================================
								   FUNCTIONS
 ===============================================================================
*/

/**
* Display to the user how to use timecalc.
*
* @return void
*/ 
function help()
{
	echo "timecalc.php options\n";
	echo "\n";
	echo "-d | --debug         Display debug messages\n";
	echo "-h | --help          Display this help\n";
} 

/**
* Remove the option arguments from the argv array
*
* @param String $short_opts single character switches
* @param Array  $long_opts long form switches
* @return void
*/ 
function clean_argv($short_opts = '', $long_opts = array())
{
	$opts = getopt( $short_opts, $long_opts );
	global $argv;

	foreach( $opts as $o => $a )
	{
		while( $k = array_search( "-" . $o, $argv ) )
		{
			if( $k )
			{ 
				unset( $argv[$k] );
			}

			if( preg_match( "/^.*".$o.":.*$/i", $short_opts ) )
			{
				unset( $argv[$k+1] );
			}
		}
	}
	$argv = array_merge( $argv );
}
