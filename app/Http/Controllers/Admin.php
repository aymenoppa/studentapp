<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	// constructor
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	// default function
	public function index()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
	}

    // ADMIN DASHBOARD
    function dashboard()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('dashboard');
        $this->load->view('backend/index', $page_data);
    }

    // STUDENT ADD VIEW PAGE
    function student_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'student_add';
        $page_data['page_title'] = get_phrase('add_student');
        $this->load->view('backend/index', $page_data);
    }

    // STUDENT EDIT VIEW PAGE
    function student_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']  		= $param1;
        $page_data['page_name']  	= 'student_edit';
        $page_data['page_title']	= get_phrase('edit_student');
        $this->load->view('backend/index', $page_data);
    }

    // STUDENT PROFILE
    function student_profile($param1 = 'student_info', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['inner_page']    = $param1;
        $page_data['code']          = $param2;
        $page_data['month']         = $param3;
        $page_data['year']          = $param4;
        $page_data['page_name']     = 'student_profile';
        $page_data['page_title']    = get_phrase('student_profile');
        $this->load->view('backend/index', $page_data);
    }

    function student($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $class_code = $this->crud_model->create_student();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/student/' . $class_code, 'refresh');
        }

        if ($param1 == "update")
        {
            $class_code = $this->crud_model->update_student($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/student/' . $class_code, 'refresh');
        }

        if ($param1 == "delete")
        {
            $class_code = $this->crud_model->delete_student($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/student/' . $class_code, 'refresh');
        }

        if($param1 == '') {
            $first_class        = $this->db->get('class')->row_array();
            $data['class_code'] = $first_class['code'];
        } else
            $data['class_code'] = $param1;

        $data['page_name']  = 'student';
        $data['page_title'] = get_phrase('students');
        $this->load->view('backend/index', $data);
    }

    function get_class_groups($class_id = '')
    {
        $class_groups = $this->db->get_where('class_group', array('class_id' => $class_id))->result_array();

        echo '<option value="">' . get_phrase("select_a_group") . '</option>';
        foreach ($class_groups as $row) {
            echo '<option value="' . $row['class_group_id'] . '">' . $row['name'] . '</option>';
        }
    }

    // TEACHER ADD VIEW PAGE
    function teacher_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'teacher_add';
        $page_data['page_title'] = get_phrase('add_teacher');
        $this->load->view('backend/index', $page_data);
    }

    // TEACHER EDIT VIEW PAGE
    function teacher_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'teacher_edit';
        $page_data['page_title']    = get_phrase('edit_teacher');
        $this->load->view('backend/index', $page_data);
    }

    // TEACHER PROFILE
    function teacher_profile($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'teacher_profile';
        $page_data['page_title']    = get_phrase('teacher_profile');
        $this->load->view('backend/index', $page_data);
    }

    function teacher($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $this->crud_model->create_teacher();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/teacher', 'refresh');
        }

        if ($param1 == "update")
        {
            $this->crud_model->update_teacher($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/teacher', 'refresh');
        }

        if ($param1 == "delete")
        {
            $this->crud_model->delete_teacher($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/teacher', 'refresh');
        }

        $data['page_name']  = 'teacher';
        $data['page_title'] = get_phrase('teachers');
        $this->load->view('backend/index', $data);
    }

    // PARENT ADD VIEW PAGE
    function parent_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'parent_add';
        $page_data['page_title'] = get_phrase('add_parent');
        $this->load->view('backend/index', $page_data);
    }

    // PARENT EDIT VIEW PAGE
    function parent_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'parent_edit';
        $page_data['page_title']    = get_phrase('edit_parent');
        $this->load->view('backend/index', $page_data);
    }

    // PARENT PROFILE
    function parent_profile($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'parent_profile';
        $page_data['page_title']    = get_phrase('parent_profile');
        $this->load->view('backend/index', $page_data);
    }

    function parent($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $this->crud_model->create_parent();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/parent', 'refresh');
        }

        if ($param1 == "update")
        {
            $this->crud_model->update_parent($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/parent', 'refresh');
        }

        if ($param1 == "delete")
        {
            $this->crud_model->delete_parent($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/parent', 'refresh');
        }

        $data['page_name']  = 'parent';
        $data['page_title'] = get_phrase('parents');
        $this->load->view('backend/index', $data);
    }

    // CLASS ADD VIEW PAGE
    function class_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'class_add';
        $page_data['page_title'] = get_phrase('add_class');
        $this->load->view('backend/index', $page_data);
    }

    // CLASS EDIT VIEW PAGE
    function class_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'class_edit';
        $page_data['page_title']    = get_phrase('edit_class');
        $this->load->view('backend/index', $page_data);
    }

    function classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $this->crud_model->create_class();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/classes', 'refresh');
        }

        if ($param1 == "update")
        {
            $this->crud_model->update_class($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/classes', 'refresh');
        }

        if ($param1 == "delete")
        {
            $this->crud_model->delete_class($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/classes', 'refresh');
        }

        $data['page_name']  = 'class';
        $data['page_title'] = get_phrase('classes');
        $this->load->view('backend/index', $data);
    }

    // CLASS GROUP ADD VIEW PAGE
    function class_group_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'class_group_add';
        $page_data['page_title'] = get_phrase('add_class_group');
        $this->load->view('backend/index', $page_data);
    }

    // CLASS GROUP EDIT VIEW PAGE
    function class_group_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'class_group_edit';
        $page_data['page_title']    = get_phrase('edit_class_group');
        $this->load->view('backend/index', $page_data);
    }

    function class_group($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $class_code = $this->crud_model->create_class_group();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/class_group/' . $class_code, 'refresh');
        }

        if ($param1 == "update")
        {
            $class_code = $this->crud_model->update_class_group($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/class_group/' . $class_code, 'refresh');
        }

        if ($param1 == "delete")
        {
            $class_code = $this->crud_model->delete_class_group($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/class_group/' . $class_code, 'refresh');
        }

        if($param1 == '') {
            $first_class        = $this->db->get('class')->row_array();
            $data['class_code'] = $first_class['code'];
        } else
            $data['class_code'] = $param1;

        $data['page_name']  = 'class_group';
        $data['page_title'] = get_phrase('class_groups');
        $this->load->view('backend/index', $data);
    }

    function get_group_teachers($class_id = '')
    {
        $teacher_id = $this->db->get_where('class', array('class_id' => $class_id))->row()->teacher_id;
        $teachers   = $this->db->get_where('teacher', array('teacher_id !=' => $teacher_id))->result_array();

        foreach ($teachers as $row) {
            echo '<option value="' . $row['teacher_id'] . '">' . $row['name'] . '</option>';
        }
    }

    // STAFF ROLE ADD VIEW PAGE
    function staff_role_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'staff_role_add';
        $page_data['page_title'] = get_phrase('add_staff_role');
        $this->load->view('backend/index', $page_data);
    }

    // STAFF ROLE EDIT VIEW PAGE
    function staff_role_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'staff_role_edit';
        $page_data['page_title']    = get_phrase('edit_staff_role');
        $this->load->view('backend/index', $page_data);
    }

    function staff_role($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $this->crud_model->create_staff_role();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/staff_role', 'refresh');
        }

        if ($param1 == "update")
        {
            $this->crud_model->update_staff_role($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/staff_role', 'refresh');
        }

        if ($param1 == "delete")
        {
            $this->crud_model->delete_staff_role($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/staff_role', 'refresh');
        }

        $data['page_name']  = 'staff_role';
        $data['page_title'] = get_phrase('staff_roles');
        $this->load->view('backend/index', $data);
    }

    // STAFF ADD VIEW PAGE
    function staff_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'staff_add';
        $page_data['page_title'] = get_phrase('add_staff');
        $this->load->view('backend/index', $page_data);
    }

    // STAFF EDIT VIEW PAGE
    function staff_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'staff_edit';
        $page_data['page_title']    = get_phrase('edit_staff');
        $this->load->view('backend/index', $page_data);
    }

    function staff($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $this->crud_model->create_staff();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/staff', 'refresh');
        }

        if ($param1 == "update")
        {
            $this->crud_model->update_staff($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/staff', 'refresh');
        }

        if ($param1 == "delete")
        {
            $this->crud_model->delete_staff($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/staff', 'refresh');
        }

        $data['page_name']  = 'staff';
        $data['page_title'] = get_phrase('staffs');
        $this->load->view('backend/index', $data);
    }

    // COURSE ADD VIEW PAGE
    function course_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'course_add';
        $page_data['page_title'] = get_phrase('add_course');
        $this->load->view('backend/index', $page_data);
    }

    // COURSE EDIT VIEW PAGE
    function course_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'course_edit';
        $page_data['page_title']    = get_phrase('edit_course');
        $this->load->view('backend/index', $page_data);
    }

    function course($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $class_code = $this->crud_model->create_course();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/course/' . $class_code, 'refresh');
        }

        if ($param1 == "update")
        {
            $class_code = $this->crud_model->update_course($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/course/' . $class_code, 'refresh');
        }

        if ($param1 == "delete")
        {
            $class_code = $this->crud_model->delete_course($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/course/' . $class_code, 'refresh');
        }

        if($param1 == '') {
            $first_class        = $this->db->get('class')->row_array();
            $data['class_code'] = $first_class['code'];
        } else
            $data['class_code'] = $param1;

        $data['page_name']  = 'course';
        $data['page_title'] = get_phrase('courses');
        $this->load->view('backend/index', $data);
    }

    // EXAM ADD VIEW PAGE
    function exam_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'exam_add';
        $page_data['page_title'] = get_phrase('add_exam');
        $this->load->view('backend/index', $page_data);
    }

    // EXAM EDIT VIEW PAGE
    function exam_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'exam_edit';
        $page_data['page_title']    = get_phrase('edit_exam');
        $this->load->view('backend/index', $page_data);
    }

    function exam($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $class_code = $this->crud_model->create_exam();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/exam/' . $class_code, 'refresh');
        }

        if ($param1 == "update")
        {
            $class_code = $this->crud_model->update_exam($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/exam/' . $class_code, 'refresh');
        }

        if ($param1 == "delete")
        {
            $class_code = $this->crud_model->delete_exam($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/exam/' . $class_code, 'refresh');
        }

        $data['page_name']  = 'exam';
        $data['page_title'] = get_phrase('exams');
        $this->load->view('backend/index', $data);
    }

    // TRANSPORT ADD VIEW PAGE
    function transport_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'transport_add';
        $page_data['page_title'] = get_phrase('add_transport');
        $this->load->view('backend/index', $page_data);
    }

    // TRANSPORT EDIT VIEW PAGE
    function transport_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'transport_edit';
        $page_data['page_title']    = get_phrase('edit_transport');
        $this->load->view('backend/index', $page_data);
    }

    function transport($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $class_code = $this->crud_model->create_transport();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/transport/' . $class_code, 'refresh');
        }

        if ($param1 == "update")
        {
            $class_code = $this->crud_model->update_transport($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/transport/' . $class_code, 'refresh');
        }

        if ($param1 == "delete")
        {
            $class_code = $this->crud_model->delete_transport($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/transport/' . $class_code, 'refresh');
        }

        $data['page_name']  = 'transport';
        $data['page_title'] = get_phrase('transports');
        $this->load->view('backend/index', $data);
    }

    // NOTICE ADD VIEW PAGE
    function notice_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'notice_add';
        $page_data['page_title'] = get_phrase('add_notice');
        $this->load->view('backend/index', $page_data);
    }

    // NOTICE EDIT VIEW PAGE
    function notice_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'notice_edit';
        $page_data['page_title']    = get_phrase('edit_notice');
        $this->load->view('backend/index', $page_data);
    }

    function notice($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $class_code = $this->crud_model->create_notice();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/notice/' . $class_code, 'refresh');
        }

        if ($param1 == "update")
        {
            $class_code = $this->crud_model->update_notice($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/notice/' . $class_code, 'refresh');
        }

        if ($param1 == "delete")
        {
            $class_code = $this->crud_model->delete_notice($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/notice/' . $class_code, 'refresh');
        }

        $data['page_name']  = 'notice';
        $data['page_title'] = get_phrase('notices');
        $this->load->view('backend/index', $data);
    }

    // EXPENSE CATEGORY ADD VIEW PAGE
    function expense_category_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'expense_category_add';
        $page_data['page_title'] = get_phrase('add_expense_category');
        $this->load->view('backend/index', $page_data);
    }

    // EXPENSE CATEGORY EDIT VIEW PAGE
    function expense_category_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'expense_category_edit';
        $page_data['page_title']    = get_phrase('edit_expense_category');
        $this->load->view('backend/index', $page_data);
    }

    function expense_category($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $this->crud_model->create_expense_category();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/expense_category', 'refresh');
        }

        if ($param1 == "update")
        {
            $this->crud_model->update_expense_category($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/expense_category', 'refresh');
        }

        if ($param1 == "delete")
        {
            $this->crud_model->delete_expense_category($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/expense_category', 'refresh');
        }

        $data['page_name']  = 'expense_category';
        $data['page_title'] = get_phrase('expense_categories');
        $this->load->view('backend/index', $data);
    }

    // MANAGE ATTENDANCE
    function attendance($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']     = 'attendance';
        $page_data['page_title']    = get_phrase('daily_attendance');
        $this->load->view('backend/index', $page_data);
    }

    function attendance_view($class_code = '', $class_group_code = '', $date = '')
    {
        if ($this->session->userdata('admin_login') != 1)
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
        if ($this->session->userdata('admin_login') != 1)
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

        redirect(base_url().'index.php?admin/attendance_view/' . $class_code . '/' . $class_group_code . '/' . $data['date'], 'refresh');
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
        redirect(base_url().'index.php?admin/attendance_view/' . $class_code . '/' . $class_group_code . '/' . $date, 'refresh');
    }

    // MANAGE EXAM SCORE
    function exam_score($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']     = 'exam_score';
        $page_data['page_title']    = get_phrase('evaluations');
        $this->load->view('backend/index', $page_data);
    }

    function exam_score_view($exam_code = '', $class_code = '', $class_group_code = '')
    {
        if ($this->session->userdata('admin_login') != 1)
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
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $exam_code          = $this->crud_model->get_field_by_id('exam', 'code', $this->input->post('exam_id'));
        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $this->input->post('class_id'));
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $this->input->post('class_group_id'));

        redirect(base_url().'index.php?admin/exam_score_view/' . $exam_code . '/' . $class_code . '/' . $class_group_code, 'refresh');
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

        redirect(base_url().'index.php?admin/exam_score_view/' . $exam_code . '/' . $class_code . '/' . $class_group_code, 'refresh');
    }

    // CLASS SCHEDULE ADD VIEW PAGE
    function class_schedule_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'class_schedule_add';
        $page_data['page_title'] = get_phrase('add_class_schedule');
        $this->load->view('backend/index', $page_data);
    }

    // CLASS SCHEDULE EDIT VIEW PAGE
    function class_schedule_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'class_schedule_edit';
        $page_data['page_title']    = get_phrase('edit_class_schedule');
        $this->load->view('backend/index', $page_data);
    }

    function class_schedule($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $class_code = $this->crud_model->create_class_schedule();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/class_schedule/' . $class_code, 'refresh');
        }

        if ($param1 == "update")
        {
            $class_code = $this->crud_model->update_class_schedule($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/class_schedule/' . $class_code, 'refresh');
        }

        if ($param1 == "delete")
        {
            $class_code = $this->crud_model->delete_class_schedule($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/class_schedule/' . $class_code, 'refresh');
        }

        if($param1 == '') {
            $first_class        = $this->db->get('class')->row_array();
            $data['class_code'] = $first_class['code'];
        } else
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

    // INVOICE ADD VIEW PAGE
    function invoice_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'invoice_add';
        $page_data['page_title'] = get_phrase('add_invoice');
        $this->load->view('backend/index', $page_data);
    }

    // MASS INVOICE ADD VIEW PAGE
    function invoice_mass_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'invoice_mass_add';
        $page_data['page_title'] = get_phrase('add_mass_invoice');
        $this->load->view('backend/index', $page_data);
    }

    // INVOICE EDIT VIEW PAGE
    function invoice_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'invoice_edit';
        $page_data['page_title']    = get_phrase('edit_invoice');
        $this->load->view('backend/index', $page_data);
    }

    // INVOICE DETAILS VIEW PAGE
    function invoice_details_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'invoice_details';
        $page_data['page_title']    = get_phrase('invoice_details');
        $this->load->view('backend/index', $page_data);
    }

    function invoice($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $this->crud_model->create_invoice();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        }

        if ($param1 == "create_mass_invoice")
        {
            $this->crud_model->create_mass_invoice();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        }

        if ($param1 == "update")
        {
            $this->crud_model->update_invoice($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        }

        if ($param1 == "delete")
        {
            $this->crud_model->delete_invoice($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        }

        $data['page_name']  = 'invoice';
        $data['page_title'] = get_phrase('student_fees');
        $this->load->view('backend/index', $data);
    }

    function get_students_by_class($class_id = '')
    {
        $students = $this->db->get_where('student', array('class_id' => $class_id))->result_array();

        foreach ($students as $row) {
            echo '<option value="' . $row['student_id'] . '">' . $row['name'] . '</option>';
        }
    }

    // EXPENSE ADD VIEW PAGE
    function expense_add_view()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'expense_add';
        $page_data['page_title'] = get_phrase('add_expense');
        $this->load->view('backend/index', $page_data);
    }

    // EXPENSE EDIT VIEW PAGE
    function expense_edit_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'expense_edit';
        $page_data['page_title']    = get_phrase('edit_expense');
        $this->load->view('backend/index', $page_data);
    }

    // EXPENSE DETAILS VIEW PAGE
    function expense_details_view($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['code']          = $param1;
        $page_data['page_name']     = 'expense_details';
        $page_data['page_title']    = get_phrase('expense_details');
        $this->load->view('backend/index', $page_data);
    }

    function expense($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == "create")
        {
            $this->crud_model->create_expense();
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        if ($param1 == "update")
        {
            $this->crud_model->update_expense($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        if ($param1 == "delete")
        {
            $this->crud_model->delete_expense($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        $data['page_name']  = 'expense';
        $data['page_title'] = get_phrase('school_expenses');
        $this->load->view('backend/index', $data);
    }

    function attendance_report_selector()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $month          = $this->input->post('month');
        $year           = $this->input->post('year');
        $student_code   = $this->input->post('code');

        redirect(base_url().'index.php?admin/student_profile/student_attendance_report/' . $student_code . '/' . $month . '/' . $year, 'refresh');
    }

    // PROGRESS REPORT
    function progress_report()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'progress_report';
        $page_data['page_title'] = get_phrase('progress_report');
        $this->load->view('backend/index', $page_data);
    }

    function progress_report_selector()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $this->input->post('class_id'));
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $this->input->post('class_group_id'));
        $student_code       = $this->crud_model->get_field_by_id('student', 'code', $this->input->post('student_id'));

        redirect(base_url().'index.php?admin/progress_report_view/' . $class_code . '/' . $class_group_code . '/' . $student_code, 'refresh');
    }

    function progress_report_view($class_code = '', $class_group_code = '', $student_code = '')
    {
        if ($this->session->userdata('admin_login') != 1)
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

    // MANAGE OWN PROFILE AND CHANGE PASSWORD
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'update_profile_info')
        {
            $data['name']       = $this->input->post('name');
            $data['username']   = $this->input->post('username');
            $data['email']      = $this->input->post('email');

            $this->db->where('admin_id', $this->session->userdata('login_user_id'));
            $this->db->update('admin', $data);

            if($_FILES['image']['name'] != '')
                //move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/admin_image/' . $this->session->userdata('login_user_id') . '.jpg');

            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/manage_profile', 'refresh');
        }

        if ($param1 == 'change_password')
        {
            $current_password_input = sha1($this->input->post('password'));
            $new_password           = sha1($this->input->post('new_password'));
            $confirm_new_password   = sha1($this->input->post('confirm_new_password'));

            $current_password_db = $this->db->get_where('admin', array('admin_id' => $this->session->userdata('login_user_id')))->row()->password;

            if ($current_password_db == $current_password_input && $new_password == $confirm_new_password) {
                $this->db->where('admin_id', $this->session->userdata('login_user_id'));
                $this->db->update('admin', array('password' => $new_password));

                $this->session->set_flashdata('flash_message', get_phrase('password_updated_successfuly'));
                redirect(base_url() . 'index.php?admin/manage_profile', 'refresh');
            } else {
                $this->session->set_flashdata('error_message', get_phrase('password_mismatch'));
                redirect(base_url() . 'index.php?admin/manage_profile', 'refresh');
            }
        }

        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $this->load->view('backend/index', $page_data);
    }

    // SYSTEM SETTINGS
    function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'do_update') {

            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_email');
            $this->db->where('type' , 'system_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('address');
            $this->db->where('type' , 'address');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('phone');
            $this->db->where('type' , 'phone');
            $this->db->update('settings' , $data);

            if($_FILES['logo']['name'] != '')
                //move_uploaded_file($_FILES['logo']['tmp_name'], 'uploads/logo.png');

            $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/system_settings', 'refresh');
        }

        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $this->load->view('backend/index', $page_data);
    }

    // MANAGE CLASS TEST SCORE
    function class_test_score($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']     = 'class_test_score';
        $page_data['page_title']    = get_phrase('class_test_scores');
        $this->load->view('backend/index', $page_data);
    }

    function class_test_score_selector()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $class_code         = $this->crud_model->get_field_by_id('class', 'code', $this->input->post('class_id'));
        $class_group_code   = $this->crud_model->get_field_by_id('class_group', 'code', $this->input->post('class_group_id'));
        $course_code        = $this->crud_model->get_field_by_id('course', 'code', $this->input->post('course_id'));
        $class_test_code    = $this->crud_model->get_field_by_id('class_test', 'code', $this->input->post('class_test_id'));

        redirect(base_url().'index.php?admin/class_test_score_view/' . $class_code . '/' . $class_group_code . '/' . $course_code . '/' . $class_test_code, 'refresh');
    }

    function class_test_score_view($class_code = '', $class_group_code = '', $course_code = '', $class_test_code = '')
    {
        if ($this->session->userdata('admin_login') != 1)
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
        $page_data['page_title']        = get_phrase('class_test_scores_of_class') . ' ' . $class_name . ' : ' . get_phrase('group') . ' ' . $group_name;
        $this->load->view('backend/index', $page_data);
    }

    function get_class_tests($class_id = '', $class_group_id = '', $course_id = '')
    {
        $class_tests = $this->db->get_where('class_test', array('class_id' => $class_id, 'class_group_id' => $class_group_id, 'course_id' => $course_id))->result_array();

        echo '<option value="">' . get_phrase("select_a_class_test") . '</option>';
        foreach ($class_tests as $row) {
            echo '<option value="' . $row['class_test_id'] . '">' . $row['title'] . '</option>';
        }
    }
}





















