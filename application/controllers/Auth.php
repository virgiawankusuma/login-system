<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index()
	{	
		$data['title'] = 'Login';

		$this->load->view('template/header', $data);
		$this->load->view('auth/login');
		$this->load->view('template/footer');
	}

	public function registration()
	{	
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
			"is_unique" => "This email has already registered!"
		] );
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
			"matches" => "Password don't match!",
			"min_length" => "Password too short!"
		] );
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

		if ($this->form_validation->run() == FALSE) {
			$data['title'] = 'Registration';

			$this->load->view('template/header', $data);
			$this->load->view('auth/registration');
			$this->load->view('template/footer');

		} else {
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => htmlspecialchars($this->input->post('email', true)),
				'image' => 'default.jpg',
				'password' => password_hash(htmlspecialchars($this->input->post('password')), PASSWORD_DEFAULT),
				'role_id' => 2, 
				'is_active' => 1,
				'date_create' => time()
			];
			$this->db->insert('user', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Congratulation! your account has been created. Please Activate</div>');
			redirect('auth','refresh');
		}
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */