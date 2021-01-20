<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Api extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model');
        $this->load->model('staff_model');
    }
}