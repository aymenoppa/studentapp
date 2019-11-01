<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* 	
 * 	@author : Joyonto Roy
 * 	30th July, 2014
 * 	Creative Item
 * 	www.creativeitem.com
 * 	http://codecanyon.net/user/joyontaroy
 */

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        /* cache control */
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }

    //Default function, redirects to logged in user area
    public function index() {

        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');

        if ($this->session->userdata('teacher_login') == 1)
            redirect(base_url() . 'index.php?teacher/dashboard', 'refresh');

        if ($this->session->userdata('student_login') == 1)
            redirect(base_url() . 'index.php?student/dashboard', 'refresh');

        if ($this->session->userdata('parent_login') == 1)
            redirect(base_url() . 'index.php?parents/dashboard', 'refresh');

        $this->load->view('backend/login');
    }

    function do_login()
    {
        $response = array();

        //Recieving post input of email, password from ajax request
        $email      = $_POST["email"];
        $password   = sha1($_POST["password"]);

        //Validating login
        $login_status = $this->validate_login($email,  $password);
        if ($login_status == 'invalid') {
            $this->session->set_flashdata('error_message', get_phrase('invalid_login') . '! ' . get_phrase('please_enter_correct_email_and_password') . '.');
        }

        redirect(base_url() . 'index.php?login', 'refresh');
    }

    //Validating login from ajax request
    function validate_login($email = '', $password = '') {
        $credential1 = array('email' => $email, 'password' => $password);
        $credential2 = array('username' => $email, 'password' => $password);

        // Checking login credential for admin
        $query1 = $this->db->get_where('admin', $credential1);
        $query2 = $this->db->get_where('admin', $credential2);

        if ($query1->num_rows() > 0 || $query2->num_rows() > 0) {
            if($query1->num_rows() > 0)
                $row = $query1->row();
            else
                $row = $query2->row();

            $this->session->set_userdata('admin_login', '1');
            $this->session->set_userdata('admin_id', $row->admin_id);
            $this->session->set_userdata('login_user_id', $row->admin_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'admin');

            return 'success';
        }

        // Checking login credential for teacher
        $query1 = $this->db->get_where('teacher', $credential1);
        $query2 = $this->db->get_where('teacher', $credential2);

        if ($query1->num_rows() > 0 || $query2->num_rows() > 0) {
            if($query1->num_rows() > 0)
                $row = $query1->row();
            else
                $row = $query2->row();

            $this->session->set_userdata('teacher_login', '1');
            $this->session->set_userdata('teacher_id', $row->teacher_id);
            $this->session->set_userdata('login_user_id', $row->teacher_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'teacher');

            return 'success';
        }

        // Checking login credential for parent
        $query1 = $this->db->get_where('parent', $credential1);
        $query2 = $this->db->get_where('parent', $credential2);

        if ($query1->num_rows() > 0 || $query2->num_rows() > 0) {
            if($query1->num_rows() > 0)
                $row = $query1->row();
            else
                $row = $query2->row();

            $this->session->set_userdata('parent_login', '1');
            $this->session->set_userdata('parent_id', $row->parent_id);
            $this->session->set_userdata('login_user_id', $row->parent_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'parent');

            return 'success';
        }

        // Checking login credential for student
        $query1 = $this->db->get_where('student', $credential1);
        $query2 = $this->db->get_where('student', $credential2);

        if ($query1->num_rows() > 0 || $query2->num_rows() > 0) {
            if($query1->num_rows() > 0)
                $row = $query1->row();
            else
                $row = $query2->row();

            $this->session->set_userdata('student_login', '1');
            $this->session->set_userdata('student_id', $row->student_id);
            $this->session->set_userdata('login_user_id', $row->student_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'student');

            return 'success';
        }

        return 'invalid';
    }

    // LOGOUT FUNCTION
    function logout() {
        $this->session->sess_destroy();
        redirect(base_url(), 'refresh');
    }

    /*     * *DEFAULT NOR FOUND PAGE**** */

    function four_zero_four() {
        $this->load->view('four_zero_four');
    }

    // PASSWORD RESET BY EMAIL
    function forgot_password()
    {
        $this->load->view('backend/forgot_password');
    }

    function ajax_forgot_password()
    {
        $resp                   = array();
        $resp['status']         = 'false';
        $email                  = $_POST["email"];
        $reset_account_type     = '';
        //resetting user password here
        $new_password           =   substr( md5( rand(100000000,20000000000) ) , 0,7);

        // Checking credential for admin
        $query = $this->db->get_where('admin' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'admin';
            $this->db->where('email' , $email);
            $this->db->update('admin' , array('password' => sha1($new_password)));
            $resp['status']         = 'true';
        }
        // Checking credential for student
        $query = $this->db->get_where('student' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'student';
            $this->db->where('email' , $email);
            $this->db->update('student' , array('password' => sha1($new_password)));
            $resp['status']         = 'true';
        }
        // Checking credential for teacher
        $query = $this->db->get_where('teacher' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'teacher';
            $this->db->where('email' , $email);
            $this->db->update('teacher' , array('password' => sha1($new_password)));
            $resp['status']         = 'true';
        }
        // Checking credential for parent
        $query = $this->db->get_where('parent' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'parent';
            $this->db->where('email' , $email);
            $this->db->update('parent' , array('password' => sha1($new_password)));
            $resp['status']         = 'true';
        }

        // send new password to user email  
        $this->email_model->password_reset_email($new_password , $reset_account_type , $email);

        $resp['submitted_data'] = $_POST;

        echo json_encode($resp);
    }

}
