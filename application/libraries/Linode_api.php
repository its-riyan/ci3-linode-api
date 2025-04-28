<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linode_api {

    private $api_base = "https://api.linode.com/v4/";
    private $api_key;

    public function __construct($config = []) {
        $this->api_key = isset($config['api_key']) ? $config['api_key'] : null;
    }

    private function api_request($endpoint, $method = 'GET', $data = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_base . $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->api_key,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function get_linodes() {
        return $this->api_request('linode/instances');
    }

    public function create_linode($data) {
        return $this->api_request('linode/instances', 'POST', $data);
    }

    public function update_linode($id, $data) {
        return $this->api_request('linode/instances/' . $id, 'PUT', $data);
    }

    public function delete_linode($id) {
        return $this->api_request('linode/instances/' . $id, 'DELETE');
    }

    public function get_linode($id) {
        return $this->api_request('linode/instances/' . $id);
    }

    // New method for rebooting Linode
    public function reboot_linode($id) {
        return $this->api_request('linode/instances/' . $id . '/reboot', 'POST');
    }
}