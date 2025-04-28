<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linode_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Linode_api', ['api_key' => 'f0d6749cf7fe66dae033caa5441f632a1b4a654a3d31f72979a2acdb2895b8bc']);
        $this->load->model('Linode_model', 'linode_model');
    }

    public function instances() {
        $data['linodes'] = $this->linode_api->get_linodes();
        $this->load->view('@BACKEND/page_instances/view.html', $data);
    }

    public function create() {
        // Check if form submission occurred
        if ($this->input->post()) {
            // Prepare the data array based on user input
            $data = [
                'label' => $this->input->post('label'),
                'region' => $this->input->post('region'),  // Get selected region from the form
                'type' => $this->input->post('type'),      // Get selected type from the form
                'image' => $this->input->post('image'),    // Get selected image OS from the form
                // Add other required fields based on Linode API specifications
            ];
          
            // Call your Linode API method to create a new Linode instance
            $response = $this->linode_api->create_linode($data);
    
            // Check response and handle errors accordingly
            if ($response && isset($response['id'])) {
                // Linode created successfully
                redirect('linode'); // Redirect to the Linode listing page after successful creation
            } else {
                // Handle error
                // You may want to display an error message or log the issue
            }
        }
    
        // Fetch available regions, types, and images for the dropdowns from the API
        $data['regions'] = $this->linode_model->get_regions(); // Fetch regions from the API
        $data['types'] = $this->linode_model->get_types();     // Fetch types from the API
        $data['images'] = $this->linode_model->get_images();   // Fetch images from the API
    
        // Load the create Linode view, passing all necessary data
        $this->load->view('@BACKEND/page_instances/create.html', $data);
    }

    public function edit($id) {
        if ($this->input->post()) {
            $data = [
                'label' => $this->input->post('label'),
                'region' => $this->input->post('region'),
                'type' => $this->input->post('type'),
                // Add other fields you want to update
            ];

            $this->linode_api->update_linode($id, $data);
            redirect('instances');
        }

        $data['linode'] = $this->linode_api->get_linode($id);
        $this->load->view('@BACKEND/page_instances/update.html', $data);
    }

    public function store() {
        // Validate the required fields are filled out
        $this->form_validation->set_rules('label', 'Label', 'required');
        $this->form_validation->set_rules('region', 'Region', 'required');
        $this->form_validation->set_rules('type', 'Machine Type', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, reload the create view with errors
            $data['regions'] = $this->linode_model->getRegions();
            $data['types'] = $this->linode_model->getTypes();
            $data['images'] = $this->linode_model->getImages();
            $data['error'] = validation_errors(); // Collect validation errors
            $this->load->view('page_instances/create', $data);
        } else {
            // Prepare data for Linode creation
            $data = array(
                'label' => $this->input->post('label'),
                'region' => $this->input->post('region'),
                'type' => $this->input->post('type'),
                'image' => $this->input->post('image'), // Assuming you added image field
            );
    
            // Create the Linode using the Linode API
            $response = $this->linode_api->create_linode($data);
    
            // Check the response from the Linode API
            if ($response && isset($response['id'])) {
                // Linode created successfully, save to the database
                $linode_data = array(
                    'linode_id' => $response['id'],
                    'label' => $data['label'],
                    'region' => $data['region'],
                    'type' => $data['type'],
                    'status' => 'provisioning', // Initial status
                );
                $this->linode_model->insert($linode_data); // Store Linode data in your DB
    
                // Redirect to the Linode listing page with a success message
                $this->session->set_flashdata('success', 'Linode instance created successfully!');
                redirect('linodes');
            } else {
                // Handle API response error
                $data['regions'] = $this->linode_model->getRegions();
                $data['types'] = $this->linode_model->getTypes();
                $data['images'] = $this->linode_model->getImages();
                $data['error'] = 'Failed to create Linode. Please try again.'; // Error message
    
                $this->load->view('page_instances/create', $data);
            }
        }
    }


    public function reboot($id) {
        $this->linode_api->reboot_linode($id);
        redirect('instances');
    }

    public function delete($id) {
        $this->linode_api->delete_linode($id);
        redirect('instances');
    }

}