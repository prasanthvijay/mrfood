<?php


class Admin extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */


    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('users_model');
        $this->load->library('session');
    }

    public function index()
    {
        $output = $this->session->flashdata('output');
        $succesMsg = $this->users_model->getSuccessMsg($output);

        $dataheader['succesMsg'] = $succesMsg;
        $dataheader['title'] = "Login";
        $dataheader['loginPostUrl'] = "admin/checkLogin";

        $this->load->view('layout/frontend_header', $dataheader);
        $this->load->view('admin/login');
        $this->load->view('layout/frontend_footer');
    }
	public function checkLogin()
    {
        
       	$fromApp = $this->input->get_post('fromApp');
	if($fromApp=='appLogin'){
		 $username = $this->input->get_post('username');
		 $userpassword = $this->input->get_post('password');	

		$userCredentialArray = array('username' => $username, 'userpassword' => $userpassword);
		$userCredential = $this->users_model->checkUserCredential($userCredentialArray);
		if (count($userCredential) > 0) {
			header('Access-Control-Allow-Origin: https://not-example.com');
			header('Access-Control-Allow-Credentials: true');
			header("Content-type: application/json");
            		echo json_encode($userCredential);
		} 
	}
	else{
		$username = $this->input->post('userName');
		$userpassword = $this->input->post('password');
		$userCredentialArray = array('username' => $username, 'userpassword' => $userpassword);
		$userCredential = $this->users_model->checkUserCredential($userCredentialArray);
		if (count($userCredential) > 0) {

			$this->session->set_userdata($userCredential);
			$this->users_model->updateLastlogin($userCredential['userid']);
			redirect($userCredential['redirecturl']);
		} else {
			$output = array('status' => "2", 'message' => "Invalid Login!!");
			$this->session->set_flashdata('output', $output);
			redirect(base_url());
		}
	}
    }
}
