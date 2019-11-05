<?php

$function = isset( $_REQUEST['function'] ) ? $_REQUEST['function'] : '';
$user_id = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$content_id = isset( $_REQUEST['content_id'] ) ? $_REQUEST['content_id'] : '';
$type = isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : '';
$domain = isset( $_REQUEST['domain'] ) ? $_REQUEST['domain'] : '';
$data = isset( $_REQUEST['data'] ) ? $_REQUEST['data'] : [];
$html = $html_function = $html_button = '';

if ( !empty( $function ) && !empty( $type ) && !empty( $domain ) ){
	function getForm( $user_id, $content_id, $domain, $function, $type, $data = [], $inputs = []){
		$ret = '';
		if ( !empty( $user_id ) ){
			$ret .= '<input type="hidden" id="user_id" value="' . $user_id . '"/>';
		}
		if ( !empty( $content_id ) ){
			$ret .= '<input type="hidden" id="content_id" value="' . $content_id . '"/>';
		}
		$ret .= '<input type="hidden" id="domain" value="' . $domain . '"/>';
		$ret .= '<input type="hidden" id="func" value="' . $function . '"/>';
		$ret .= '<input type="hidden" id="type" value="' . $type . '"/>';
		if ( !empty( $inputs) && is_array( $inputs ) ) foreach ( $inputs as $input ){
			if ( $input == 'name' ){
				$value = isset( $data['name'] ) ? $data['name'] : '';
				if ( $value == $domain ){
					$value = '@';
				} else {
					$value = str_replace( '.' . $domain, '', $value );
				}
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="name" class="col-md-3 col-form-label text-right">Pre Adresu</label>
								<input id="name" name="name" type="text" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
								<div class="input-group-append">
									<span class="input-group-text" id="basic-addon2">.' . $domain . '</span>
								</div>
							</div>
						  </div>';
			} elseif ( $input == 'ip4' ){
				$value = isset( $data['content'] ) ? $data['content'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Cieľová IP</label>
								<input id="content" name="content" type="text" class="form-control ip4" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'ip6' ){
				$value = isset( $data['content'] ) ? $data['content'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Cieľová IP</label>
								<input id="content" type="text" class="form-control ip6" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'adress' ){
				$value = isset( $data['content'] ) ? $data['content'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Adresa služby</label>
								<input id="content" type="text" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'content' ){
				$value = isset( $data['content'] ) ? $data['content'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Cieľová adresa</label>
								<input id="content" type="text" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'value' ){
				$value = isset( $data['content'] ) ? $data['content'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Hodnota</label>
								<input id="content" type="text" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'mail' ){
				$value = isset( $data['content'] ) ? $data['content'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Mail server</label>
								<input id="content" type="text" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'priority' ){
				$value = isset( $data['prio'] ) ? $data['prio'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Priorita</label>
								<input id="content" type="number" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'weight' ){
				$value = isset( $data['weight'] ) ? $data['weight'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Váha</label>
								<input id="content" type="number" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'port' ){
				$value = isset( $data['port'] ) ? $data['port'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="content" class="col-md-3 col-form-label text-right">Port</label>
								<input id="content" type="number" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			} elseif ( $input == 'ttl' ){
				$value = isset( $data['ttl'] ) ? $data['ttl'] : '600';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="ttl" class="col-md-3 col-form-label text-right">TTL</label>
								<input id="ttl" type="number" class="form-control col-md-2" aria-describedby="basic-addon2" value="' . $value . '">
								<div class="input-group-append">
									<span class="input-group-text" id="basic-addon2">Sekúnd</span>
								</div>
							</div>
						  </div>';
			} elseif ( $input == 'note' ){
				$value = isset( $data['note'] ) ? $data['note'] : '';
				$ret .= '<div class="form-group row">
							<div class="input-group">
								<label for="note" class="col-md-3 col-form-label text-right">Poznámka</label>
								<input id="note" type="text" class="form-control" aria-describedby="basic-addon2" value="' . $value . '">
							</div>
						  </div>';
			}
		}
		return $ret;
	}

	function fillInputs( $type ){
		$ret = [];
		if( $type == 'A' ){
			$ret = [ 'name', 'ip4', 'ttl', 'note' ];
		} elseif( $type == 'AAAA' ){
			$ret = [ 'name', 'ip6', 'ttl', 'note' ];
		} elseif( $type == 'MX' ){
			$ret = [ 'name', 'mail', 'priority', 'ttl', 'note' ];
		} elseif( $type == 'SRV' ){
			$ret = [ 'name', 'adress', 'port', 'priority', 'weight', 'ttl', 'note' ];
		} elseif( $type == 'TXT' ){
			$ret = [ 'name', 'value', 'ttl', 'note' ];
		} else {
			// aname, cname, ns
			$ret = [ 'name', 'content', 'ttl', 'note' ];
		}
		return $ret;
	}

	$inputs = fillInputs( $type );

	if ( $function == 'insert' ){
		$html_title = '<h5 class="modal-title" id="exampleModalLabel">Pridať nový ' . $type . ' záznam</h5>';
		$html_button = '<button type="button" class="d-flex align-items-center submit btn btn-primary" onclick="submitVex();">
				<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true" style="display: none;"></span>
				<span>Pridať</span></button>';
	} elseif ( $function == 'update' ){
		$html_title = '<h5 class="modal-title" id="exampleModalLabel">Upraviť ' . $type . ' záznam</h5>';

		$html_button = '<button type="button" class="d-flex align-items-center submit btn btn-primary" onclick="submitVex();">
			<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true" style="display: none;"></span>
			<span>Uložiť</span></button>';
	} elseif ( $function == 'delete' ){
		$html_title = '<h5 class="modal-title" id="exampleModalLabel">Zmazať ' . $type . ' záznam</h5>';

		$html_button = '<button type="button" class="d-flex align-items-center submit btn btn-danger" onclick="submitVex();">
			<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true" style="display: none;"></span>
			<span>Zmazať</span></button>';
		$inputs = [];
	}

	//modal

	$html = '<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
	$html .= '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">';
	$html .= '<div class="modal-content">';
	$html .= '<div class="modal-header">';
	$html .= $html_title;
	$html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
	$html .= '<span aria-hidden="true">&times;</span>';
	$html .= '</button>';
	$html .= '</div>';
	$html .= '<div class="modal-body">';
	$html .= '<div class="container p-md-3 rounded-lg backgrounded">';
	$html .= '<form class="">';
	$html .= '<p class="text-center status"></p>';

	if ( $function == 'delete') {
		$html .= '<p class="text-center mb-5">Skutočne chcete zmazať tento záznam?</p>';
	}

	$html .= getForm( $user_id, $content_id, $domain, $function, $type, $data , $inputs );
	$html .= '</div>';
	$html .= '<div class="modal-footer">';
	$html .= '<button type="button" class="btn btn-secondary" data-dismiss="modal">Zavrieť</button>';
	$html .= $html_button;
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</form>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';

}

//echo $html;
echo json_encode( [ 'html' => $html ] );