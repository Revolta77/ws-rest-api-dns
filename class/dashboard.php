<?php

use ws\Connection;

class Dashboard extends Connection {

	public function getDasboard( $data, $hash){
		$hide_container = '';
		$links = $records = [];
		if( $hash != '' ){
			$hide_container = ' style="display:none;"';
		}

		$types = $this->getDnsTypes();
		if( !empty( $types ) ) foreach ( $types as $type ) {
			$links[] = '<a class="dropdown-item" data-name="' . $type .'" 
				data-url="dns-' . $type . '" href="javascript:;" onclick="navLink(this);jQuery(\'#dropdownMenuLink\').click()" >' . $type .'</a>';
		}

		if( !empty( $data ) ) foreach ( $data as $key => $value ){
			$div = '<div class="row">
				<div class="d-flex col-2 justify-content-center align-self-center"><b>' . $key . '</b></div>';
			$div .= '<div class="d-flex col-6 align-self-center flex-column dns_record">';
			$x = 0;
			if( !empty( $value) && is_array( $value ) ) foreach ( $value as $index => $record ){
				if ( $index == 0 ){
					$div .= '<span>smerujú na ' . $record['content'] . '</span>';
				} else {
					$x++;
				}
			}
			if ( $x > 0 ){
				switch (true) {
					case ($x == 1):
						$record_text = 'záznam';
						break;

					case ($x > 1 && $x < 5):
						$record_text = 'záznamy';
						break;

					default:
						$record_text = 'záznamov';
						break;
				}
				$div .= '<br/><span><b>+ ' . $x . ' ' . $key .' ' . $record_text . '</b></span>';

			}
			$div .= '</div>';
			$div .=	'<div class="d-flex col-4 justify-content-center align-self-center">
						<a class="nav-link collapsed" data-toggle="collapse" data-name="' . $key .'" data-url="dns-' . $key . '" 
						href="javascript:;" onclick="navLink(this);">Upraviť '. $key .' záznamy<i class="fa fa-angle-right"></i></a></div>
				</div>';
			$records[] = $div;
		}

		return '
		<div class="container-fluid dashboard container_content"' . $hide_container . '>
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Prehľad</h1>
			</div>
			<div class="row">
				<div class="col-xl-11 col-lg-11">
					<div class="card shadow mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="m-0 font-weight-bold text-primary">DNS nastavenia</h6>
							<div class="dropdown no-arrow">
								<a class="dropdown-toggle" href="javascript:;" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
									<div class="dropdown-header">Zobraziť nastavenie pre záznamy:</div>
									' . implode('', $links ) . '
								</div>
							</div>
						</div>
						<div class="card-body">
							' . implode('<div class="dropdown-divider"></div>', $records) . '
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
}