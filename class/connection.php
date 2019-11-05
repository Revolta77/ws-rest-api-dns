<?php

namespace ws;

class Connection {

	protected $apiKey;
	protected $secret;
	protected $time;
	public $users_id = [];
	public $domains = [];
	public $allRecords = [];
	public $dnsTypes = [ 'A', 'AAAA', 'ANAME', 'CAA', 'CNAME', 'MX', 'NS', 'SRV', 'TXT', 'DNSSEC' ];
	public $notUpdated = [ 'CAA', 'MX', 'DNSSEC' ];

	public function __construct() {
		$this->time = time();
		$this->apiKey = '{api_key}';
		$this->secret = '{secret_key}';
		list ($data, $responseCode) = $this->request( 'GET', '/v1/user' );
		if ($responseCode === 200) {
			if( !empty( $data['items'] ) ) foreach ( $data['items'] as $user ) {
				$this->users_id[] = $user['id'];
			}
		} else {
			var_dump( $responseCode, $data );
		}
	}

	public function getDnsTypes(){
		return $this->dnsTypes;
	}

	public function getAllData(){
		$data = [];
		if ( !empty( $this->users_id ) ) foreach ( $this->users_id as $key => $user_id ) {
			$domains = $this->getDomains( $user_id );
			if ( $domains == false ){
				return false;
			}
			if ( !empty( $domains) ) foreach ( $domains as $key2 => $domain ) {
				$records = $this->getAllRecords( $user_id, $domain['domain'] );
				if( !empty( $records ) ) foreach ( $records as $record ){
					$data[$user_id][$domain['domain']][$record['type']][] = $record;
				}
			}
		}
		return $data;
	}

	public function getUsersId(){
		return $this->users_id;
	}

	public function getDomains( $user_id = '' ){
		$domains = [];
		if ( $user_id != '' ){
			list ($data, $responseCode) = $this->request( 'GET', '/v1/user/' . $user_id . '/zone' );
		} else {
			list ($data, $responseCode) = $this->request( 'GET', '/v1/user/self/zone' );
		}

		if ($responseCode === 200) {
			if ( !empty( $data['items'] ) ) foreach ( $data['items'] as $domain ) {
				if( $user_id != '' ){
					$this->domains[$user_id][] = [ 'id' => $domain['id'], 'domain' => $domain['name'] ];
				} else {
					$domains[] = [ 'id' => $domain['id'], 'domain' => $domain['name'] ];
				}

			}
		} else {
			var_dump( $responseCode, $data );
			return false;
		}
		if( $user_id != '' ){
			return $this->domains[$user_id];
		} else {
			return $domains;
		}
	}

	public function getDomainsName ( $user_id ) {
		return $this->domains[$user_id];
	}


	public function getAllRecords ( $id, $domain ){
		$ret = [];
		list ($data, $responseCode) = $this->request( 'GET', '/v1/user/' . $id . '/zone/' . $domain . '/record' );
		if ($responseCode === 200) {
			if ( !empty( $data['items'] ) ) {
				$this->allRecords[$id][$domain] = $data['items'];
				$ret = $data['items'];
			}
		} else {
			var_dump($data);
			return false;
		}
		return $ret;
	}

	public function setRequest( $path, $method, $data ){

		list ($retData, $responseCode) = $this->request( $method, $path, $data );

		if ($responseCode === 200 && $retData['status'] == 'success' ) {
			return [ 'success' => 'success' ];
		} elseif ( $responseCode === 200 && $retData['status'] == 'error' ){
			return [ 'error' => $retData['errors'] ];
		} else {
			return $retData;
		}
	}

	protected function request( $method, $path, $params = NULL ){

		$canonicalRequest = sprintf('%s %s %s', $method, $path, $this->time);
		$signature = hash_hmac('sha1', $canonicalRequest, $this->secret);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_URL, 'https://rest.websupport.sk' . $path);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $this->apiKey . ":" . $signature);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-Type: application/json',
			'Accept-Language: sk',
			'Date: ' . gmdate( 'Ymd\THis\Z', $this->time ),
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		if (!empty($params)) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode( $params ) );
		}
		$api = curl_exec($curl);
		$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($httpStatus == 0) {
			$errorMessage = curl_error($curl);
			var_dump($errorMessage);
		}
		curl_close($curl);
		$data = json_decode($api, true);
		if ($data === null) {
			var_dump('Error while parsing json: ' . substr($api, 0, 250),200, $api);
			return false;
		}

		return [ $data, $httpStatus ];
	}

}
