<?php
/**
 * @Author Siddiqkhan
 *
 * @Download Controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class download extends CI_Controller {

	protected $userSession = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->model('Employees_model');		
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->library('zip');
		$this->userSession = $this->session->userdata('username');
	}
	
	public function output_file($file, $name, $mime_type='') {
		 if(!is_readable($file)) die('File not found or inaccessible!');
		 $size = filesize($file);
		 $name = rawurldecode($name);
		 $known_mime_types=array(
			"htm" => "text/html",
			"exe" => "application/octet-stream",
			"zip" => "application/zip",
			"doc" => "application/msword",
			"jpg" => "image/jpg",
			"php" => "text/plain",
			"xls" => "application/vnd.ms-excel",
			"ppt" => "application/vnd.ms-powerpoint",
			"gif" => "image/gif",
			"pdf" => "application/pdf",
			"txt" => "text/plain",
			"html"=> "text/html",
			"png" => "image/png",
			"jpeg"=> "image/jpg"
		 );
		 
		 if($mime_type==''){
			 $file_extension = strtolower(substr(strrchr($file,"."),1));
			 if(array_key_exists($file_extension, $known_mime_types)){
				$mime_type=$known_mime_types[$file_extension];
			 } else {
				$mime_type="application/force-download";
			 };
		 };
		 
		 //turn off output buffering to decrease cpu usage
		 @ob_end_clean(); 
		 
		 // required for IE, otherwise Content-Disposition may be ignored
		 if(ini_get('zlib.output_compression'))
		 ini_set('zlib.output_compression', 'Off');
		 header('Content-Type: ' . $mime_type);
		 header('Content-Disposition: attachment; filename="'.$name.'"');
		 header("Content-Transfer-Encoding: binary");
		 header('Accept-Ranges: bytes');
		 
		 // multipart-download and download resuming support
		 if(isset($_SERVER['HTTP_RANGE']))
		 {
			list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
			list($range) = explode(",",$range,2);
			list($range, $range_end) = explode("-", $range);
			$range=intval($range);
			if(!$range_end) {
				$range_end=$size-1;
			} else {
				$range_end=intval($range_end);
			}
		
			$new_length = $range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
		 } else {
			$new_length=$size;
			header("Content-Length: ".$size);
		 }
		 
		 /* Will output the file itself */
		 $chunksize = 1*(1024*1024); //you may want to change this
		 $bytes_send = 0;
		 if ($file = fopen($file, 'r'))
		 {
			if(isset($_SERVER['HTTP_RANGE']))
			fseek($file, $range);
		 
			while(!feof($file) && 
				(!connection_aborted()) && 
				($bytes_send<$new_length)
				  )
			{
				$buffer = fread($file, $chunksize);
				echo($buffer); 
				flush();
				$bytes_send += strlen($buffer);
			}
		 fclose($file);
		 } else
		 //If no permissiion
		 die('Error - can not open file.');
		 //die
		die();
	}
		
	public function index() {	
		// type
		$type = $this->input->get('type');		
		if($type!= '') {
			//Set the time out
			set_time_limit(0);
			
			// file name
			$filename = $this->input->get('filename');
			//path to the file
			$file_path='uploads/'.$type.'/'.$filename;
			
			if($type=='dbbackup'){
				$type='Database Backup';
			}
			/*User Logs*/			 
			$affected_id= table_update_id('xin_system_setting','setting_id',1);
			userlogs('Settings-'.$type.'-Download',$type.' Downloaded',1,$affected_id['datas']);
			/*User Logs*/
	
			//Call the download function with file path,file name and file type
			$this->output_file($file_path, ''.$filename.'', 'text/plain');
		}
	}	
	
	public function downloadpdf(){	
		// type
		$type = $this->input->get('type');
		$db = $this->input->get('db');
		if($type!= '') {
			//Set the time out
			set_time_limit(0);			
			// file name
			$document_id = $this->input->get('id');
			if($db=='document'){
			$dbname='xin_employee_immigration';
			}
			else{
			$dbname='xin_employee_contract';
			}
			
            $filename=$this->Employees_model->get_document_image_name($document_id,$dbname,$db);


            $file_name=array_filter(explode(',',$filename));

			$this->zip->read_file($path, $new_path);
		    foreach($file_name as $fname){
			//path to the file
			$file_path='uploads/'.$type.'/'.$fname;		
			$new_path ='document/'.$fname;	
			$this->zip->read_file($file_path, $new_path);
            }		
			$this->zip->download('Documents'.time().'.zip');

		}
	}

	
}

