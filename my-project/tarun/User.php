 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
            
        // Send mail library
        $this->load->library('SendEmail');

        // load essential helper and url etc.
        $this->load->model('EducatorModel');
        $this->load->model('UserModel');
        $this->load->model('CourseModel');
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->library('pagination');
        // For authentication in every method except index and register
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method();
        if($class === 'user' && $method !== 'register' && $method !== 'index') {
            if (!isset($this->session->userdata['user_id'])) {
                redirect(base_url(),'refresh');
                $this->session->set_flashdata('message', 'Please login !');
            }
            // welcome form condition
            if ($this->session->userdata['welcome_info'] === "false") {
                
               redirect(base_url(),'refresh');
            }
        }

    }
    // domain exist
    public function CheckDomain($domain)
    {
        $exist = $this->EducatorModel->DomainExist($domain);
        return $exist;
    }
    //Check user name availability
    public function CheckUsername($username, $educator_id, $user_id)
    {
        $exist = $this->UserModel->UserNameExist($username, $educator_id, $user_id);
        return $exist;
    }
    // check email exist or not
    public function CheckEmail($email, $educator_id, $user_id)
    {
        $exist = $this->UserModel->EmailExist($email, $educator_id, $user_id);
        return $exist;
    }
    // Welcome form
    private function welcome_form()
    {
        $this->load->view('welcome_form');  
    }
    // Educator register
    public function Register()
    {
        if($this->input->server('REQUEST_METHOD') == "POST" && $this->input->post("educator_register") !== null) {
            // response object
            $response = [
                'success' => false,
                'message' => '',
                'error' => array(),
                'data' => array(),
                'target' =>  ''
            ];
            // form validation rules
            $this->form_validation->set_rules(
                'domain_name','Domain Name','trim|required|max_length[21]'
            );
            $this->form_validation->set_rules(
                'full_name', 'Full Name','trim|required|min_length[4]|max_length[40]'
            );
            $this->form_validation->set_rules(
                'username', 'User Name','trim|required|min_length[3]|max_length[40]'
            );
            $this->form_validation->set_rules(
                'email','Email','trim|required|valid_email|xss_clean'
            );
            $this->form_validation->set_rules(
                'password','Password','trim|required|min_length[4]|max_length[40]|xss_clean'
            );
            $this->form_validation->set_rules(
                'term&policy','Terms & Services','required'
            );

            // Check username, domain, email availability
            $domain_exist = $this->CheckDomain($this->input->post("domain_name"));
            $username_exist = $this->CheckUsername($this->input->post("username"), "", "");
            $email_exist = $this->CheckEmail($this->input->post("email"), "", "");

            if($this->form_validation->run() == false)
            {
                $response['error'] = $this->form_validation->error_array();
                $response['message'] = "Please fill the form correctly...!";
                echo json_encode($response);
                exit;
            } else if($domain_exist === true) {
                $err_arr = $this->form_validation->error_array(); 
                $err_arr['domain_name'] = "Domain name not available.";
                $response['message'] = "Domain name not available.";
                $response['error'] = $err_arr;
                echo json_encode($response);
                exit;
            } else if($email_exist === true) {
                $err_arr = $this->form_validation->error_array(); 
                $err_arr['email'] = "An account already exist with given email. Please login.";
                $response['message'] = "An account already exist with given email. Please login.";
                $response['error'] = $err_arr;
                echo json_encode($response);
                exit;
            } else if($username_exist === true) {
                $err_arr = $this->form_validation->error_array(); 
                $err_arr['username'] = "Username not available.";
                $response['message'] = "Username not available.";
                $response['error'] = $err_arr;
                echo json_encode($response);
                exit;
            } else {
                $user_password = password_hash($this->input->post("password"), PASSWORD_DEFAULT);
                // Educator data
                $EducatorData = [
                    "domain_name" => $this->input->post("domain_name")
                ];

                $educator_id;
                // Insert INTO Educator
                $insert_educator = $this->EducatorModel->Insert($EducatorData);
                // echo "<pre>";
                // print_r($insert_educator);
                // die;
                if($insert_educator === false) {
                    // Response
                    $response["message"] = "Some error occured, please try again...!";
                    echo json_encode($response);
                    exit;
                } else {
                    $educator_id = $insert_educator;

                    // Educator data
                    $UserData = [
                        "email" => $this->input->post("email"),
                        "username" => $this->input->post("username"),
                        "full_name" => $this->input->post("full_name"),
                        "password" => $user_password,
                        "user_type" => "1",
                        "active" => "true",
                        "educator_id" => $educator_id
                    ];
                    
                    $user_id;
                    // Insert into Users
                    $insert_user = $this->UserModel->Insert($UserData);
                    if($insert_user === false) { 
                        // Response
                        $response["message"] = "Some error occured, please try again...!";
                        echo json_encode($response);
                        exit;
                    } else {
                        $user_id = $insert_user;
                        $data = [
                            'user_id' => $user_id
                        ];
                        // update user id in educator
                        $update_educator = $this->EducatorModel->Update($data, $educator_id);
                        if($update_educator != true) {
                            // Response
                            $response["message"] = 'Some error occured, please try again...!';
                            echo json_encode($response);
                            exit;
                        } else {
                            $this->session->set_userdata('educator_id',$UserData["educator_id"]);
                            $data_row = $this->UserModel->Update_e_id($insert_user);
                            $this->session->set_userdata('user_id',$data_row->user_id);
                            $this->session->set_userdata('domain_name',$data_row->domain_name);
                            $this->session->set_userdata('user_name',$data_row->username);
                            $this->session->set_userdata('welcome_info',$data_row->welcome_info );
                            $response["success"] = true;
                            $response["data"]["name"] = $this->input->post("domain_name");
                            $response["data"]["educator_id"] = $educator_id;
                            $response["message"] = 'Registered Successfully.';
                            echo json_encode($response);
                        }
                    }
                }
            }
        } else if($this->input->server('REQUEST_METHOD') == "POST" && $this->input->post("welcome_form") !== null) {
            // response object
            $response = [
                'success' => false,
                'message' => '',
                'error' => array(),
                'data' => array(),
                'target' =>  ''
            ];
            $goal = implode(",", $this->input->post("goal"));
            $EducatorData = [
                "department" => $this->input->post("department"),
                "what_company_do" => $this->input->post("what_company_do"),
                "no_of_people" => $this->input->post("no_of_people"),
                "goal" => $goal,
                "welcome_info" => 'true',
            ];

            // update user id in educator
            $educator_id = $this->session->userdata["educator_id"];
            $update_educator = $this->EducatorModel->Update($EducatorData, $educator_id);
            if($update_educator != true) {
                // Response
                $response["message"] = 'Some error occured, please try again...!';
                echo json_encode($response);
                exit;
            } else {
                // set welcome info session
                $this->session->set_userdata('welcome_info',"true");
                $response["success"] = true;
                $response["target"] =  base_url()."user/dashboard";
                $response["message"] = 'Thank You';
                echo json_encode($response);
            }
        } else {
            $this->load->view("register");
        } 
    }
    // Login and dashboard page function
    public function index()
    { 
        if(isset($this->session->userdata["user_id"])) {
            if ($this->session->userdata['welcome_info'] === "false") {
                $this->welcome_form();
            } else {
                $this->welcome();
            }
        } 
        else if($this->input->server('REQUEST_METHOD') == "POST" && $this->input->post("educator_login") !== null)
        {
            // form validation
            $this->form_validation->set_rules(
                'domain_name','Domain Name','trim|required|min_length[4]|max_length[21]'
            );
            $this->form_validation->set_rules(
                'usernameEmail', 'Username or Email','trim|required|min_length[3]|max_length[40]'
            );
            $this->form_validation->set_rules(
                'password','Password','trim|required|min_length[4]|max_length[40]|xss_clean'
            );

            if($this->form_validation->run() == false)
            {
                $this->load->view('login');
            }
            else
            {
                $email = $this->form_validation->set_rules('usernameEmail','Username or Email','trim|required|valid_email|xss_clean');
                if($this->form_validation->run($email) == true)
                {
                    $email_id = $this->input->post("usernameEmail"); 
                } else {
                    $email_id = "";
                }
                // $password = md5($this->input->post("password"));
                $data = [
                    'username' => $this->input->post("usernameEmail"),
                    'domain_name' => $this->input->post("domain_name"),
                    'password' =>  $this->input->post("password"),
                    'email' => $email_id
                ]; 
                $result = $this->UserModel->Login($data);
                if($result != false) {
                       // $active_id = $data['active_id'] = 'administrator';
                 // $result = $this->UserModel->dashboard_active($role,$user_id);
                    $this->session->set_userdata('user_id',$result["id"]);
                    $this->session->set_userdata('educator_id',$result["educator_id"]);
                    $this->session->set_userdata('domain_name',$result["domain_name"]);
                    $this->session->set_userdata('user_name',$result["username"]);
                    $this->session->set_userdata('welcome_info',$result["welcome_info"]);

                    // set cookies for user
                    if ($this->input->post("remember_me") == "true") {
                        $this->input->set_cookie("domain_name", $this->input->post("domain_name"), '604800');
                        $this->input->set_cookie("userNameEmail", $this->input->post("usernameEmail"), '604800');
                        $this->input->set_cookie("password", $this->input->post("password"), '604800');
                    }
                    // Welcome_form condition
                    if ($result["welcome_info"] === "false") {
                        $this->welcome_form();
                    } else {
                        $this->welcome();
                    }
                } else {
                    $this->session->set_flashdata('message', 'Invalid login details...!');
                    $this->load->view('login');
                }
            }
        } else {
            $this->load->view("login"); 
        }
    }

    public function dashboard()
    {
        $this->load->view('dashboard');
    }

    public function welcome() {
        /* Set user Dashboard  By Status  @hrk*/
            $role = isset($_GET['role']) ? $_GET['role'] : "";
            $user_id = $this->session->userdata["user_id"];
            if(!empty($this->session->userdata["user_id"]) && !empty($role)){
                  $active_id = $data['active_id'] = $role;
                  $result = $this->UserModel->dashboard_active($role,$user_id);
                  if(!empty($result)){
                    $data['msg'] = 'dashboard Board Change  Successfully';
                  }else{
                    $data['msg'] = 'dashboard Board Not Change  Successfully please Try again !';
                  }
            }else{
                  $role = $this->UserModel->get_active_dashboard($user_id);
                  //echo $result;
                 $active_id =  $data['active_id'] = isset($role) ? $role : 'administrator';
                 
            }
        if($active_id == 'administrator'){
                $this->load->view('dashboard',$data);
         }else if($active_id == 'instructor'){
            $this->load->view('welcomes',$data);
         }else if($active_id == 'learner'){
           $this->load->view('welcomes',$data);
         }
            /*End */
        
    }

    // Create user
    public function Create()
    {
        if($this->input->server('REQUEST_METHOD') == "POST" && $this->input->post("add_user") !== null)
        {
            $this->UserCreateUpdate($this->input->post("add_user"));
        } else {
            $data['usertype'] = $this->UserModel->GetypeUserType();
            $this->load->view('user/create',$data);
        }
    }

    // USER UPDATE FUNCTION
    public function Update()
    {
        if($this->input->server('REQUEST_METHOD') == "POST" && $this->input->post("update_user") !== null && $this->input->post("user_id") !== null)
        {
            $this->UserCreateUpdate($this->input->post("update_user"));
        } else {
            $this->load->view('user/list');
        }
    }
    // Common method for user add and update
    private function UserCreateUpdate(string $action)
    {
        if($this->input->server('REQUEST_METHOD') == "POST" && $action != "")
        {
            // response object
            $response = [
                'success' => false,
                'message' => '',
                'error' => array(),
                'data' => array(),
                'target' =>  ''
            ];

            if($_FILES['profile_pic']['tmp_name'] != "") {
                // Pofile image
                $upload_directory = "uploads/users/profile/";
                $imagename = $this->input->post("edit_image");
                if(!empty($imagename)){
                    unlink($upload_directory. $imagename );
                }

                // supported file extension
                $allowed_extension = array("jpg", "png", "jpeg");

                // file upload
                $file_name = $_FILES['profile_pic']["name"];
                $file_size = $_FILES['profile_pic']["size"]; // size in bytes
                $temp_name = $_FILES['profile_pic']["tmp_name"];
                $file_name = $_FILES['profile_pic']["name"];
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

                if(!in_array($file_extension, $allowed_extension)) {
                    $err_arr = $this->form_validation->error_array(); 
                    $err_arr['profile_pic'] = "Please choose jpg, png, or jpeg file extension.";
                    $response['error'] = $err_arr;
                    echo json_encode($response);
                    exit;
                } else if($file_size >  2000000 ) {
                    $err_arr = $this->form_validation->error_array(); 
                    $err_arr['profile_pic'] = "Please choose any file less than 2MB.";
                    $response['error'] = $err_arr;
                    echo json_encode($response);
                    exit;
                }
            } 
            
             // form validation
            $this->form_validation->set_rules(
                'first_name','First Name ','trim|required|min_length[4]|max_length[21]'
            );
            $this->form_validation->set_rules(
                'last_name','Last Name ','trim|required|min_length[4]|max_length[21]'
            );
            $this->form_validation->set_rules(
                'email','Email','trim|required|valid_email|xss_clean'
            );
            $this->form_validation->set_rules(
                'last_name','Last Name ','trim|required|min_length[4]|max_length[21]'
            );
            $this->form_validation->set_rules(
                'username','User Name ','trim|required|min_length[4]|max_length[21]'
            );
            // skip password required validation on update
            if ($action === "AddUser") {
                $this->form_validation->set_rules(
                    'password','Password','trim|required|min_length[4]|max_length[40]|xss_clean'
                );
                $user_id_update = "";
            } else {
                $this->form_validation->set_rules(
                    'password','Password','trim|min_length[4]|max_length[40]|xss_clean'
                );
                // user update id 
                $user_id_update = $this->input->post("user_id"); 
            }
            $this->form_validation->set_rules(
                'bio','Bio ','trim|required|min_length[30]|max_length[800]'
            );
            $this->form_validation->set_rules(
                'user_type','User Type ','trim|required'
            );
            $this->form_validation->set_rules(
                'timezone','Time Zone ','trim|required'
            );
            // EDUCATOR DETAILS
            $educator_domain = $this->session->userdata["domain_name"];
            $educator_id = $this->session->userdata["educator_id"];


            // Check username, email availability
            $username_exist = $this->CheckUsername($this->input->post("username"), $educator_id, $user_id_update);
            $email_exist = $this->CheckEmail($this->input->post("email"), $educator_id, $user_id_update);
            
            if($this->form_validation->run() == false)
            {
                 $response['error'] = $this->form_validation->error_array();
                 $response['message'] = "Please fill the form correctly...!";
                 echo json_encode($response);
                 exit;
            } else if($username_exist === true) {
                $err_arr = $this->form_validation->error_array(); 
                $err_arr['username'] = "Username not available.";
                $response['message'] = "Username not available...!";
                $response['error'] = $err_arr;
                echo json_encode($response);
                exit;
            } else if($email_exist === true) {
                $err_arr = $this->form_validation->error_array(); 
                $response['message'] = "Username not available...!";
                $err_arr['email'] = "An account already exist with given email.";
                $response['error'] = $err_arr;
                echo json_encode($response);
                exit;
            } else {
                
                // Educator data
                $UserData = [
                    "full_name" => $this->input->post("first_name")." ".$this->input->post("last_name"),
                    "first_name" => $this->input->post("first_name"),
                    "last_name" => $this->input->post("last_name"),
                    "email" => $this->input->post("email"),
                    "username" => $this->input->post("username"),
                    "bio" => $this->input->post("bio"),
                    "user_type" => $this->input->post("user_type"),
                    "timezone" => $this->input->post("timezone"),
                    "active" => $this->input->post("active") ?? false,
                    "deactivated_at" => $this->input->post("deactivated_date") ?? NULL,
                    "email_exclude" => $this->input->post("exclude_mail") ?? false,
                    "educator_id" => $educator_id
                ];
                // password condtion for add update
                if ($this->input->post("password") != "") {
                    $user_password = md5($this->input->post("password"));
                    // Push in userdata array
                    $UserData["password"] = $user_password;
                }

                // ADD Update condtion 
                if ($action === "AddUser") {
                    // response message
                    $return_message = "User Added Successfully";
                    // Insert into Users
                    $result = $this->UserModel->Insert($UserData);    
                } else if ($action === "UpdateUser") {
                    // response message
                    $return_message = "User Updated Successfully";
                    // Update User
                    $result = $this->UserModel->UpdateUser($UserData, $user_id_update);
                }

                
                if($result === false) { 
                    $response["message"] = "Some error occured..!";
                    echo json_encode($response);
                    exit;
                } else {
                    // USER ID OF CREATED USER
                    $user_id = $result;

                    // EMAIL TO USER AFTER CREATION
                    $from = 'dev.team2080@gmail.com';
                    $from_name = $educator_domain;
                    $to = $UserData["email"];
                    $subject = 'Invitation Mail';
                    $mail_message = "
                        Welcome User : '{$UserData["full_name"]}' <br>
                        Welcome Username : '{$UserData["username"]}' <br>
                        Welcome Password : '{$this->input->post("password")}' <br>
                    ";

                    // USER PROFILE UPLOAD 
                    if ($_FILES['profile_pic']['tmp_name'] != "") {
                        $new_file_name = $user_id.".png";
                        $upload_path = $upload_directory.$new_file_name;

                        $uploaded = move_uploaded_file($temp_name, $upload_path);
                        if (!$uploaded) {
                            $result = $this->UserModel->DeleteUser($user_id, $educator_id);
                            $response["message"] = "Some error occured.";
                            echo json_encode($response);
                            exit;
                        } else {
                            $data = [
                                "profile_pic" => $new_file_name
                            ];
                            $result = $this->UserModel->UpdateUser($data, $user_id);
                            if($result) {
                                // Send mail
                                $this->sendemail->Send($from, $from_name, $to, $subject, $mail_message);
                                // Returning Response
                                $response["success"] = true;
                                $response["message"] = $return_message;
                                $response["target"] = base_url()."user/list";
                                echo json_encode($response);
                            } else {
                                $response["message"] = "Some error occured.";
                                echo json_encode($response);
                            }
                        }
                    } else {
                        // Invitation mail to user
                        $this->sendemail->Send($from, $from_name, $to, $subject, $mail_message);
                        // Response
                        $response["success"] = true;
                        $response["message"] = $return_message;
                        $response["target"] = base_url()."user/list";
                        echo json_encode($response);
                    }
                }
            }
        } else {
            $this->load->view('user/list');
        }
    }

    // List user
    public function List()
    {
          // EDUCATOR DETAILS
        $educator_id = $this->session->userdata["educator_id"];
       $datas['list'] = $this->UserModel->GetUserallData($educator_id);
        $this->load->view('user/list',$datas);
    }

    public function datalist()
    {
        // EDUCATOR DETAILS
        $educator_id = $this->session->userdata["educator_id"];
        // Get value
        $data = [
            'sort' => $this->input->get("sort") ?? "",
            'search' => $this->input->get("search") ?? "",
            'order_by' => $this->input->get("order") ?? "",
            'offset' => $this->input->get("offset") ?? "",
            'limit' => $this->input->get("limit") ?? ""
        ];
        $arr['rows'] = [];
        $list = $this->UserModel->GetUser($data, $educator_id);
        if ($list->num_rows > 0) {
            while($row = $list->fetch_assoc()) {
                $arr['rows'][] = $row;
            }

            $total_count = $this->UserModel->CountUser($educator_id);
            $arr['total'] = $list->num_rows;
            $arr['totalNotFiltered'] = $total_count->num_rows;
        } else {
            $arr['rows'] = [];
        }
        
        echo json_encode($arr);
    }

    // read csv file
    public function readFile()
    {
        $this->load->library('Import');
        $result =   $this->import->parse_file($_FILES['csv_file']);
        var_dump($result); die(); 
    }

    // User info
    public function Info()
    {
        // EDUCATOR DETAILS
		$id = isset($_GET['id']) ? $_GET['id'] : "";
		if(!empty($id) && is_numeric($id)){
			$id = $id;
		}else{
			$id = $this->session->userdata["user_id"];
		}
		//echo $id; die;
        $educator_id = $this->session->userdata["educator_id"];
        $result = $this->UserModel->GetUserById($id, $educator_id);
        $data['usertype'] = $this->UserModel->GetypeUserType();
        $data['userData'] = $result->result();
        $data['user_id'] = $id;
        $this->load->view('user/info', $data);
    }

    public function course_info($id){
        $result = $this->UserModel->GetCourseList($id);
        $data['userData'] = $result->result();

        //$this->load->view('user/info', $data);

        print_r($data);

    }
    

    // Logout method
    public function Logout()
    {
        $this->session->sess_destroy();
        $this->load->helper("url");
        redirect(base_url(),'refresh');
    }

    public function CourseUser($user_id)
    {
       $result = $check_user_data['result'] = $this->UserModel->GetUserData($user_id);
        if($result){
        $config = array();
        $config["base_url"] = base_url() . "user/course/";
        $config["total_rows"] = $this->UserModel->get_count_course_byid($result->educator_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['results'] = $this->UserModel->get_course_byID($config["per_page"], $page,$result->educator_id);
        $data['educator_id'] = $check_user_data['result']->educator_id;
        $data['user_id']   = $user_id;
        $this->load->view('user/course',$data);
        }
        else
        {
            echo "Page Not Found";
            die();
        }
    }

    public function UserCourseList($educator_id)
    {
        // Get value
        $data = [
            'sort' => $this->input->get("sort") ?? "",
            'search' => $this->input->get("search") ?? "",
            'order_by' => $this->input->get("order") ?? "",
            'offset' => $this->input->get("offset") ?? "",
            'limit' => $this->input->get("limit") ?? ""
        ];
        $arr['rows'] = [];
        $list = $this->UserModel->GetUserCourseList($data, $educator_id);
        if ($list->num_rows > 0) {
            while($row = $list->fetch_assoc()) {
                $arr['rows'][] = $row;
            }

            $total_count = $this->UserModel->CountUser($educator_id);
            $arr['total'] = $list->num_rows;
            $arr['totalNotFiltered'] = $total_count->num_rows;
        } else {
            $arr['rows'] = [];
        }
        
        echo json_encode($arr);
    }

    public function Group($user_id)
    {

        $result = $check_user_data['result'] = $this->UserModel->GetUserData($user_id);
        if($result){
        $config = array();
        $config["base_url"] = base_url() . "user/course/";
        $config["total_rows"] = $this->UserModel->get_count_group_byid($result->educator_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"]       = $this->pagination->create_links();
        $data['results']     = $this->UserModel->get_group_byID($config["per_page"], $page,$result->educator_id);
        $data['educator_id'] = $check_user_data['result']->educator_id;
        $data['user_id']     = $user_id;
        $this->load->view('user/group',$data);
        }
        else
        {
            echo "Page Not Found";
            die();
        }
    }

    public function UserGroupList($educator_id)
    {
        // Get value
        $data = [
            'sort' => $this->input->get("sort") ?? "",
            'search' => $this->input->get("search") ?? "",
            'order_by' => $this->input->get("order") ?? "",
            'offset' => $this->input->get("offset") ?? "",
            'limit' => $this->input->get("limit") ?? ""
        ];
        $arr['rows'] = [];
        $list = $this->UserModel->GetUserGroupList($data, $educator_id);
        if ($list->num_rows > 0) {
            while($row = $list->fetch_assoc()) {
                $arr['rows'][] = $row;
            }

            $total_count = $this->UserModel->CountUser($educator_id);
            $arr['total'] = $list->num_rows;
            $arr['totalNotFiltered'] = $total_count->num_rows;
        } else {
            $arr['rows'] = [];
        }
        
        echo json_encode($arr);
    }

    public function Branches($user_id)
    {
        $result = $check_user_data['result'] = $this->UserModel->GetUserData($user_id);
        if($result){
        $config = array();
        $config["base_url"] = base_url() . "user/course/";
        $config["total_rows"] = $this->UserModel->get_count_branch_byid($result->educator_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"]       = $this->pagination->create_links();
        $data['results']     = $this->UserModel->get_branch_byID($config["per_page"], $page,$result->educator_id);
        $data['educator_id'] = $check_user_data['result']->educator_id;
        $data['user_id']     = $user_id;
        $this->load->view('user/branch',$data);
        
        }
        else
        {
            echo "Page Not Found";
            die();
        }
    }

    public function branchuserlist($educator_id)
    {
        // Get value
        $data = [
            'sort' => $this->input->get("sort") ?? "",
            'search' => $this->input->get("search") ?? "",
            'order_by' => $this->input->get("order") ?? "",
            'offset' => $this->input->get("offset") ?? "",
            'limit' => $this->input->get("limit") ?? ""
        ];
        $arr['rows'] = [];

        $list = $this->CourseModel->GetCourseBranchData($educator_id,$data);
        if ($list->num_rows > 0) {
            while($row = $list->fetch_assoc()) {
                $arr['rows'][] = $row;
            }

            $total_count = $this->CourseModel->CountCourseBranch($educator_id);
            $arr['total'] = $total_count;
            $arr['totalNotFiltered'] = $list->num_rows;
        } else {
            $arr['rows'] = [];
        }
        echo json_encode($arr);
    }

    public function UserFiles($user_id)
    {
        $check_user_branch_data['result'] = $this->UserModel->GetUserData($user_id);
        if($check_user_branch_data['result']){
            $data['educator_id'] = $check_user_branch_data['result']->educator_id;
            $data['user_id'] = $user_id;
            $this->load->view('user/files',$data);
        }
        else
        {
            echo "Page Not Found";
            die();
        }
    }

    public function UserFilesList($user_id)
    {
        $educator_id = $this->session->userdata["educator_id"];
        // Get value
        $data = [
            'sort' => $this->input->get("sort") ?? "",
            'search' => $this->input->get("search") ?? "",
            'order_by' => $this->input->get("order") ?? "",
            'offset' => $this->input->get("offset") ?? "",
            'limit' => $this->input->get("limit") ?? ""
        ];
        $arr['rows'] = [];

        //Check branch Courses
        $file_data = $this->UserModel->GetUserFileData($data,$educator_id,$user_id);
        
        if ($file_data->num_rows > 0) {
            // $fetch_data = $file_data->fetch_assoc();
            foreach($file_data as $row){
                $arr['rows'][] = $row;
            }
            $total_count = $this->UserModel->CountUserFileData($educator_id,$user_id);
            $arr['total'] = $total_count->num_rows();
            $arr['totalNotFiltered'] = $file_data->num_rows;
        } else {
            $arr['rows'] = [];
        }
        echo json_encode($arr);
    }

    public function UserUploadImage()
    {
        $educator_id = $this->session->userdata["educator_id"];
        $user_id = $this->input->post("user_id");
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('max_input_time', 3000);
        ini_set('max_execution_time', 3000);

        if(!empty($_FILES["image"]["tmp_name"])){ 
            $file_size = $_FILES['image']["size"]/1024;

            // File upload configuration 
            $targetDir = "uploads/users/files"; 
            $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg', 'gif','mp4'); 
            $fileName = basename($_FILES['image']['name']); 
            $targetFilePath = $targetDir."/".$fileName; 

            // Type of file
            $file_type = strstr($fileName,".");
            // Check whether file type is valid 
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
            if(in_array($fileType, $allowTypes)){ 
                // Upload file to the server 
                if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath) && ($user_id!='')){ 
                    // Branch data
                    $ImageData = [
                        "educator_id" => $educator_id,
                        "user_id" => $user_id,
                        "name" => $fileName,
                        "type" => $file_type,
                        "size" => intval($file_size).' KB',
                        "pre_name" => $fileName
                    ];
                    $check_branch_data = $this->UserModel->GetFilesUploaded($ImageData);
                    $response["success"] = true;
                    $response["message"] = "File has uploaded successfully!";
                } 
            } 
        } 
        else{
            $response["success"] = false;
            $response["message"] = "File uploaded unsuccessfully!";
        }
        echo json_encode($response); 
    }

    public function RenameFile()
    {
        $file_id = $this->input->post("name_id");
        $File_name = $this->input->post("rename");
        $this->UserModel->UodateFileName($file_id,$File_name);
    }
        
    public function DeleteUserFile()
    {
        $file_id = $this->input->post("file_id");
        $this->UserModel->DeleteFileUser($file_id);
    }
    /*  Assgin Cours by user id @hrk*/
    public function course_assgin(){
         $courseid = $this->input->post("courseid");
         $userid   = $this->input->post("userid");
        $data = $this->UserModel->course_assgin($courseid,$userid); 
         echo '1';
         
    }
    /*Delete Assgin Cours by user id @hrk*/
    public function course_remove(){
         $courseid = $this->input->post("courseid");
         $userid   = $this->input->post("userid");
         $data= $this->UserModel->course_remove($courseid,$userid); 
         echo  '1';
    }
    /* Assgin Group by user id @hrk*/
    public function group_assgin(){
         $groupid = $this->input->post("groupid");
         $userid   = $this->input->post("userid");
        $data = $this->UserModel->group_assgin($groupid,$userid); 
         echo '1';
         
    }
    /*Delete Assgin Group by user id @hrk*/
    public function group_remove(){
         $groupid = $this->input->post("groupid");
         $userid   = $this->input->post("userid");
         $data= $this->UserModel->group_remove($groupid,$userid); 
         echo  '1';
    }
     /* Assgin branch by user id @hrk*/
    public function branch_assgin(){
         $branchid = $this->input->post("branchid");
         $userid   = $this->input->post("userid");
        $data = $this->UserModel->branch_assgin($branchid,$userid); 
         echo '1';
         
    }
    /*Delete Assgin branch by user id @hrk*/
    public function branch_remove(){
         $branchid = $this->input->post("branchid");
         $userid   = $this->input->post("userid");
         $data= $this->UserModel->branch_remove($branchid,$userid); 
         echo  '1';
    }
    public function Userinfo($user_id){
           // echo "Hello";die;
            $check_user_branch_data['result'] = $this->UserModel->GetUserData($user_id);
            if($check_user_branch_data['result']){
            $data['educator_id'] = $check_user_branch_data['result']->educator_id;
            $data['user_id'] = $user_id;
            $this->load->view('user/userreport',$data);
        }
        else
        {
            echo "Page Not Found";
            die();
        }
    }
}