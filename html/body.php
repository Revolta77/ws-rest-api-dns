<body id="page-top">
    <div id="wrapper">
    <?php
    /**
     * @var $data
     * @var $domain
     * @var $hash
     */
    include( 'html/menu.php' );
    require_once( CLASSPATH . 'content.php' );
    $content = new Content();
    echo $content->getContent( $data, $domain, $hash );
    ?>
    </div>
</body>