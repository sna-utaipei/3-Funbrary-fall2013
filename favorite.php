<?php 
    require './facebook.php';
    set_time_limit(100); 
        $facebook = new Facebook(array(
          'appId'  => '591689687547208',
          'secret' => 'e9ae2fe5fec474022dabc38ad4262a6d',
          'cookie' => true, // enable optional cookie support
        ));

    $databaseConnection = pg_connect("host=ec2-54-197-249-167.compute-1.amazonaws.com port=5432 dbname=d1deq0bdiotmdi user=plsrhyziizmras password=I9cXpT9VDHk2AcHKVvyPfPWWti sslmode=require options='--client_encoding=UTF8'") or die('Could not connect: ' . pg_last_error());
    $rows; 
    // 取得粉絲團相關資訊
    function getPageInfo($page) {
        $fbpageinfo =   '';
     
        global $facebook;
     
        try {
            $fbpageinfo = $facebook->api("/$page");
        }
        catch(Exception $o) {
            //print_r($o);
        }
     
        return $fbpageinfo;
    }
     
    // 取得粉絲團最新話題
    function getPagePosts($page,$catalogy) {
        $fbpagetopic =   '';
        $array = array();
        $count = 0;
        global $facebook;
        global $databaseConnection;
        global $rows;

        if($_COOKIE['user_id'] != null){                
                
            $user_id = $_COOKIE['user_id'];

            if($catalogy == 'general'){
                $query_fetch_posts = "SELECT post_id FROM favorite WHERE (user_id = '$user_id' AND author = '$page');";
                $statement_fetch_posts = pg_query($databaseConnection,$query_fetch_posts);
                $rows = pg_num_rows($statement_fetch_posts);
                if($rows != 0){
                    while($obj = pg_fetch_object($statement_fetch_posts)) {
                              $post_list[] = $obj;
                          }
                    try {
                        for($i=0;$i<$rows;$i++) {
                            $post = $post_list[$i]->post_id;
                            $fbpagetopic = $facebook->api("/$post");
                            $array[$count] = $fbpagetopic;
                            $count++;
                        }
                    }
                    catch(Exception $o) {
                        //print_r($o);
                    }
                }
            }else{
                $query_fetch_posts = "SELECT post_id FROM User_fav WHERE (user_id = '$user_id' AND author = '$page' AND catalogy = '$catalogy');";
                $statement_fetch_posts = pg_query($databaseConnection,$query_fetch_posts);
                $rows = pg_num_rows($statement_fetch_posts);
                if($rows != 0){
                    while($obj = pg_fetch_object($statement_fetch_posts)) {
                              $post_list[] = $obj;
                          }

                    try {
                        for($i=0;$i<$rows;$i++) {
                            $post = $post_list[$i]->post_id;
                            $fbpagetopic  = $facebook->api("/$post");
                            $array[$count] = $fbpagetopic;
                            $count++;
                        }
                    }
                    catch(Exception $o) {
                        //print_r($o);
                    }
                }
            }         
            
        }
        return $array;
    }

    function textEnter($string)
    {
        $result = str_replace("\n", '<br>', $string);
        return $result;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Funbrary</title>
	<link rel="shortcut icon" href="funbrary-icon.png">
<!-- BLOCK: Loading libraries -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
    <script src="./funbrary.js" type="text/javascript"></script>
    <script src="./your_secret.js"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./mbExtruder.css" media="all" type="text/css">
    <script type="text/javascript" src="./jquery.hoverIntent.min.js"></script>
    <script type="text/javascript" src="./jquery.mb.flipText.js"></script>
    <script type="text/javascript" src="./mbExtruder.js"></script>
    <script type="text/javascript">
        $(function(){
            /*
            var _showTab = 0;
            $('.fav_tab').each(function(){
                // 目前的頁籤區塊
                var $tab = $(this);
         
                var $defaultLi = $('ul[class='nav nav-tabs'] li', $tab).eq(_showTab).addClass('active');
                $($defaultLi.find('a').attr('href')).siblings().hide();
         
                // 當 li 頁籤被點擊時...
                // 若要改成滑鼠移到 li 頁籤就切換時, 把 click 改成 mouseover
                $('ul.[class='nav nav-tabs'] li', $tab).click(function() {
                    // 找出 li 中的超連結 href(#id)
                    var $this = $(this),
                        _clickTab = $this.find('a').attr('href');
                    // 把目前點擊到的 li 頁籤加上 .active
                    // 並把兄弟元素中有 .active 的都移除 class
                    $this.addClass('active').siblings('.active').removeClass('active');
                    // 淡入相對應的內容並隱藏兄弟元素
                    $(_clickTab).stop(false, true).fadeIn().siblings().hide();
         
                    return false;
                }).find('a').focus(function(){
                    this.blur();
                });
            });*/

            if (self.location.href == top.location.href){
                $("body").css({font:"normal 13px/16px 'trebuchet MS', verdana, sans-serif"});
                var logo=$("<a href='http://pupunzi.com'><img id='logo' border='0' src='http://pupunzi.com/images/logo.png' alt='mb.ideas.repository' style='display:none;'></a>").css({position:"absolute"});
                $("body").prepend(logo);
                $("#logo").fadeIn();
            }

            $("#extruderLeft").buildMbExtruder({
                position:"left",
                width:300,
                extruderOpacity:.8,

                hidePanelsOnClose:false,
                accordionPanels:false,
                onExtOpen:function(){},
                onExtContentLoad:function(){$("#extruderLeft").openPanel();},
                onExtClose:function(){}
            });

            $("#extruderRight").buildMbExtruder({
                position:"right",
                width:300,
                extruderOpacity:.8,
                textOrientation:"tb",
                onExtOpen:function(){},
                onExtContentLoad:function(){},
                onExtClose:function(){}
            });

            $.fn.changeLabel=function(text){
                $(this).find(".flapLabel").html(text);
                $(this).find(".flapLabel").mbFlipText();
            };

        });
    </script>
</head>
<body bgclolr="#C9C9C9" data-target="#sidebar" data-spy="scroll" bgproperties="fixed">
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        // init the FB JS SDK
        FB.init({
        appId      : FacebookAppId,                        // App ID from the app dashboard
        cookie     : true,                                 // Allowed server-side to fetch fb auth cookie
        status     : true,                                 // Check Facebook Login status
        xfbml      : true                                  // Look for social plugins on the page
        });

        // Additional initialization code such as adding Event Listeners goes here
        fbLoaded();
    };
    // Load the SDK asynchronously
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s); 
        js.id = id;
        //js.src = "//connect.facebook.net/en_US/all.js";
        // Debug version of Facebook JS SDK
        js.src = "//connect.facebook.net/en_US/all/debug.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="index.php" class="navbar-brand"><font size="5">Funbrary</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
          <ul class="nav navbar-nav">
            <li><a href="favorite.php">Favorite Lists</a></li>
            <li><a data-toggle="modal" href="#myModal">Contact</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <!--<li><a id="contact" data-toggle="modal" href="#myModal">Contact</a></li>--></font>
          </ul>
        </nav>
    </div>
</div>      
<nav class="navbar navbar-default navbar-static-top" role="navigation"></nav>

<div class="container">
    <div class="jumbotron">
        <ul class="nav nav-tabs">
          <li><a href="#General" class="fav_tab" data-toggle="tab">All</a></li>
          <?php 
            $list_name = array();
            if($_COOKIE['user_id'] != null){
                $user_id = $_COOKIE['user_id'];                
                $query_fetch_list_name = "SELECT cata_name FROM catalogy WHERE owner_id = '$user_id'";
                $statement_fetch_list_name = pg_query($databaseConnection,$query_fetch_list_name);
                //$query_result = pg_fetch_row($statement_fetch_list_name);
                $result_num = pg_num_rows($statement_fetch_list_name);
                if($result_num!=0){
                    while($obj = pg_fetch_object($statement_fetch_list_name)) {
                          $list_name[] = $obj;
                    }
                }else{
                    $list_name[0] = null;
                }
            }
            if($list_name[0]!=null){
                foreach ($list_name as $key => $value) {
                    $name = str_replace("  ", "", $value->cata_name);
                    if(substr($name, -1) == " "){
                        $name = substr($name, 0 , -1);
                    }                        
                    echo '<li><a href="#'.$name.'" class="fav_tab" value="'.$name.'" data-toggle="tab">'.$name.'</a></li>';
                }
            }
          ?><!--
          <li><a href="#profile" class="fav_tab" data-toggle="tab">Profile</a></li>
          <li><a href="#messages" class="fav_tab" data-toggle="tab">Messages</a></li>
          <li><a href="#settings" class="fav_tab" data-toggle="tab">Settings</a></li>-->
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="General">
                <!--<iframe src="fav_list.php" align="center" width="780" height="850" marginwidth="0" scrolling="yes"></iframe>
               -->
                <div id="post-content" class="row"><!--Post 放的地方-->
                <?php
                    $datas = array('cherngs.y','MissUndine','lusia.chiachinlu','cwwany.tw','sinkcomic','ByeByeChuChu','LaoBanZheYu','Aidahello','9gag','nagee.tw');
                    global $databaseConnection;
                    
                    if($_COOKIE['user_id'] != null)
                    {                
                        
                        $author_list = array();
                        $author = array();
                        $count = 0;
                        $query_fetch_general = "SELECT author FROM favorite WHERE user_id = '$user_id' ORDER BY id;";
                        $statement_fetch_general = pg_query($databaseConnection,$query_fetch_general);
                        $result_general = pg_num_rows($statement_fetch_general);
                        if($result_general!=0){
                            while($obj = pg_fetch_object($statement_fetch_general)) {
                                  $author_list[] = $obj;
                            }
                            foreach($author_list as $k => $value) {

                                if(in_array($value->author, $datas))
                                {
                                    if(in_array($value->author, $author))
                                    {   
                                        continue;
                                    }
                                    $author[$count] = $value->author;
                                    $count++;
                                }
                            }
                        }
                    }

                    for($i=0;$i<count($author);$i++){
                     
                        $page = '';
                        $topic = '';
                     
                        $page = getPageInfo($author[$i]);
                        $topic = getPagePosts($author[$i],'general');
                        $profile = 'http://graph.facebook.com/'.$author[$i].'/picture';
                ?>
                    <div id="feed" class="col-xs-8 col-md-7 col-md-offset-2">
                        <div><h1 id="<?php echo $author[$i]; ?>"></h1></div><!--TAG-->
                        <div class="page-header">
                            <h2 class="title"><img src="<?php echo $profile; ?>"><strong><?php echo $page['name'];?></strong></h2>
                        </div>                
                        <?php 
                          global $rows;
                          for($j=0;$j<count($topic);$j++){

                            $fql = 'SELECT src_big  FROM photo WHERE object_id="'.$topic[$j]['object_id'].'"';

                            $param  =   array(
                                 'method'    => 'fql.query',
                                 'query'     => $fql,
                                 'callback'  => ''
                                );

                            $fqlResultSrc   =   $facebook->api($param);
                            $message = textEnter($topic[$j]['message']);
                            $postId = explode("_", $topic[$j][id]);
                            $link = 'https://graph.facebook.com/'.$postId[0].'/posts/'.$postId[1];
                            $createed_time = substr($topic[$j]['created_time'],0,10);
                        ?>
                        <div id="post-picture" class="row">
                            <div class="panel panel-primary frame">
                                <div class="panel-body">
                                    <a href="<?php echo $topic[$j]['link']; ?>" target="_blank"><img id="my-img" class="img-rounded img-responsive" alt="Responsive image" src="<?php echo $fqlResultSrc[0]['src_big']; ?>"/></a>
                                        <!--<br>
                                        <div><button id="my-like-btn-<?php /*echo $topic[$j][id];?>" class="like" value="<?php echo $topic[$j][id];?>"><span class="glyphicon glyphicon-heart"></span>  Having Fun!</button>
                                             <button id="my-unlike-btn-<?php echo $topic[$j][id];?>" class="unlike" value="<?php echo $topic[$j][id];?>"><span class="glyphicon glyphicon-heart-empty"></span>  Not Really</button>
                                             <div class="btn-group">
                                             <form action="index.php" method="post">                               
                                             <button id="my-add-btn-<?php echo $topic[$j][id];?>" name="my-add-btn-<?php echo $topic[$j][id];?>" type="submit" class="add" data-toggle="dropdown" value="<?php echo $topic[$j][id];?>"><span class="glyphicon glyphicon-plus"></span>  Add to list</button>
                                             <span class="sr-only">Toggle Dropdown</span>
                                              <ul class="dropdown-menu" role="menu">
                                                  <?php 
                                                        for( $k=0;$k<$result_num;$k++ ){                                                
                                                            echo '<li><a href="#" id="'.$author[$i].'" class="userdefine" value="'.$topic[$j][id].'" data-cata="'.$list_name[$k]->cata_name.'">'.$list_name[$k]->cata_name.'</a></li>';
                                                        }
                                                  ?>
                                                  <li><a href="#" id="<?php echo $author[$i];?>" class="general" value="<?php echo $topic[$j][id]; */?>">General</a></li>
                                                  <li class="divider"></li>
                                                  <li><a data-toggle="modal" href="#add_list_modal" class="add_new_list"><span class="glyphicon glyphicon-plus"></span>  New List</a></li>
                                                  
                                              </ul>
                                             </div><!/btn-group
                                               
                                        </div>
                                        </form>-->
                                    </div><!--/panel-body-->
                                <div class="panel-heading">
                                    <h2 class="panel-title"><?php echo $message; ?></h2><br>
                                    <div class="fb-share-button" data-href="<?php echo $topic[$j]['link'];?>" data-type="button"></div>
                                    <p id="createdTime">發表於<?php echo $createed_time ?></p>
                                </div> 
                            </div><!--/panel panel-primary-->
                        </div><!--/post-picture-->
                        <?php
                          }
                        ?>      
                    </div><!--/feed-->         
                    <?php
                    }
                    ?>
                </div><!--/ENDPOST_CONTENT-->     
            </div><!--tab-pane active-->
            <?php 
                if($list_name[0]!=null){
                    foreach($list_name as $l=>$value){
                        $name = str_replace("  ", "", $value->cata_name);
                        if(substr($name, -1) == " "){
                            $name = substr($name, 0 , -1);
                        } 
                        echo '<div class="tab-pane" id="'.$name.'"><div id="post-content" class="row">';
                        $author_list = array();
                        $author = array();
                        $count = 0;
                        $query_fetch_author = "SELECT author FROM User_fav WHERE user_id = '$user_id' ORDER BY id;";
                        $statement_fetch_author = pg_query($databaseConnection,$query_fetch_author);
                        $result_general = pg_num_rows($statement_fetch_author);
                        if($result_general!=0){
                            while($obj = pg_fetch_object($statement_fetch_author)) {
                                  $author_list[] = $obj;
                            }
                            foreach ($author_list as $k => $value) {
                                if(in_array($value->author, $author))
                                {   
                                    continue;
                                }
                                if(in_array($value->author, $datas))
                                {
                                    $author[$count] = $value->author;
                                    $count++;
                                }
                            }
                        }

                    for($i=0;$i<count($author);$i++){
                     
                        $page = '';
                        $topic = '';
                     
                        $page = getPageInfo($author[$i]);
                        $topic = getPagePosts($author[$i],$name);
                        $profile = 'http://graph.facebook.com/'.$author[$i].'/picture';
                ?>
                    <div id="feed" class="col-xs-8 col-md-7 col-md-offset-2">
                        <div id="<?php echo $page[$i]; ?>" class="page-header">
                            <h2 class="title"><img src="<?php echo $profile; ?>"><strong><?php echo $page['name'];?></strong></h2>
                        </div>

                        
                            <?php 
                              global $rows;
                              for($j=0;$j<count($topic);$j++){

                                $fql = 'SELECT src_big  FROM photo WHERE object_id="'.$topic[$j]['object_id'].'"';

                                $param  =   array(
                                     'method'    => 'fql.query',
                                     'query'     => $fql,
                                     'callback'  => ''
                                    );

                                $fqlResultSrc   =   $facebook->api($param);
                                $message = textEnter($topic[$j]['message']);
                                $postId = explode("_", $topic[$j][id]);
                                $link = 'https://graph.facebook.com/'.$postId[0].'/posts/'.$postId[1];
                                $createed_time = substr($topic[$j]['created_time'],0,10);

                            ?>
                        
                            <div id="post-picture" class="row">
                                <div class="panel panel-primary frame">
                                    
                                    <div class="panel-body">
                                        <a href="<?php echo $topic[$j]['link']; ?>" target="_blank"><img id="my-img" class="img-rounded img-responsive" alt="Responsive image" src="<?php echo $fqlResultSrc[0]['src_big']; ?>"/></a>
                                        </form>
                                    </div><!--/panel-body-->
                                    <div class="panel-heading">
                                        <h2 class="panel-title"><?php echo $message; ?></h2><br>
                                        <div class="fb-share-button" data-href="<?php echo $topic[$j]['link'];?>" data-type="button"></div>
                                        <p id="createdTime">發表於<?php echo $createed_time ?><p>
                                    </div> 
                                </div><!--/panel panel-primary-->
                            </div><!--/ENDPOST-->
                        <?php
                          }
                        ?>      
                    </div><!--/feed-->         
                    <?php
                    }
                    ?>
                </div><!--/ENDPOST_CONTENT-->     
            </div><!--tab-pane-->
            <?php                    
                    }//foreach
                }//if($list_name[0]!=null)*/
            ?>
        </div><!--tab-content-->
    </div><!--jumbotron-->
</div><!--container-->
<div id="extruderLeft" class="a {title:' FAN PAGES ', url:'parts/extruderLeft.html'}"></div>
<div id="extruderRight" class="{title:' MY PROFILE'}">
    <div class="text">
        <img src="welcome.png" alt="" width="90%">
        <br>
        <br>
        <img id ="my-profile-picture" class="img-thumbnail" src="" alt="" width="90%">
        <br>
        <h3 id="my-profile-name"><button id="my-login-button" class="logbtn logbtn-primary">Login With Facebook!</button></h3>
        <br>
    </div>
    <div class="text">
        <img src="funbrary.png" alt="" width="90%">
        <h3>Fun Photos Set.
            Collect your 
            love posts
            everyday!
            </h3>
        <br>
        Start your leisure trip.
        Add your favorite photos to the list.
        Review those creative ideas and make your life fun.
        <br>
        <br>
        <button id="share_app" class="btn btn-warning">
            <span class="glyphicon glyphicon-hand-right"></span>    Share Now!</button>  
    </div>
    <style type="text/css">
        .extruder .text{font:14px/16px Arial,Helvetica,sans-serif;color:gray;padding:20px;border-bottom:1px solid #333333;}
        .navbar-right{margin-top:10px;}
    </style>
</div>
<!--Modal-->
<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Developers</h4>
       </div>
      <div class="modal-body" style="bgcolor:#CDFEFF">
          <!--<p align="center"><img src="./img/wu_3.jpg"  width="220" height="300" class="img-polaroid img-rounded" width="23%" style="position:center; z-index:2"></p>-->
        <div class="row">
        <div class="col-md-4">
          <img src="ethan.jpg" class="img-circle" width="140"px height="140"px><br><br>
          <address><strong>Ethan Lee <br>(Lee, Cheng-Yang)<br></address>
        </div>
        <div class="col-md-4">
          <img src="wupa.jpg" class="img-circle" width="140"px height="140"px><br><br>
          <address><strong>Linda Wu <br>(Wu, Pei-Ju)<br></address>
        </div>
        <div class="col-md-4">
          <img src="jumbo.jpg" class="img-circle" width="140"px height="140"px><br><br>
          <address><strong>Jason Chang <br>(Chang, Jia-Ming)<br></address>
        </div>
        </div><!--row--> 
        <div class="row">
          <div class="col-md-4"><span class="glyphicon glyphicon-envelope"></span><a href="mailto:#"> statine0322@gmail.com</a></div>
          <div class="col-md-4"><span class="glyphicon glyphicon-envelope"></span><a href="mailto:#"> linda66585@gmail.com</a></div>
          <div class="col-md-4"><span class="glyphicon glyphicon-envelope"></span><a href="mailto:#"> jumbomirror@gmail.com</a></div>
        </div>
        <br>
        <br>
        <br>
        <div class="row">
          <div class="col-md-6 col-md-offset-4">
          <address>
            <em>Dept. of Computer Science<br>
            University of Taipei</em><br>  
          </address>
          </div>
        </div><!--row2-->                      
      </div><!--modal body-->
    <div class="modal-footer">
    <button class="btn btn-default btn-lg" role="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div><!--footer-->
  </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>