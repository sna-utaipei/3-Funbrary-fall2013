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
 
// 取得粉絲團最新一筆話題
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

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Funbrary</title>

<!-- BLOCK: Loading libraries -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
<!-- ENDBLOCK: Loading libraries -->
</head>
 
<body>
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
        window.fbLoaded();
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
          <a href="index.html" class="navbar-brand"><font size="5">Funbrary</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
          <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="index.html">FEED</a>         
            </li>
            <li>
                <a href="blog.html">FAVORITE LIST</a>
            </li>
            <li>
                <a data-toggle="modal" href="#myModal">CONTACT</a>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li>
                <a id="my-login-button">Login</a>
            </li>
            <li>
                <a id="my-logout-button">Logout</a></font>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">

    </nav>

    <div class="container">
        <div class="jumbotron">
          <h1>Make your life fun.</h1>
        </div>

        <?php
            $datas = array('cherngs.y','MissUndine','lusia.chiachinlu','sinkcomic','ByeByeChuChu','LaoBanZheYu','9gag','nagee.tw');

            for($i=0;$i<count($datas);$i++){
             
                $page = '';
                $topic = '';
             
                $page = getPageInfo($datas[$i]);
                $topic = getPageTopic($datas[$i]);
                $profile = 'http://graph.facebook.com/'.$datas[$i].'/picture';
        ?>
        
            <div id="post-content" class="row">
                <div id="feed" class="col-md-8 col-md-offset-2">
                    <div class="page-header">
                        <h1 id="page-name"><img src="<?php echo $profile; ?>"><strong><?php echo $page['name'];?></strong> <small>有 <?php echo $page['likes'];?> 人說 <?php echo $page['name'];?> 讚</small></h1>
                    </div>

                    <div id="post-picture">
                        <?php 
                            for($j=0;$j<6;$j++){

                                $fql = 'SELECT src_big  FROM photo WHERE object_id="'.$topic[$j]['object_id'].'"';

                                $param  =   array(
                                     'method'    => 'fql.query',
                                     'query'     => $fql,
                                     'callback'  => ''
                                    );

                                $fqlResultSrc   =   $facebook->api($param);

                                echo '<div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h1 class="panel-title">'.$topic[$j]['message'].'</h1>
                                        </div>';

                                echo '<div class="panel-body">
                                        <img href="'.$topic[$j]['link'].'" class="img-rounded img-responsive" alt="Responsive image" src="'.$fqlResultSrc[0]['src_big'].'"/></div>
                                        <!--<div id="my-like-btn"><div class="fb-like" data-href="http://mighty-savannah-4409.herokuapp.com/test.php"></div>
                                      </div>-->
                                        <!--<div class="fb-comments" data-href="http://example.com/comments" data-numposts="5" data-colorscheme="light"></div>-->
                                    </div>';                
                            }
                        ?>  
                    </div><!--/ENDPOST-->
                </div><!--/ENDPOST_CONTENT-->
            </div><!--/CONTAINER-->
               
        <?php
        }
        ?>
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
              <div class="col-md-8 col-md-offset-3">
               <address>
                <strong>Linda Wu (Wu, Pei-Ju)<br>
                Ethan Lee (Lee, Cheng-Yang)<br>
                Jason Chang (Chang, Jia-Ming)</strong><br>              
              </address>
              <address>
                <em>Dept. of Computer Science<br>
                University of Taipei</em><br>  
              </address>
              <!--<address>
                <span class="glyphicon glyphicon-earphone"></span>   +886-972-238-881<br>
                <span class="glyphicon glyphicon-envelope"></span><a href="mailto:#"> linda66585@gmail.com</a>
              </address>-->
              </div><!--./col-md-8-->
             </div><!--row--> 
             <!--<div class="btn-group col-md-offset-3">
                <a class="btn btn-primary btn-lg" href="http://facebook.com/linda66585" target="_blank" role="button" class="btn btn-default">Facebook</a>
                <a class="btn btn-danger btn-lg" href="http://instagram.com/lyndawu" target="_blank" role="button" class="btn btn-defult">Instagram</a>
             </div><!./btn-group-->                      
            </div><!--modal body-->
          <div class="modal-footer">
            <button class="btn btn-success btn-lg" role="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div><!--footer-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

     <script id="my-script-playground">
        window.fbLoaded = function(){
            // define the events when login status changed.
            FB.Event.subscribe('auth.login', function(response) {
                // when user has been logged in, this block will be triggered.
                //var msg = "You're logged in.";
                //$("#my-login-message").html(msg);
                console.log("Your login response:");
                console.log(response);
            });

            FB.Event.subscribe('auth.statusChange', function(response) {
                      if (response.status === 'connected') {
                        // the user is logged in and has authenticated your
                        // app, and response.authResponse supplies
                        // the user's ID, a valid access token, a signed
                        // request, and the time the access token 
                        // and signed request each expire
                        var uid = response.authResponse.userID;
                        var accessToken = response.authResponse.accessToken;
                      } else {
                        FB.login(function (response) {
                            if (response.authResponse) {
                                var uid = response.authResponse.userID;
                                var accessToken = response.authResponse.accessToken;
                            } else {
                                alert('登入失敗!');
                            }}, {
                                scope: 'publish_stream'
                            });
                        // the user isn't logged in to Facebook.
                      }
            });

            FB.getLoginStatus(function(response) {
                      if (response.status === 'connected') {
                        // the user is logged in and has authenticated your
                        // app, and response.authResponse supplies
                        // the user's ID, a valid access token, a signed
                        // request, and the time the access token 
                        // and signed request each expire
                        var uid = response.authResponse.userID;
                        var accessToken = response.authResponse.accessToken;
                      } else {
                        FB.login(function (response) {
                            if (response.authResponse) {
                                var uid = response.authResponse.userID;
                                var accessToken = response.authResponse.accessToken;
                            } else {
                                alert('登入失敗!');
                            }}, {
                                scope: 'publish_stream'
                            });
                        // the user isn't logged in to Facebook.
                      }
                     });


            // define the action when user clicked the login button.
            $("#my-login-button").click(function(){
                FB.getLoginStatus(function(response) {
                      if (response.status === 'connected') {
                        // the user is logged in and has authenticated your
                        // app, and response.authResponse supplies
                        // the user's ID, a valid access token, a signed
                        // request, and the time the access token 
                        // and signed request each expire
                        var msg = "You're logged in.";
                        alert(msg);
                      } else {
                        FB.login(function (response) {
                            if (response.authResponse) {
                                var uid = response.authResponse.userID;
                                var accessToken = response.authResponse.accessToken;
                            } else {
                                alert('登入失敗!');
                            }}, {
                                scope: 'publish_stream'
                            });
                        // the user isn't logged in to Facebook.
                      }
                     });

            });

            $("#my-logout-button").click(function(){
                FB.logout();
                //window.location.reload();
            });



            // send me a friend request by using Facebok Friends Dialog
            $("#my-friend-button").click(function(){
                FB.api('/me', function (profile_response) {
                    var my_id = profile_response.id;

                    FB.ui({
                        'method': 'friends',
                        'id': my_id
                    }, function(friend_response) {
                        var she_say_yes = friend_response.action;

                        if (she_say_yes) {
                            alert("Thank you! We will be good friends.");
                        } else {
                            alert("Alright, it must be my wrong :(");
                        }
                    });
                });
            });

            $("#my-post-on-wall-button").click(function(){
                var body = document.getElementById('my-post-on-wall').value;
                FB.api('/me/feed', 'post', { message: body }, function(response) {
                  if (!response || response.error) {
                    alert('Error occured');
                  } else {
                    alert('Post on your wall Successfully!!');
                  }
                });
            });

/*
            var fetch_my_profile = function () {
                /*
                Fetching profile information.
                For more detail, please vist the following url:

                (Graph API: User documentation)
                https://developers.facebook.com/docs/graph-api/reference/user/
                */

                /*
                FB.api('/me', function(response) {
                    var my_name = response.name;
                    var my_gender = response.gender;
                    var my_username = response.username;
                    var my_facebook_id = response.id;

                    $("#my-profile-name").html(my_name);
                    $("#my-profile-gender").html(my_gender);
                    $("#my-profile-username").html(my_username);
                    $("#my-profile-facebook-id").html(my_facebook_id);
                });

                /*
                Fetching profile picture from Facebook.
                For more detail, please visit the following url:

                (Graph API: User/Picture reference)
                https://developers.facebook.com/docs/graph-api/reference/user/picture/
                */

                /*
                FB.api('/me/picture?width=250', function(response) {
                    var my_picture_url = response.data.url;
                
                    $("#my-profile-picture").attr('src', my_picture_url);
              });
                */
            };
        };
    </script>
    <!-- ENDBLOCK: Your script playground -->
 
</body>
</html>
