<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');




class Testing extends MX_Controller
{



    public function __construct()
    {
        parent::__construct();
    }



    public function index()
    {
        $this->load->view('header');
        $this->load->view('body');



        // $this->load->view('testing/testing_home');
        // $this->load->view('testing/testing_footer');
    }

    public function holamundo()
    {
        $this->load->view('testing/holamundo');
    }

    public function pepe()
    {
        $this->load->view('testing/pepe');
    }



}