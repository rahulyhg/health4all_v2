<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hospital_areas extends CI_Controller{                                           //create class with name Hospital_areas and it extends codeigniter controller.
  function __construct(){                                                             // definition of constructor.
		parent::__construct();     					
		$this->load->model('staff_model');
		if($this->session->userdata('logged_in')){
		$userdata=$this->session->userdata('logged_in');
		$user_id=$userdata['user_id'];
		$this->data['hospitals']=$this->staff_model->user_hospital($user_id);
		$this->data['functions']=$this->staff_model->user_function($user_id);
		$this->data['departments']=$this->staff_model->user_department($user_id);
		}
		$this->data['op_forms']=$this->staff_model->get_forms("OP");
		$this->data['ip_forms']=$this->staff_model->get_forms("IP");	                                                      
  }// __construct

     function add_area(){    
       
		if($this->session->userdata('logged_in')){                                    //checking whether user is in logging state or not;session:state of a user.
            $this->data['userdata']=$this->session->userdata('logged_in');            //taking session data into data array of index:userdata                   
        }	
        else{
            show_404();                                                               //if user is not logged in then this error will be thrown.
        }
		$access=0;
		foreach($this->data['functions'] as $function){
			if($function->user_function=="Admin"){
					$access=1;
					break;
			}
		}
        if($access==1){
        $this->load->helper('form');                                               //to do form validation,we are loading helper file with file name 'form'.
        $this->load->library('form_validation');                                   //this function is used to load core classes.Loading form_validation class.
        $this->data['title']="add area";                                           //store title value into data array of index:title.
        $this->load->view('templates/header',$this->data);                         //load header view file which is in template folder .
        $this->load->view('templates/leftnav',$this->data);                        //load leftnav view file which is in template folder.
        
        $validations=array(                                                        //create a var:validations.
            array(                                                                 // insert an array of arrays into it .Each array with 3 fields.
                'field'=>'area_name',                                              //field: contains name of the field which is used.
                'label'=>'Area name',                                              //label:it is an alias name which is displayed when an error occur.
                'rules'=>'required|alpha'                                          //rules:These are the rules that has to follow  by the field while inserting data                                                                                       into it.
            ),
             array(
                'field'=>'beds',
                'label'=>'Total beds',
                'rules'=>'numeric|required'
            ),
            array(
                'field'=>'department_id',
                'label'=>'Department',
                'rules'=>'required'
            ),
            array(
                'field'=>'area_type_id',
                'label'=>'Area type',
                'rules'=>'required'
            )

        );
       
        $this->form_validation->set_rules($validations);                                //setting form validation rules.
        $this->form_validation->set_message('message','Please input missing details.'); //display message if all the details are not given.
        if ($this->form_validation->run() == FALSE)                                     //checking whether the form has any error or not 
        {
	    echo validation_errors();                                                  //function used to display the errors.
            $this->data['message']= "Validation failed.";                              //pass failed message into data array of index:message.
            
        }//if
        else{     
	     $this->load->model('hospital_areas_model');                               //loading hospital_areas_model file.       
             if( $this->hospital_areas_model->add_area()){                             //checking whether there is a method with name add_area is present in                                                                                                       hospital_areas_model or not.
                $this->data['message']= "area added succesfully.";                     //if present then success message is set into data array of index:message.
             }//if
             else{
                $this->data['message']= "Something went wrong please try again.";      //else it stores some message into data array of index:message.
             }//else           
        }//else
        $this->load->model('area_type_model');
        $this->data['all_departments']=$this->staff_model->get_department();           //get all the departments  using get_department method of staff_model file into                                                                                          data array of index:all_departments.
        $this->data['area_types']=$this->area_type_model->get_area_type();             //get area details  using get_area_type method of staff_model file into data                                                                                              array of index:area_types.
        $this->data['lab_report_staff']=$this->staff_model->get_staff();               //get staff details using get_staff method of staff_model file into data array                                                                                          of index:lab_report_staff.
        $this->load->view('pages/hospital_area_view',$this->data);                     //load hospital_area_view (view page).
        $this->load->view('templates/footer');                                         //load footer view page.
		}
		else show_404();
	}//add_area
}// hospital_areas


