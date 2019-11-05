<?php
/** @var $hash */
/** @var $user_id */

$domains = $api->getDomainsName($user_id);
if( isset($_COOKIE['domain']) ){
	$domain = $_COOKIE['domain'];
	$domain_id = $_COOKIE['domain_id'];
} else {
	$domain = $domains[0]['domain'];
	$domain_id = $domains[0]['domain_id'];
	$_COOKIE['domain'] = $domain;
	$_COOKIE['domain_id'] = $domain_id;
}

?>
<button id="sidebarToggleTop" class="btn btn-link d-lg-none rounded-circle mr-3" onclick="burgerMenu();">
    <i class="fa fa-bars"></i>
</button>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-lg-block" id="accordionSidebar">
	<li class="nav-item">
		<a class="nav-link collapsed" href="javascript:;" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			<i class="fas fa-server"></i>
			<span class="domain_name"><?php echo $domain;?></span>
            <input type="hidden" name="user_id" value="<?php echo $user_id;?>"/>
		</a>
		<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">
			<div class="bg-white py-2 collapse-inner rounded">
				<?php
				if( !empty($domains) ) foreach ( $domains as $key => $value ){
					$active = '';
					if( isset($_COOKIE['domain']) ){
						if( $value['domain'] == $_COOKIE['domain'] ){
							$active = ' active';
						}
					} else {
						if ( $key == 0 ){
							$active = ' active';
						}
					}
					?>
					<a class="collapse-item<?php echo $active;?>"
					   data-domain_name="<?php echo $value['domain'];?>"
					   data-domain_id="<?php echo $value['id'];?>"
					   href="javascript:;" onclick="changeDomain(this);"><?php echo $value['domain'];?></a>
					<?php
				}
				?>
			</div>
		</div>
	</li>
	<hr class="sidebar-divider my-0">
	<li class="nav-item<?php
	echo $hash == '' ? ' active' : '';?>">
		<a class="nav-link" data-name="Prehľad" data-url="/" href="javascript:;" onclick="navLink(this);">
			<i class="fas fa-fw fa-tachometer-alt"></i>
			<span>Prehľad</span></a>
	</li>
	<hr class="sidebar-divider">

	<div class="sidebar-heading">
		DNS
	</div>
	<?php

		$types = $api->getDnsTypes();
		if( !empty( $types ) ) foreach ( $types as $type ) {
			?>
			<li class="nav-item<?php echo $hash == 'dns-'.$type ? ' active' : '';?>">
				<a class="nav-link dns" data-name="<?php echo $type;?>" data-url="dns-<?php echo $type;?>" href="javascript:;" onclick="navLink(this);" >
					<i class="fas fa-circle"></i>
					<span><?php echo $type;?></span></a>
			</li>
			<?php
		}
	?>
	<hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link" href="https://github.com/Revolta77/ws-rest-api-dns.git" target="_blank">
            <i class="fab fa-github-square"></i>
            <span>GitHub</span></a>
    </li>
    <hr class="sidebar-divider">

</ul>