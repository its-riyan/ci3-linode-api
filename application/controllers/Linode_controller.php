<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linode_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Linode_model', 'linode_model');
    }

    public function instances() {
        $instances = $this->linode_model->get_instances();
    
        // Cache to avoid repeated API calls for the same type
        $type_cache = [];
    
        foreach ($instances as &$instance) {
            $type_id = $instance['type'];
            if (!isset($type_cache[$type_id])) {
                $type_cache[$type_id] = $this->linode_model->get_type_details($type_id);
            }
            $instance['type_details'] = $type_cache[$type_id];
        }

        $instance['invoicesss'] = $this->linode_model->get_invoices();
    
        $this->load->view('@BACKEND/page_instances/view.html', ['instances' => $instances]);
    }


    public function create_instance() {
        $regions = $this->linode_model->get_regions();
        $images  = $this->linode_model->get_images();
        $types   = $this->linode_model->get_types();

        $this->form_validation->set_rules('region', 'Region', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('image', 'Image', 'required');
        $this->form_validation->set_rules('root_pass', 'Root Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('@BACKEND/page_instances/create.html', [
                'regions' => $regions,
                'images'  => $images,
                'types'   => $types
            ]);
        } else {
            $data = [
                'region'    => $this->input->post('region'),
                'type'      => $this->input->post('type'),
                'image'     => $this->input->post('image'),
                'root_pass' => $this->input->post('root_pass'),
            ];

            $result = $this->linode_model->create_instance($data);

            if ($result) {
                $this->load->view('linode_success', ['response' => $result]);
            } else {
                $this->load->view('linode_create', [
                    'regions' => $regions,
                    'images'  => $images,
                    'types'   => $types,
                    'error'   => 'Failed to create Linode instance. Check logs for details.'
                ]);
            }
        }
    }

//     public function reboot($id) {
//         $this->linode_api->reboot_linode($id);
//         redirect('instances');
//     }

//     public function delete($id) {
//         $this->linode_api->delete_linode($id);
//         redirect('instances');
//     }

}