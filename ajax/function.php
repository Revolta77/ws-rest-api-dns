<?php
include ($_SERVER['DOCUMENT_ROOT'] . '{sub_domain_path}/configuration.php');
use ws\Connection;

$ret = [];
$data = isset( $_REQUEST['data'] ) ? $_REQUEST['data'] : [];

if ( !empty( $data ) ){
	require_once (CLASSPATH . 'connection.php');
	$api = new Connection();
	$user_id = isset( $data['user_id'] ) ? $data['user_id'] : '';

	$content_id = isset( $data['content_id'] ) ? $data['content_id'] : '';
	$domain = isset( $data['domain'] ) ? $data['domain'] : '';
	$type = isset( $data['type '] ) ? $data['type '] : '';
	if ( isset( $data['hash']) ){
		$hash = $data['hash'];
		unset($data['hash']);
	}
	if( $data['func'] == 'insert' ){
		$path = '/v1/user/' . $user_id . '/zone/' . $domain . '/record';
		$method = 'POST';
	} elseif( $data['func'] == 'update' ){
		$path = '/v1/user/' . $user_id . '/zone/' . $domain . '/record/' . $content_id;
		$method = 'PUT';
	} elseif( $data['func'] == 'delete' ){
		$path = '/v1/user/' . $user_id . '/zone/' . $domain . '/record/' . $content_id;
		$method = 'DELETE';

	}

	if( $method == 'DELETE' || $method == 'GET' ){
		$data = NULL;
	} else {
		if ( $method == 'PUT' ){
			unset($data['type']);
		}
		if ( $method != 'POST' ){
			$data['id'] = $data['content_id'];
		}
		unset($data['content_id']);
		unset($data['func']);
		unset($data['domain']);
		unset($data['user_id']);
	}

	$html = '';
	if ( isset( $path ) && isset( $method ) ){
		if ( $method != 'DELETE' ){
			$is_empty = true;
			if ( !empty( $data ) ) foreach ( $data as $key => $value ){
				if ( $key != 'type' && $key != 'ttl' && $key != 'note' && !empty( $value ) ){
					$is_empty = false;
				}
			}
		} else {
			$is_empty = false;
		}

		if( !$is_empty ){
			$ret = $api->setRequest( $path, $method, $data );
			if( isset( $ret['success'] ) ){
				$data = $api->getAllData()[$user_id];
				require_once ( CLASSPATH . 'content.php' );
				$content = new Content();
				$html = $content->getContent( $data, $domain, $hash );
			}
		} else {
			$ret['error']['content'] = "Je potrebné vyplniť dáta.";
		}


	}
}
echo json_encode( [ 'return' => $ret, 'path' => $path, 'html' => $html ] );