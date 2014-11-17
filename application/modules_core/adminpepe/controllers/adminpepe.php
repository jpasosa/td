<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');




class Adminpepe extends MX_Controller
{



    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('testing/header_testing');



        // $this->load->view('testing/testing_home');
        // $this->load->view('testing/testing_footer');
    }



}