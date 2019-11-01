<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	// constructor
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	// default function
	public function index()
	{
		if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('teacher_login') == 1)
            redirect(base_url() . 'index.php?teacher/dashboard', 'refresh');
	}

    // TEACHER DASHBOARD
    function dashboard()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('dashboard');
        $this->load->view('backend/index', $page_data);
    }

    function student($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['class_code'] = $param1;
        $data['page_name']  = 'student';
        $data['page_title'] = get_phrase('students');
        $this->load->view('backend/index', $data);
    }

    // STUDENT PROFILE
    function student_profile($param1 = 'student_info', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
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
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $month          = $this->input->post('month');
        $year           = $this->input->post('year');
        $student_code   = $this->input->post('code');

        redirect(base_url().'index.php?teacher/student_profile/student_attendance_report/' . $student_code . '/' . $month . '/' . $year, 'refresh');
    }

    // MANAGE ATTENDANCE
    function attendance($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']     = 'attendance';
        $page_data['page_title']    = get_phrase('daily_attendance');
        $this->load->view('backend/index', $page_data);
    }

    function attendance_view($class_code = '', $class_group_code = '', $date = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $class_id       = $this->crud_model->get_field_by_code('class', 'class_id', $class_code);
        $class_group_id = $this->crud_model->get_field_by_code('class_group', 'class_group_id', $class_group_code);
        $class_name     = $this->crud_model->get_field_by_code('class', 'name', $class_code);
        $group_name     = $this->crud_model->get_field_by_code('class_group', 'name', $class_group_code);

        $page_data['class_id']          = $class_id;
        $page_data['class_group_id']    = $class_group_id;
        $page_data['date']              = $date;
        $page_data['page_name']         = 'attendance_view';
        $page_data['page_title']        = get_phrase('manage_attendance_of_class') . ' ' . $class_name . ' : ' . get_phrase('group') . ' ' . $group_name;
        $this->load->view('backend/index', $page_data);
    }

    function attendance_selector()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['class_id']       = $this->input->post('class_id');
        $data['class_group_id'] = $this->input->post('class_group_id');
        $data['date']           = strtotime($this->input->post('date'));
        $data['session']        = $this->db->get_where('settings', array('type' => 'session'))->row()->description;

        $query = $this->db->get_where('student_attendance', array('class_id' => $data['class_id'], 'class_group_id' => $data['class_group_id'], 'session' => $data['session'], 'date' => $data['date']));

        if($query->num_rows() < 1) {
            $students = $this->db->get_where('student', array('class_id' => $data['class_id'], 'class_group_id' => $data['class_group_id'], 'session' => $data['session']))->result_array();

            foreach($students as $row) {
                $attn_data['code']              = substr(md5(rand(0, 100000)), 0, 5);
                $attn_data['student_id']        = $row['student_id'];
                $attn_data['class_id']          = $data['class_id'];
                $attn_data['class_group_id']    = $data['class_group_id'];
                $attn_data['date']              = $data['date'];
                $attn_data['session']           = $data['session'];

                $this->db->insert('student_attendance', $attn_data);
            }
        }

        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $data['class_id']);
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $data['class_group_id']);

        redirect(base_url().'index.php?teacher/attendance_view/' . $class_code . '/' . $class_group_code . '/' . $data['date'], 'refresh');
    }

    function attendance_update($class_code = '', $class_group_code = '', $date = '')
    {
        $class_id       = $this->crud_model->get_field_by_code('class', 'class_id', $class_code);
        $class_group_id = $this->crud_model->get_field_by_code('class_group', 'class_group_id', $class_group_code);

        $attendances    = $this->db->get_where('student_attendance', array('class_id' => $class_id, 'class_group_id' => $class_group_id, 'date' => $date))->result_array();

        foreach($attendances as $row) {

            $data['status']         = $this->input->post('status_' . $row['student_attendance_id']);

            if($data['status'] == 0)
                $data['absence_reason'] = $this->input->post('absence_reason_' . $row['student_attendance_id']);
            else
                $data['absence_reason'] = '';

            $this->db->update('student_attendance', $data, array('student_attendance_id' => $row['student_attendance_id']));
        }

        $this->session->set_flashdata('flash_message', get_phrase('attendance_updated_successfully'));
        redirect(base_url().'index.php?teacher/attendance_view/' . $class_code . '/' . $class_group_code . '/' . $date, 'refresh');
    }

    function get_class_groups($class_id = '')
    {
        $class_groups = $this->db->get_where('class_group', array('class_id' => $class_id))->result_array();

        echo '<option value="">' . get_phrase("select_a_group") . '</option>';
        foreach ($class_groups as $row) {
            echo '<option value="' . $row['class_group_id'] . '">' . $row['name'] . '</option>';
        }
    }

    // PROGRESS REPORT
    function progress_report()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'progress_report';
        $page_data['page_title'] = get_phrase('progress_report');
        $this->load->view('backend/index', $page_data);
    }

    function progress_report_selector()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $this->input->post('class_id'));
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $this->input->post('class_group_id'));
        $student_code       = $this->crud_model->get_field_by_id('student', 'code', $this->input->post('student_id'));

        redirect(base_url().'index.php?teacher/progress_report_view/' . $class_code . '/' . $class_group_code . '/' . $student_code, 'refresh');
    }

    function progress_report_view($class_code = '', $class_group_code = '', $student_code = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $class_id       = $this->crud_model->get_field_by_code('class', 'class_id', $class_code);
        $class_group_id = $this->crud_model->get_field_by_code('class_group', 'class_group_id', $class_group_code);
        $student_id     = $this->crud_model->get_field_by_code('student', 'student_id', $student_code);

        $page_data['class_id']          = $class_id;
        $page_data['class_group_id']    = $class_group_id;
        $page_data['student_id']        = $student_id;
        $page_data['page_name']         = 'progress_report_view';
        $page_data['page_title']        = get_phrase('progress_report');
        $this->load->view('backend/index', $page_data);
    }

    function get_students_by_group($class_group_id = '')
    {
        $students = $this->db->get_where('student', array('class_group_id' => $class_group_id))->result_array();

        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['name'] . '</option>';
        }
    }

    // TEACHER PROFILE
    function teacher_profile($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'teacher_profile';
        $page_data['page_title']    = get_phrase('teacher_profile');
        $this->load->view('backend/index', $page_data);
    }

    function teacher($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'teacher';
        $data['page_title'] = get_phrase('teachers');
        $this->load->view('backend/index', $data);
    }

    // PARENT PROFILE
    function parent_profile($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'parent_profile';
        $page_data['page_title']    = get_phrase('parent_profile');
        $this->load->view('backend/index', $page_data);
    }

    function parent($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'parent';
        $data['page_title'] = get_phrase('parents');
        $this->load->view('backend/index', $data);
    }

    function staff($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'staff';
        $data['page_title'] = get_phrase('staffs');
        $this->load->view('backend/index', $data);
    }

    function classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'class';
        $data['page_title'] = get_phrase('classes');
        $this->load->view('backend/index', $data);
    }

    function class_group($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['class_code'] = $param1;
        $data['page_name']  = 'class_group';
        $data['page_title'] = get_phrase('class_groups');
        $this->load->view('backend/index', $data);
    }

    function class_schedule($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['class_code'] = $param1;
        $data['page_name']  = 'class_schedule';
        $data['page_title'] = get_phrase('class_schedules');
        $this->load->view('backend/index', $data);
    }

    function get_courses_by_class($class_id = '')
    {
        $courses = $this->db->get_where('course', array('class_id' => $class_id))->result_array();

        echo '<option value="">' . get_phrase("select_a_course") . '</option>';
        foreach ($courses as $row) {
            echo '<option value="' . $row['course_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function course($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        if($param1 == '') {
            $first_class        = $this->db->get('class')->row_array();
            $data['class_code'] = $first_class['code'];
        } else
            $data['class_code'] = $param1;

        $data['page_name']  = 'course';
        $data['page_title'] = get_phrase('courses');
        $this->load->view('backend/index', $data);
    }

    function exam($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'exam';
        $data['page_title'] = get_phrase('exams');
        $this->load->view('backend/index', $data);
    }

    // MANAGE EXAM SCORE
    function exam_score($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']     = 'exam_score';
        $page_data['page_title']    = get_phrase('evaluations');
        $this->load->view('backend/index', $page_data);
    }

    function exam_score_view($exam_code = '', $class_code = '', $class_group_code = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $exam_id        = $this->crud_model->get_field_by_code('exam', 'exam_id', $exam_code);
        $class_id       = $this->crud_model->get_field_by_code('class', 'class_id', $class_code);
        $class_group_id = $this->crud_model->get_field_by_code('class_group', 'class_group_id', $class_group_code);

        $class_name     = $this->crud_model->get_field_by_id('class', 'name', $class_id);
        $group_name     = $this->crud_model->get_field_by_id('class_group', 'name', $class_group_id);

        $page_data['exam_id']           = $exam_id;
        $page_data['class_id']          = $class_id;
        $page_data['class_group_id']    = $class_group_id;
        $page_data['page_name']         = 'exam_score_view';
        $page_data['page_title']        = get_phrase('evaluations_of_class') . ' ' . $class_name . ' : ' . get_phrase('group') . ' ' . $group_name;
        $this->load->view('backend/index', $page_data);
    }

    function exam_score_selector()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $exam_code          = $this->crud_model->get_field_by_id('exam', 'code', $this->input->post('exam_id'));
        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $this->input->post('class_id'));
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $this->input->post('class_group_id'));

        redirect(base_url().'index.php?teacher/exam_score_view/' . $exam_code . '/' . $class_code . '/' . $class_group_code, 'refresh');
    }

    function exam_score_update()
    {   
        $exam_id        = $this->input->post('exam_id');
        $class_id       = $this->input->post('class_id');
        $class_group_id = $this->input->post('class_group_id');

        $students       = $this->db->get_where('student', array('class_id' => $class_id, 'class_group_id' => $class_group_id))->result_array();
        $courses        = $this->db->get_where('course', array('class_id' => $class_id))->result_array();
        
        if(!empty($students) && !empty($courses)) {
            foreach($students as $row) {
                foreach($courses as $row2) {
                    $data['obtained_score'] = $this->input->post('obtained_score_' . $row['student_id'] . '_' . $row2['course_id']);

                    $query = $this->db->get_where('exam_score', array('exam_id' => $exam_id, 'student_id' => $row['student_id'], 'course_id' => $row2['course_id']));
                    
                    if($query->num_rows() == 0) {
                        $data['code']           = substr(md5(rand(0, 100000)), 0, 5);
                        $data['exam_id']        = $this->input->post('exam_id');
                        $data['student_id']     = $row['student_id'];
                        $data['course_id']      = $row2['course_id'];
                        $data['session']        = $this->db->get_where('settings', array('type' => 'session'))->row()->description;
                        $data['date_added']     = time();
                        $data['last_modified']  = time();

                        $this->db->insert('exam_score', $data);
                    }
                    else {
                        $exam_score_id = $this->db->get_where('exam_score', array('exam_id' => $exam_id, 'student_id' => $row['student_id'], 'course_id' => $row2['course_id']))->row()->exam_score_id;

                        $data['last_modified'] = time();

                        $this->db->update('exam_score', $data, array('exam_score_id' => $exam_score_id));
                    }
                }
            }
            $this->session->set_flashdata('flash_message', get_phrase('exam_scores_updated_successfully'));
        } else
            $this->session->set_flashdata('flash_message', get_phrase('evaluation_failed'));
        
        $exam_code          = $this->crud_model->get_field_by_id('exam', 'code', $this->input->post('exam_id'));
        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $this->input->post('class_id'));
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $this->input->post('class_group_id'));

        redirect(base_url().'index.php?teacher/exam_score_view/' . $exam_code . '/' . $class_code . '/' . $class_group_code, 'refresh');
    }

    function transport($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'transport';
        $data['page_title'] = get_phrase('transports');
        $this->load->view('backend/index', $data);
    }

    function notice($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['page_name']  = 'notice';
        $data['page_title'] = get_phrase('notices');
        $this->load->view('backend/index', $data);
    }

    // MANAGE OWN PROFILE AND CHANGE PASSWORD
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'update_profile_info')
        {
            $data['name']       = $this->input->post('name');
            $data['username']   = $this->input->post('username');
            $data['email']      = $this->input->post('email');

            $this->db->where('teacher_id', $this->session->userdata('login_user_id'));
            $this->db->update('teacher', $data);

            if($_FILES['image']['name'] != '')
                //move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/teacher_image/' . $this->session->userdata('login_user_id') . '.jpg');

            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?teacher/manage_profile', 'refresh');
        }

        if ($param1 == 'change_password')
        {
            $current_password_input = sha1($this->input->post('password'));
            $new_password           = sha1($this->input->post('new_password'));
            $confirm_new_password   = sha1($this->input->post('confirm_new_password'));

            $current_password_db = $this->db->get_where('teacher', array('teacher_id' => $this->session->userdata('login_user_id')))->row()->password;

            if ($current_password_db == $current_password_input && $new_password == $confirm_new_password) {
                $this->db->where('teacher_id', $this->session->userdata('login_user_id'));
                $this->db->update('teacher', array('password' => $new_password));

                $this->session->set_flashdata('flash_message', get_phrase('password_updated_successfuly'));
                redirect(base_url() . 'index.php?teacher/manage_profile', 'refresh');
            } else {
                $this->session->set_flashdata('error_message', get_phrase('password_mismatch'));
                redirect(base_url() . 'index.php?teacher/manage_profile', 'refresh');
            }
        }

        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $this->load->view('backend/index', $page_data);
    }
    
    function get_group_teachers($class_id = '')
    {
        $teacher_id = $this->db->get_where('class', array('class_id' => $class_id))->row()->teacher_id;
        $teachers   = $this->db->get_where('teacher', array('teacher_id !=' => $teacher_id))->result_array();

        foreach ($teachers as $row) {
            echo '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
        }
    }

    // CLASS TEST ADD VIEW PAGE
    function class_test_add_view()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'class_test_add';
        $page_data['page_title'] = get_phrase('add_class_test');
        $this->load->view('backend/index', $page_data);
    }

    // CLASS TEST EDIT VIEW PAGE
    function class_test_edit_view($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'class_test_edit';
        $page_data['page_title']    = get_phrase('edit_class_test');
        $this->load->view('backend/index', $page_data);
    }

    // CLASS TEST DETAILS
    function class_test_details($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'class_test_details';
        $page_data['page_title']    = get_phrase('class_test_details');
        $this->load->view('backend/index', $page_data);
    }

    function class_test($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $class_code = $this->crud_model->create_class_test();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?teacher/class_test/' . $class_code, 'refresh');
        }

        if ($param1 == "update")
        {
            $class_code = $this->crud_model->update_class_test($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?teacher/class_test/' . $class_code, 'refresh');
        }

        if ($param1 == "delete")
        {
            $class_code = $this->crud_model->delete_class_test($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?teacher/class_test/' . $class_code, 'refresh');
        }

        $data['class_code'] = $param1;
        $data['page_name']  = 'class_test';
        $data['page_title'] = get_phrase('class_tests');
        $this->load->view('backend/index', $data);
    }

    // MANAGE CLASS TEST SCORE
    function class_test_score($param1 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']     = 'class_test_score';
        $page_data['page_title']    = get_phrase('manage_class_test_scores');
        $this->load->view('backend/index', $page_data);
    }

    function class_test_score_selector()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $this->input->post('class_id'));
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $this->input->post('class_group_id'));
        $course_code        = $this->crud_model->get_field_by_id('course', 'code', $this->input->post('course_id'));
        $class_test_code    = $this->crud_model->get_field_by_id('class_test', 'code', $this->input->post('class_test_id'));

        redirect(base_url().'index.php?teacher/class_test_score_view/' . $class_code . '/' . $class_group_code . '/' . $course_code . '/' . $class_test_code, 'refresh');
    }

    function class_test_score_view($class_code = '', $class_group_code = '', $course_code = '', $class_test_code = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $class_id       = $this->crud_model->get_field_by_code('class', 'class_id', $class_code);
        $class_group_id = $this->crud_model->get_field_by_code('class_group', 'class_group_id', $class_group_code);
        $course_id      = $this->crud_model->get_field_by_code('course', 'course_id', $course_code);
        $class_test_id  = $this->crud_model->get_field_by_code('class_test', 'class_test_id', $class_test_code);

        $class_name     = $this->crud_model->get_field_by_id('class', 'name', $class_id);
        $group_name     = $this->crud_model->get_field_by_id('class_group', 'name', $class_group_id);

        $page_data['class_id']          = $class_id;
        $page_data['class_group_id']    = $class_group_id;
        $page_data['course_id']         = $course_id;
        $page_data['class_test_id']     = $class_test_id;
        $page_data['page_name']         = 'class_test_score_view';
        $page_data['page_title']        = get_phrase('manage_class_test_scores_of_class') . ' ' . $class_name . ' : ' . get_phrase('group') . ' ' . $group_name;
        $this->load->view('backend/index', $page_data);
    }

    function class_test_score_update()
    {   
        $class_id       = $this->input->post('class_id');
        $class_group_id = $this->input->post('class_group_id');
        $course_id      = $this->input->post('course_id');
        $class_test_id  = $this->input->post('class_test_id');

        $students       = $this->db->get_where('student', array('class_id' => $class_id, 'class_group_id' => $class_group_id))->result_array();
        
        if(!empty($students)) {
            foreach($students as $row) {
                $data['score'] = $this->input->post('score_' . $row['student_id']);

                $query = $this->db->get_where('class_test_score', array('class_test_id' => $class_test_id, 'student_id' => $row['student_id']));
                
                if($query->num_rows() == 0) {
                    $data['code']           = substr(md5(rand(0, 100000)), 0, 5);
                    $data['class_test_id']  = $class_test_id;
                    $data['student_id']     = $row['student_id'];
                    $data['session']        = $this->db->get_where('settings', array('type' => 'session'))->row()->description;
                    $data['date_added']     = time();
                    $data['last_modified']  = time();

                    $this->db->insert('class_test_score', $data);
                }
                else {
                    $class_test_score_id = $this->db->get_where('class_test_score', array('class_test_id' => $class_test_id, 'student_id' => $row['student_id']))->row()->class_test_score_id;

                    $data['last_modified'] = time();

                    $this->db->update('class_test_score', $data, array('class_test_score_id' => $class_test_score_id));
                }
            }
            $this->session->set_flashdata('flash_message', get_phrase('class_test_scores_updated_successfully'));
        } else
            $this->session->set_flashdata('flash_message', get_phrase('class_test_score_update_failed'));
        
        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $this->input->post('class_id'));
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $this->input->post('class_group_id'));
        $course_code        = $this->crud_model->get_field_by_id('course', 'code', $this->input->post('course_id'));
        $class_test_code    = $this->crud_model->get_field_by_id('class_test', 'code', $this->input->post('class_test_id'));
        
        redirect(base_url().'index.php?teacher/class_test_score_view/' . $class_code . '/' . $class_group_code . '/' . $course_code . '/' . $class_test_code, 'refresh');
    }

    function get_class_tests($class_id = '', $class_group_id = '', $course_id = '')
    {
        $teacher_id     = $this->session->userdata('teacher_id');
        $class_tests    = $this->db->get_where('class_test', array('teacher_id' => $teacher_id, 'class_id' => $class_id, 'class_group_id' => $class_group_id, 'course_id' => $course_id))->result_array();

        echo '<option value="">' . get_phrase("select_a_class_test") . '</option>';
        foreach ($class_tests as $row) {
            echo '<option value="' . $row['class_test_id'] . '">' . $row['title'] . '</option>';
        }
    }
}





















