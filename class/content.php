<?php

class Content{

	public function getContent( $data, $domain, $hash ){
		require_once CLASSPATH . 'dashboard.php';
		require_once CLASSPATH . 'records.php';
		$dashboard = new Dashboard();
		$records = new Records();
		$html = '';
		$html .= '<div id="content-wrapper" class="d-flex flex-column pt-5">';
		$html .= '<div id="content">';
		$html .= $dashboard->getDasboard( $data[$domain], $hash);
		$html .= $records->getAll( $data, $domain, $hash );
		$html .= '</div>';
		$html .= '<footer class="sticky-footer bg-white">';
		$html .= '<div class="container my-auto">';
		$html .= '<div class="copyright text-center my-auto">';
		$html .= '<span>Copyright © Dušan Rybár 2019</span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</footer>';
		$html .= '</div>';
		return $html;
	}
}