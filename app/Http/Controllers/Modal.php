<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modal extends CI_Controller {

	// constructor
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	// default fucntion
	public function index()
	{

	}

	// popup method
	function popup($page_name = '', $param2 = '', $param3 = '', $param4 = '') {
		$account_type				    =	$this->session->userdata('login_type');
		$page_data['param2']		=	$param2;
		$page_data['param3']		=	$param3;
		$page_data['param4']		=	$param4;
		$this->load->view('backend/'.$account_type.'/'.$page_name.'.php' , $page_data);
	}

}
