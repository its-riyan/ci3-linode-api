<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/aws-sdk/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Linode_model extends CI_Model {
    private $api_key;

    public function __construct() {
        parent::__construct();
        $this->api_key = 'f0d6749cf7fe66dae033caa5441f632a1b4a654a3d31f72979a2acdb2895b8bc'; // Load your API key securely
    }

    // Private function to make API requests
    private function apiRequest($url, $method = 'GET', $data = []) {
        $client = new Client();
        try {
            $options = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_key,
                ],
            ];
            if ($method !== 'GET') {
                $options['json'] = $data; // Add request body if not GET
            }

            $response = $client->request($method, $url, $options);
            return json_decode($response->getBody(), true)['data'];
        } catch (RequestException $e) {
            log_message('error', 'Linode API Error: ' . $e->getMessage());
            return []; // Or handle error as needed
        }
    }

    public function get_regions() {
        $url = 'https://api.linode.com/v4/regions';
        return $this->apiRequest($url);
    }

    public function get_types() {
        // Corrected endpoint for getting Linode types
        $url = 'https://api.linode.com/v4/linode/types';
        return $this->apiRequest($url);
    }

    public function get_images() {
        $url = 'https://api.linode.com/v4/images';
        return $this->apiRequest($url);
    }

    // Example method to create a new Linode instance
    public function create_linode($data) {
        $url = 'https://api.linode.com/v4/linode/instances';
        return $this->apiRequest($url, 'POST', $data);
    }
}