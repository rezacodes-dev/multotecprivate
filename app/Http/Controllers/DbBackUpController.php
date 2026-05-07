<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use Auth;
use File;

class DbBackUpController extends Controller
{
    
    public function index() {

    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['oneMenu'] = 'dbbkp';
    	$dbbkp_path = public_path() . '/dbbackups/';
    	$files = File::allFiles( $dbbkp_path );
    	$dataBag['allFiles'] = $files;
    	return view('dashboard.db_backup.index', $dataBag);
    }

    public function create(Request $request) {

        $filename = "Multotec_".date("d-m-Y-H-i-s").".sql.gz";
        $db = env('DB_DATABASE');
        $dbuser = env('DB_USERNAME');
        $dbpwd = env('DB_PASSWORD');
        $dbhost = env('DB_HOST');

    	try {

        	$command = "mysqldump --opt --user=" . $dbuser ." --password=" . $dbpwd . " --host=" . $dbhost . " " . $db . " | gzip > " . public_path() . "/dbbackups/" . $filename."  2>&1";
        	$returnVar = NULL;
        	$output  = NULL;
        	exec($command, $output, $returnVar);
        	return 1;

     	} catch(Exception $e) {

        	return $e->errorInfo; 
     	}
    }

    public function delete(Request $request) {

    	$filename = trim( $request->input('filename') );
    	$path = 'public/dbbackups/'. $filename;
    	
    	if( File::exists($path) ) {
    		File::delete($path);
    		return 1;
    	} 

    	return 0;
    }

    public function deleteAll() {

    	File::cleanDirectory(public_path('/dbbackups'));
    	//File::deleteDirectory(public_path('path/to/folder'));
    	return back();
    }
}
