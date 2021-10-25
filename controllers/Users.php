<?php
  class Users extends Controller {
    public function __construct(){
      $this->userModel = $this->model('User');
    }

    public function register(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
  
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data
        $data =[
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'type' => trim($_POST['type']),
          'con_number' => trim($_POST['con_number']),
          'cen_name' => trim($_POST['cen_name']),
          'location' => trim($_POST['location']),
          'district' =>trim($_POST['district']),
          'name_err' => '',
          'email_err' => '',
          'type_err' => '',
          'con_number_err' => '',
          'cen_name_err' => '',
          'location_err' => '',
          'district_err' =>'',

        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Please enter an correct email address';
        } else {
          // Check email
          if($this->userModel->findUserByaEmail($data['email'])){
            $data['email_err'] = 'Email is already taken';
          }
        }

        // Validate Name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter requester name';
        }

        // Validate Password
        if(empty($data['type'])){
          $data['type_error'] = 'select the type';
        }
        if(empty($data['con_number'])){
          $data['con_number_err'] = 'please enter contact number';
        }
        if(empty($data['cen_name'])){
          $data['cen_name_err'] = 'please fill unique name for collection center/outlet';
        }
        if(empty($data['location'])){
          $data['location_err'] = 'plese fill your address';
        }
        if(empty($data['district'])){
          $data['district_err'] = 'enter situated district';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['name_err']) && empty($data['type_err']) && empty($data['con_number_err']) && empty($data['cen_name_err'])  && empty($data['district_err'])){
          // Validated
  

          // Register User
          if($this->userModel->register($data)){
            flash('register_success', 'Registered successfully, We will serve for your requeest');
            redirect('users/register');
            
          } else {
            die('Something went wrong');
          }

        } else {
          // Load view with errors
          $this->view('users/register', $data);
        }

      } else {
        // Init data
        $data =[
          'name' =>'',
          'email' => '',
          'type' => '',
          'con_number' => '',
          'cen_name' => '',
          'location' => '',
          'district' =>'',
          'name_err' => '',
          'email_err' => '',
          'type_err' => '',
          'con_number_err' => '',
          'cen_name_err' => '',
          'location_err' => '',
          'district_err' => '',
        ];

        // Load view
        $this->view('users/register', $data);
      }
    }

 public function login(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Init data
        $data =[
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'email_err' => '',
          'password_err' => '',      
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Pleae enter email';
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        }

        // Check for user/email
        if($this->userModel->findUserByaEmail($data['email'])){
          // User found
        } else {
          // User not found
          $data['email_err'] = 'No user found';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['password_err'])){
          // Validated
          // Check and set logged in user
          $loggedInUser = $this->userModel->login($data['email'], $data['password']);

          if($loggedInUser){
            // Create Session
            $this->createUserSession($loggedInUser);
          } else {
            $data['password_err'] = 'Password incorrect';

            $this->view('users/login', $data);
          }
        } else {
          // Load view with errors
          $this->view('users/login', $data);
        }


      } else {
        // Init data
        $data =[    
          'email' => '',
          'password' => '',
          'email_err' => '',
          'password_err' => '',        
        ];

        // Load view
        $this->view('users/login', $data);
      }
    }   

    public function createUserSession($user){
      $_SESSION['user_id'] = $user->id;
      $_SESSION['user_email'] = $user->email;
      $_SESSION['user_name'] = $user->user_name;
     $_SESSION['user_type'] = $user->type; 
      if($_SESSION['user_type']==1){
        redirect('Collectioncenterpages/home');
      }
      else if($_SESSION['user_type']==2){
        redirect('Collectioncenterpages/home');
      }
      else if($_SESSION['user_type']==3){
        redirect('Collectioncenterpages/home');
      }
      else if($_SESSION['user_type']==4){
        redirect('Collectioncenterpages/home');
      }
      else if($_SESSION['user_type']==5){
        redirect('Collectioncenterpages/pendingorders');
      }
      else if($_SESSION['user_type']==6){
        redirect('Collectioncenterpages/home');
      }
    
    }

    public function logout(){
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      session_destroy();
      redirect('users/login');
    }

    public function forgot_password(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Init data
        $data =[
          'email' => trim($_POST['email']),
          'email_err' => '',   
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Pleae enter email';
        }

        // Check for user/email
        if($this->userModel->findUserByaEmail($data['email'])){
          // User found
        } else {
          // User not found
          $data['email_err'] = 'No user found';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) ){
          $verification_code=rand(10,10000);
          $pass=$verification_code;
						/*Mail Code*/
						$to =$data['email'];
						$subject = "Password";
						$txt = "Your password is $pass .";
						$headers = "From: wwprasannamadusanaka@gmail.com" . "\r\n" ;
            $this->userModel->setVerification($pass,$data);
						mail($to,$subject,$txt,$headers);
            flash('send_success', 'your request successfully send to the email');
            $this->view('users/forgot_password');
        }
            else {
              flash('send_success', 'there is no existing user');

            $this->view('users/forgot_password', $data);
          }
        } 
        $this->view('users/forgot_password');
    }

    public function reset_password(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
  
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data
        $data =[
          'verification_code' => trim($_POST['verification_code']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'verification_code_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Pleae enter email';
        } else {
          // Check email
          if(!($this->userModel->findUserByEmail($data['email'],$data['verification_code']))){
            $data['email_err'] = 'please enter correct email and verification code';
          }
        }

        // Validate Name
        if(empty($data['verification_code'])){
          $data['verification_code_err'] = $data['email_err'];
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Pleae enter password';
        } elseif(strlen($data['password']) < 6){
          $data['password_err'] = 'Password must be at least 6 characters';
        }

        // Validate Confirm Password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = 'Pleae confirm password';
        } else {
          if($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = 'Passwords do not match';
          }
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['verification_code_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
          // Validated
          
          // Hash Password
          //$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // Register User
          if($this->userModel->change_password($data)){
            flash('request-success', 'Your request was successflly completed');
            $this->view('users/reset_password', $data);
          } else {
            die('Something went wrong');
          }

        } else {
          // Load view with errors
          $this->view('users/reset_password', $data);
        }

      } else {
        // Init data
        $data =[
          'name' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Load view
        $this->view('users/reset_password', $data);
      }
    }


    public function isLoggedIn(){
      if(isset($_SESSION['user_id'])){
        return true;
      } else {
        return false;
      }
    }
  }