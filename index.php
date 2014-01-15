<?php
    require './facebook.php';
    set_time_limit(100); 
        $facebook = new Facebook(array(
          'appId'  => '591689687547208',
          'secret' => 'e9ae2fe5fec474022dabc38ad4262a6d',
          'cookie' => true, // enable optional cookie support
        ));
     
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
    function getPageTopic($page) {
        $fbpagetopic =   '';
        $array = array();
        $count = 0;
        global $facebook;
     
        try {
            $fbpagetopic = $facebook->api("/$page/posts?limit=15");
            for($i=0;$i<15;$i++) {
                if($fbpagetopic['data'][$i]['type'] == "photo"){
                    $array[$count] = $fbpagetopic['data'][$i];
                    $count++;
                }
            }

        }
        catch(Exception $o) {
            //print_r($o);
        }
     
        return $array;
    }
    // 擷取部分字串後加刪節號
    function textLimit($string, $length, $replacer = '...')
    {
      if(strlen($string) > $length)
      return (preg_match('/^(.*)\W.*$/', mb_substr($string, 0, $length+1, 'UTF-8'), $matches) ? $matches[1] : mb_substr($string, 0, $length, 'UTF-8')) . $replacer;
     
      return $string;
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
<!-- ENDBLOCK: Loading libraries -->
<script type="text/javascript">

        $(function(){
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
<!-- BLOCK: Menu sidebar 
<! ENDBLOCK: Menu sidebar -->
</head>
<body background="bubble.jpg" data-target="#sidebar" data-spy="scroll" bgproperties="fixed">
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
<div class="jumbotron"><h1>&nbsp;&nbsp;&nbsp;Collecting your favorite posts!!</h1></div>
<div class="container">
    <div id="post-content" class="row">
        <?php
            $datas = array('cherngs.y','MissUndine','lusia.chiachinlu','cwwany.tw','sinkcomic','ByeByeChuChu','LaoBanZheYu','Aidahello','9gag','nagee.tw');

            if($_COOKIE['user_id'] != null)
            {
                $databaseConnection = pg_connect("host=ec2-54-197-249-167.compute-1.amazonaws.com port=5432 dbname=d1deq0bdiotmdi user=plsrhyziizmras password=I9cXpT9VDHk2AcHKVvyPfPWWti sslmode=require options='--client_encoding=UTF8'") or die('Could not connect: ' . pg_last_error());
                $user_id = $_COOKIE['user_id'];
                $query_fetch_list_name = "SELECT cata_name FROM catalogy WHERE owner_id = '$user_id';";
                $statement_fetch_list_name = pg_query($databaseConnection,$query_fetch_list_name);
                //$query_result = pg_fetch_row($statement_fetch_list_name);
                $result_num = pg_num_rows($statement_fetch_list_name);
                if($result_num!=0){
                    $list_name = array();
                    while($obj = pg_fetch_object($statement_fetch_list_name)) {
                          $list_name[] = $obj;
                    }
                }
            }
            for($i=0;$i<count($datas);$i++){
             
                $page = '';
                $topic = '';
             
                $page = getPageInfo($datas[$i]);
                $topic = getPageTopic($datas[$i]);
                $profile = 'http://graph.facebook.com/'.$datas[$i].'/picture';
        ?>
            <div id="feed" class="col-xs-8 col-md-8 col-md-offset-2">
                <div class="page-header"><h1 id="<?php echo $datas[$i]; ?>"></h1></div>
                <div class="page-header">
                    <h1 class="title"><img src="<?php echo $profile; ?>"><strong><?php echo $page['name'];?></strong> <small>有 <?php echo $page['likes'];?> 人說 <?php echo $page['name'];?> 讚</small></h1>
                </div>           
                <?php 
                  for($j=0;$j<6;$j++){

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
                        <div class="panel-heading">
                            <h1 class="panel-title"><?php echo $message; ?></h1><br>
                            <p id="createdTime">發表於<?php echo $createed_time ?><p>
                        </div> 
                        <div class="panel-body">
                            <a href="<?php echo $topic[$j]['link']; ?>" target="_blank"><img id="my-img" class="img-rounded img-responsive" alt="Responsive image" src="<?php echo $fqlResultSrc[0]['src_big']; ?>"/></a>
                            <br>
                            <div><button id="my-like-btn-<?php echo $topic[$j][id];?>" class="like" value="<?php echo $topic[$j][id];?>"><span class="glyphicon glyphicon-heart"></span>  Having Fun!</button>
                                 <button id="my-unlike-btn-<?php echo $topic[$j][id];?>" class="unlike" value="<?php echo $topic[$j][id];?>"><span class="glyphicon glyphicon-heart-empty"></span>  Not Really</button>
                                 <div class="btn-group">
                                 <form action="index.php" method="post">                               
                                 <button id="my-add-btn-<?php echo $topic[$j][id];?>" name="my-add-btn-<?php echo $topic[$j][id];?>" type="submit" class="add" data-toggle="dropdown" value="<?php echo $topic[$j][id];?>"><span class="glyphicon glyphicon-plus"></span>  Add to list</button>
                                 <span class="sr-only">Toggle Dropdown</span>
                                  <ul class="dropdown-menu" role="menu">
                                      <?php 
                                            for( $k=0;$k<$result_num;$k++ ){                                                
                                                echo '<li><a href="#" id="'.$datas[$i].'" class="userdefine" value="'.$topic[$j][id].'" data-cata="'.$list_name[$k]->cata_name.'">'.$list_name[$k]->cata_name.'</a></li>';
                                            }
                                      ?>
                                      <li><a href="#" id="<?php echo $datas[$i];?>" class="general" value="<?php echo $topic[$j][id]; ?>">All</a></li>
                                      <li class="divider"></li>
                                      <li><a data-toggle="modal" href="#add_list_modal" class="add_new_list"><span class="glyphicon glyphicon-plus"></span>  New List</a></li>                                 
                                  </ul>
                                 </div><!--/btn-group-->
                                 <div class="fb-share-button" data-href="<?php echo $topic[$j]['link'];?>" data-type="button"></div>   
                            </div>
                            </form>
                          </div><!--/panel-body-->
                    </div><!--/panel panel-primary frame-->
                </div><!--/post-picture-->
                    <?php
                      } 
                    ?>       
            </div><!--/feed-->         
        <?php
        }
        ?>
    </div><!--/post-content-->
</div><!--/container--> 
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
    </style>
</div><!--/extruderRight-->
       <!--Modal-->
<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Developers</h4>
    </div>
    <div class="modal-body" style="bgcolor:#CDFEFF">
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
        </div><!--/row--> 
        <div class="row">
          <div class="col-md-4"><span class="glyphicon glyphicon-envelope"></span><a href="mailto:#"> statine0322@gmail.com</a></div>
          <div class="col-md-4"><span class="glyphicon glyphicon-envelope"></span><a href="mailto:#"> linda66585@gmail.com</a></div>
          <div class="col-md-4"><span class="glyphicon glyphicon-envelope"></span><a href="mailto:#"> jumbomirror@gmail.com</a></div>
        </div><!--/row--> 
        <br>
        <br>
        <br>
        <div class="row">
          <div class="col-md-6 col-md-offset-4">
            <address><em>Dept. of Computer Science<br>University of Taipei</em><br></address>
          </div>
        </div><!--row2-->                      
  </div><!--modal body-->
<div class="modal-footer">
<button class="btn btn-default btn-lg" role="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div><!--footer-->
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal --> 

           <!-- Modal -->
      <div class="modal fade" id="add_list_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Enter new list name.</h4>
            </div>
            <div class="modal-body">
              <div class="col-lg-6">
                <div class="input-group">
                  <label class="sr-only" for="add_list_name">New List name</label>
                  <input id="add_list_name" type="text" class="form-control" placeholder="Enter list name">
                  <span class="input-group-btn">
                    <button id="add_list_btn" class="btn btn-danger" type="submit" data-dismiss="modal">New</button>
                  </span>
                </div><!-- /input-group -->
              </div><!-- /.col-lg-6 -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->


</body>
</html>
