<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

	// constructor
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	// default function
	public function index()
	{
		if ($this->session->userdata('student_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('student_login') == 1)
            redirect(base_url() . 'index.php?student/dashboard', 'refresh');
	}

    // student DASHBOARD
    function dashboard()
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('dashboard');
        $this->load->view('backend/index', $page_data);
    }

    function student()
    {
    	if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'student';
        $data['page_title'] = get_phrase('students');
        $this->load->view('backend/index', $data);
    }

    // STUDENT PROFILE
    function student_profile($param1 = 'student_info', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['inner_page']    = $param1;
        $page_data['code']          = $param2;
        $page_data['month']         = $param3;
        $page_data['year']          = $param4;
        $page_data['page_name']     = 'student_profile';
        $page_data['page_title']    = get_phrase('student_profile');
        $this->load->view('backend/index', $page_data);
    }

    function attendance_report_selector()
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $month          = $this->input->post('month');
        $year           = $this->input->post('year');
        $student_code   = $this->input->post('code');

        redirect(base_url().'index.php?student/student_profile/student_attendance_report/' . $student_code . '/' . $month . '/' . $year, 'refresh');
    }

    // TEACHER PROFILE
    function teacher_profile($param1 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'teacher_profile';
        $page_data['page_title']    = get_phrase('teacher_profile');
        $this->load->view('backend/index', $page_data);
    }

    function teacher($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'teacher';
        $data['page_title'] = get_phrase('teachers');
        $this->load->view('backend/index', $data);
    }

    // PARENT PROFILE
    function parent_profile($param1 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'parent_profile';
        $page_data['page_title']    = get_phrase('parent_profile');
        $this->load->view('backend/index', $page_data);
    }

    function parent($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'parent';
        $data['page_title'] = get_phrase('student');
        $this->load->view('backend/index', $data);
    }

    function staff($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'staff';
        $data['page_title'] = get_phrase('staffs');
        $this->load->view('backend/index', $data);
    }

    function classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'class';
        $data['page_title'] = get_phrase('classes');
        $this->load->view('backend/index', $data);
    }

    function class_group($param1 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'class_group';
        $data['page_title'] = get_phrase('class_groups');
        $this->load->view('backend/index', $data);
    }

    function class_schedule($param1 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'class_schedule';
        $data['page_title'] = get_phrase('class_schedules');
        $this->load->view('backend/index', $data);
    }

    function course($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'course';
        $data['page_title'] = get_phrase('courses');
        $this->load->view('backend/index', $data);
    }

    function exam($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'exam';
        $data['page_title'] = get_phrase('exams');
        $this->load->view('backend/index', $data);
    }

    function transport($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'transport';
        $data['page_title'] = get_phrase('transports');
        $this->load->view('backend/index', $data);
    }

    function notice($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'notice';
        $data['page_title'] = get_phrase('notices');
        $this->load->view('backend/index', $data);
    }

    // MANAGE OWN PROFILE AND CHANGE PASSWORD
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'update_profile_info')
        {
            $data['name']       = $this->input->post('name');
            $data['username']   = $this->input->post('username');
            $data['email']      = $this->input->post('email');

            $this->db->where('student_id', $this->session->userdata('login_user_id'));
            $this->db->update('student', $data);

            if($_FILES['image']['name'] != '')
                //move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/student_image/' . $this->session->userdata('login_user_id') . '.jpg');

            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?student/manage_profile', 'refresh');
        }

        if ($param1 == 'change_password')
        {
            $current_password_input = sha1($this->input->post('password'));
            $new_password           = sha1($this->input->post('new_password'));
            $confirm_new_password   = sha1($this->input->post('confirm_new_password'));

            $current_password_db = $this->db->get_where('student', array('student_id' => $this->session->userdata('login_user_id')))->row()->password;

            if ($current_password_db == $current_password_input && $new_password == $confirm_new_password) {
                $this->db->where('student_id', $this->session->userdata('login_user_id'));
                $this->db->update('student', array('password' => $new_password));

                $this->session->set_flashdata('flash_message', get_phrase('password_updated_successfuly'));
                redirect(base_url() . 'index.php?student/manage_profile', 'refresh');
            } else {
                $this->session->set_flashdata('error_message', get_phrase('password_mismatch'));
                redirect(base_url() . 'index.php?student/manage_profile', 'refresh');
            }
        }

        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $this->load->view('backend/index', $page_data);
    }

    function get_class_groups($class_id = '')
    {
        $class_groups = $this->db->get_where('class_group', array('class_id' => $class_id))->result_array();

        echo '<option value="">' . get_phrase("select_a_group") . '</option>';
        foreach ($class_groups as $row) {
            echo '<option value="' . $row['class_group_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_students_by_group($class_group_id = '')
    {
        $students = $this->db->get_where('student', array('class_group_id' => $class_group_id))->result_array();

        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_courses_by_class($class_id = '')
    {
        $courses = $this->db->get_where('course', array('class_id' => $class_id))->result_array();

        echo '<option value="">' . get_phrase("select_a_course") . '</option>';
        foreach ($courses as $row) {
            echo '<option value="' . $row['course_id'] . '">' . $row['name'] . '</option>';
        }
    }
    
    function get_group_teachers($class_id = '')
    {
        $student_id = $this->db->get_where('class', array('class_id' => $class_id))->row()->student_id;
        $students   = $this->db->get_where('student', array('student_id !=' => $student_id))->result_array();

        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_tests($class_id = '', $class_group_id = '', $course_id = '')
    {
        $student_id     = $this->session->userdata('student_id');
        $class_tests    = $this->db->get_where('class_test', array('student_id' => $student_id, 'class_id' => $class_id, 'class_group_id' => $class_group_id, 'course_id' => $course_id))->result_array();

        echo '<option value="">' . get_phrase("select_a_class_test") . '</option>';
        foreach ($class_tests as $row) {
            echo '<option value="' . $row['class_test_id'] . '">' . $row['title'] . '</option>';
        }
    }
}





















