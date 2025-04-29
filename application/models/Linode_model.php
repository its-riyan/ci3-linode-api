<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/aws-sdk/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Linode_model extends CI_Model {

    private $client;
    private $api_token = '20c42d045f10f06529d56cd399b9050ea38f07adb42ee1f0c06ecab29dcd4e5e'; // Replace with your actual token

    public function __construct() {
        parent::__construct();
        $this->client = new Client([
            'base_uri' => 'https://api.linode.com/v4/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->api_token,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
        ]);
    }

    public function get_regions() {
        try {
            $response = $this->client->get('regions/availability');
            $data = json_decode($response->getBody(), true);
            return $data['data'];
        } catch (\Exception $e) {
            log_message('error', 'Linode API get_regions: ' . $e->getMessage());
            return [];
        }
    }

    public function get_images() {
        try {
            $response = $this->client->get('images');
            $data = json_decode($response->getBody(), true);
            return $data['data'];
        } catch (\Exception $e) {
            log_message('error', 'Linode API get_images: ' . $e->getMessage());
            return [];
        }
    }

    public function get_types() {
        try {
            $response = $this->client->get('linode/types');
            $data = json_decode($response->getBody(), true);
            return $data['data'];
        } catch (\Exception $e) {
            log_message('error', 'Linode API get_types: ' . $e->getMessage());
            return [];
        }
    }

    public function get_type_details($type_id) {
        try {
            $response = $this->client->get("linode/types/{$type_id}");
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Exception $e) {
            log_message('error', 'Linode API get_type_details error: ' . $e->getMessage());
            return null;
        }
    }

    public function create_instance($data) {
        try {
            $response = $this->client->post('linode/instances', [
                'json' => $data
            ]);
            $body = $response->getBody();
            $result = json_decode($body, true);
            return $result;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            log_message('error', 'Linode API error: ' . $e->getMessage());
            return false;
        }
    }

    public function get_instances() {
        try {
            $response = $this->client->get('linode/instances');
            $data = json_decode($response->getBody(), true);
            return $data['data'];  // List of instances
        } catch (\Exception $e) {
            log_message('error', 'Linode API get_instances: ' . $e->getMessage());
            return [];
        }
    }



    public function get_invoices() {
        try {
            $response = $this->client->get('account/invoices');
            $data = json_decode($response->getBody(), true);
            return $data['data'];  // List of instances
        } catch (\Exception $e) {
            log_message('error', 'Linode API get_instances: ' . $e->getMessage());
            return [];
        }
    }

}
