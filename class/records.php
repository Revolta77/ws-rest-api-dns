<?php

class Records extends \ws\Connection{

	public $th = [];


	public function getAll($data, $domain, $hash ){
		$html = '';
		$dnsTypes = $this->dnsTypes;
		if ( !empty($dnsTypes) ) foreach ( $dnsTypes as $type ){
			$html .= $this->getRecord( $data[$domain][$type], $domain, $type, $hash );
		}
		return $html;
	}

	public function getRecord( $data , $domain, $type, $hash ){
		$hide_container = '';
		if( $hash != 'dns-' . $type ){
			$hide_container = ' style="display:none;"';
		}

		$ret = '<div class="container-fluid container_content ' . $type . '"' . $hide_container . '>';
		$ret .=	'<div class="d-sm-flex align-items-center justify-content-between mb-4 col-xl-11 col-lg-11">';
		$ret .=	'<h1 class="h3 mb-0 text-gray-800">' . $type . ' záznamy</h1>';

		if ( !in_array( $type, $this->notUpdated ) ) {
			$ret .= '<a href="javascript:;" onclick="vexDns(this, \'' . $type . '\', \'insert\');" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">';
			$ret .= '<i class="far fa-plus-square fa-sm text-white-50"></i></i>  Vytvoriť nový záznam</a>';
		}

		$ret .=	'</div>';
		$ret .=	'<div class="row">';
		$ret .=	'<div class="col-xl-11 col-lg-7">';

		$tr = [];
		$th = $this->getTh( $type );
		if( $type == 'NS'){
			// TODO toto si tu necham este
//			print_r($data);
		}
		if ( !empty( $data ) ) foreach ( $data as $key => $value ){
			$tr[] = '<tr>' . $this->getTd( $value, $domain, $type ) . '</tr>';
		} else {
			$tr[] = '<tr><td colspan="' . $th['count'] . '" class="text-center">Neboli nájdené žiadne záznamy.</td></tr>';
		}
		$ret .=	'<table class="table table-hover table-bordered table-responsive">';
		$ret .=	'<thead class="thead-dark">';
		$ret .=	'<tr>';
		$ret .=	$th['th'];
		$ret .=	'</tr>';
		$ret .=	'</thead>';
		$ret .=	'<tbody>';
		$ret .=	implode( '', $tr );
		$ret .=	'</tbody>';
		$ret .=	'</table>';

		$ret .=	'</div>';
		$ret .=	'</div>';
		$ret .=	'</div>';

		return $ret;
	}

	public function fillTh(){
		$this->th['zdroj'] = '<th class="w-15">Zdroj</th>';
		$this->th['algoritmus'] = '<th class="w-15">Algoritmus</th>';
		$this->th['pub_key'] = '<th class="w-15">Pub key</th>';
		$this->th['adresu'] = '<th class="w-50">Pre adresu</th>';
		$this->th['identifikator'] = '<th class="w-25">Identifikátor CA</th>';
		$this->th['ttl'] = '<th class="w-10">TTL</th>';
		$this->th['atributy'] = '<th class="w-15">Atribúty</th>';
		$this->th['tag'] = '<th class="w-10">Tag</th>';
		$this->th['mail_server'] = '<th class="w-25">Mail server</th>';
		$this->th['priorita'] = '<th class="w-10">Priorita</th>';
		$this->th['adresa_sluzby'] = '<th class="w-25">Adresa služby</th>';
		$this->th['port'] = '<th class="w-10">Port</th>';
		$this->th['vaha'] = '<th class="w-10">Váha</th>';
		$this->th['hodnota'] = '<th class="w-25">Hodnota</th>';
		$this->th['check'] = '<th class="w-10" onclick="checkAll(this);"><i class="far fa-square"></i></th>';
		$this->th['cielova_ip'] = '<th class="w-25">Cieľová IP</th>';
		$this->th['poznamka'] = '<th class="w-25">Poznámka</th>';
		$this->th['upravit'] = '<th class="w-10">Upraviť</th>';
		$this->th['zmazat'] = '<th class="w-10">Zmazať</th>';
	}

	public function returnTh ( $array ) {
		$ret = '';
		if ( !empty( $array ) ) foreach ( $array as $key ) {
			$ret .= $this->th[$key];
		}
		return $ret;
	}

	public function getTh ( $type ){
		if ( empty( $this->th ) ){
			$this->fillTh();
		}
		$count = 6;
		if ( $type == 'CAA' ){
			$th = $this->returnTh( [ 'adresu', 'identifikator', 'ttl', 'atributy', 'tag', 'poznamka' ] );
		} elseif ( $type == 'DNSSEC' ) {
			$th = $this->returnTh( [ 'zdroj', 'algoritmus', 'pub_key' ] );
			$count = 5;
		} elseif ( $type == 'MX' ) {
			$th = $this->returnTh( [ 'adresu', 'mail_server', 'priorita', 'ttl', 'poznamka' ] );
			$count = 8;
		} elseif ( $type == 'SRV' ) {
			$th = $this->returnTh( [ 'adresu', 'adresa_sluzby', 'port', 'priorita', 'vaha', 'ttl', 'poznamka', 'upravit', 'zmazat'] );
			$count = 9;
		} elseif ( $type == 'TXT' ){
			$th = $this->returnTh( [ 'adresu', 'hodnota', 'ttl', 'poznamka', 'upravit', 'zmazat'] );
		} else {
			$th = $this->returnTh( [ 'adresu', 'cielova_ip', 'ttl', 'poznamka', 'upravit', 'zmazat'] );
		}
		return [ 'th' => $th, 'count' => $count ];
	}

	public function returnTd( $data, $array ){
		$ret = '';
		if ( !empty( $array ) ) foreach ( $array as $item ) {
			$ret .= '<td class="align-middle" data-content="' . $item . '">' . $data[$item] . '</td>';
		}
		return $ret;
	}

	public function getTd( $data, $domain, $type ){
		$old_name = $data['name'];
		if ( $data['name'] != '@' ){
			$data['name'] = $data['name'] . '.' . $domain;
		} else {
			$data['name'] = $domain;
		}
		$td_start = '<td class="text-center id align-middle" data-content="id" onclick="checkRow(this);"><i data-id="' . $data['id'] . '" class="far fa-square"></i></td>';
		$td_end = '<td class="text-center align-middle update" onclick="vexDns(this, \'' . $type . '\', \'update\', \'' . $data['id'] . '\');"><i class="far fa-edit"></i></td>';
		$td_end .= '<td class="text-center align-middle delete" align-middle onclick="vexDns(this, \'' . $type . '\', \'delete\', \'' . $data['id'] . '\');"><i class="far fa-trash-alt"></i></td>';
		if ( $type == 'CAA' ){
			// TODO: nevieme
			//	$td .= $this->returnTd( $data, [ 'name', 'content', 'ttl', 'note' ]);
		} elseif ( $type == 'DNSSEC' ) {
			// TODO: nevieme
			// $td .= $this->returnTd( $data, [ 'name', 'content', 'ttl', 'note' ]);
		} elseif ( $type == 'MX' ) {
			$td = $this->returnTd( $data, [ 'name', 'content', 'prio', 'ttl', 'note' ]);
		} elseif ( $type == 'SRV' ) {
//			$td = $td_start;
			$td = $this->returnTd( $data, [ 'name', 'content', 'port', 'prio', 'weight', 'ttl', 'note' ]);
			$td .= $td_end;
		} else {
//			$td = $td_start;
			$td = $this->returnTd( $data, [ 'name', 'content', 'ttl', 'note' ]);
			$td .= $td_end;
		}

		return $td;
	}

}