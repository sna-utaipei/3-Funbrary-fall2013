function fbLoaded(){
    var uid;
    var accessToken;
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
            uid = response.authResponse.userID;
            accessToken = response.authResponse.accessToken;
            document.cookie = "user_id="+uid;
            fetch_my_profile();
            FB.api('/me', function(response) {
                var my_name = response.name;
                var my_gender = response.gender;
                var my_username = response.username;
                    $.ajax({                                      
                        url:'insert.php',                                                              
                        data:'&action=users&user_id='+uid+'&user_name='+my_name+'&user_username='+my_username+'&user_gender='+my_gender,
                        //{action:'users', user_id:uid, user_name:my_name, user_username:my_username, user_gender:my_gender},
                        type:'POST',                                                                    
                        //processData: false,     
                        error:function ajaxFailure(ajax, exception) {
                              alert("Error making Ajax request:" + 
                                    "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
                                    "\n\nServer response text:\n" + ajax.responseText);
                              if (exception) {
                                throw exception;
                              } 
                        alert("失敗");
                        },
                        success:function(){ 
                        }
                    });
                });
          } else {
            FB.login(function (response) {
                if (response.authResponse) {
                    uid = response.authResponse.userID;
                    accessToken = response.authResponse.accessToken;
                    fetch_my_profile();
                } else {
                    alert('登入失敗!');
                }}, {
                    scope: 'publish_actions , publish_stream , read_stream , read_friendlists'
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
                    uid = response.authResponse.userID;
                    accessToken = response.authResponse.accessToken;
                    document.cookie = "user_id="+uid;
                    fetch_my_profile();

                    
                  }
        });


        // define the action when user clicked the login button.
        $("#my-login-button").click(function(){
            FB.getLoginStatus(function(response) {
                  if (response.status === 'connected') {
                    var msg = "You're logged in.";
                    alert(msg);
                  } else {
                    // the user isn't logged in to Facebook.
                    fblogin();
                    
                  }//getLoginStatus else
                });//end of getLoginStatus
        });
/*
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
*/
        //
        /*
        $(window).load(function(){
            var uid;
            var accessToken;

            FB.getLoginStatus(function(response) {
              if (response.status === 'connected') {
                uid = response.authResponse.userID;
                accessToken = response.authResponse.accessToken;
              }
            });

            var $add_dropdown = $(".dropdown-menu");
            $.ajax({
              url:'insert.php',//取得資料的頁面網址
              data:'&action=getList&owner_id='+uid,
              //async:false,
              //dataType:'JSON', //傳回的資料格式
              //ajax 成功後要執行的函式
              success:function(data){
                console.log('ajax success'); 
                  console.log('data not null');
                  console.log(data);
                  //cata_name:資料表的欄位名稱
                  //var Jdata = $.parseJSON(data);
                  $.each(data,function(index,item){                     
                     $add_dropdown.append(
                      '<li><a href="#" id="" class="userdefine" value="" data-cata="'+item.cata_name+'">' + 
                        item.cata_name + '</a></li>'
                     );
                  });
              },
              error:function ajaxFailure(ajax, exception) {
                      alert("Error making Ajax request:" + 
                            "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
                            "\n\nServer response text:\n" + ajax.responseText);
                      if (exception) {
                        throw exception;
                      }
                alert("失敗");
              },
              complete:function(){

                    var $add_dropdown = $(".dropdown-menu");
                    $add_dropdown.find(".general").each(function(i){
                    var li_id = $(this).attr('id');
                    var li_value = $(this).attr('value');
                    $(this).end().end().find("a.userdefine").attr({'id':li_id,'value':li_value});
                    });
                    
                    var $add_dropdown = $(".dropdown-menu");
                    $add_dropdown.find(".general").each(function(j){
                        var li_value = $(this).attr('value');
                        $(this).end().find(".userdefine").attr('value',li_value);
                    });
                    alert("成功");
              }
               
            });
        });
        */

        $(".like").click(function() {

            var user_accessToken = accessToken;
            var postId = $(this).attr('value');

            FB.api('/'+postId+'/likes', 'post', { access_token: user_accessToken }, function(result) {
                if (!result) {
                    alert('Error: No Response');
                } else if (result.error) {
                    alert('Error: '+result.error.message+'');
                } else {  }
                    if (result==true) {
                        $('#my-like-btn-'+postId).css({ 'display': 'none' });
                        $('#my-unlike-btn-'+postId).css({ 'display': 'inline-block' });
                    }
                
            });
        });
        $(".unlike").click(function() {
            var postId = $(this).attr('value');
            $('#my-like-btn-'+postId).css({ 'display': 'inline-block' });
            $('#my-unlike-btn-'+postId).css({ 'display': 'none' });
        });

        $(".add").click(function() {
            fbloginstatus();
        });

        $(".general").click(function() {
            var postId = $(this).attr('value');
            var author = $(this).attr('id');
                                                                                 
                $.ajax({                                        
                url:'insert.php',                                                              
                data:'&action=insertFavorite&user_id='+uid+'&post_id='+postId+'&author='+author+'&catalogy=general',      
                type : 'POST',
                //processData: false,                                                                     
                error:function ajaxFailure(ajax, exception) {
                      alert("Error making Ajax request:" + 
                            "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
                            "\n\nServer response text:\n" + ajax.responseText);
                      if (exception) {
                        throw exception;
                      }
                alert("Fail adding.");
                },
                success:function(){
                alert("Add into All!");
                }
            });
        });

        $(".userdefine").click(function() {
            var postId = $(this).attr('value');
            var author = $(this).attr('id');
            var cata_name = $(this).attr('data-cata');
                                                                                 
                $.ajax({                                        
                url:'insert.php',                                                              
                data:'&action=insertFavorite&user_id='+uid+'&post_id='+postId+'&author='+author+'&catalogy='+cata_name,      
                type : 'POST',
                //processData: false,                                                                     
                error:function ajaxFailure(ajax, exception) {
                      alert("Error making Ajax request:" + 
                            "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
                            "\n\nServer response text:\n" + ajax.responseText);
                      if (exception) {
                        throw exception;
                      }
                alert("Fail adding.");
                },
                success:function(){
                alert("Add into "+cata_name);
                }
            });
        });


        $("#add_list_btn").click(function() {
            var cata_name = $("#add_list_name").val();
            if(cata_name != null)
            {
                $.ajax({                                        
                url:'insert.php',                                                              
                data:'&action=addList&cata_name='+cata_name+'&owner_id='+uid,      
                type : 'POST',
                //processData: false,                                                                     
                error:function ajaxFailure(ajax, exception) {
                      alert("Error making Ajax request:" + 
                            "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
                            "\n\nServer response text:\n" + ajax.responseText);
                      if (exception) {
                        throw exception;
                      }
                alert("失敗");
                },
                success:function(){
                alert("成功");
                }
            });
            }
        });

        $("#share_app").click(function() {
            // body...
            FB.ui({
              method: 'feed',
              name: 'Funbrary, Collecing your favorite posts!',
              link: 'http://funbrary.herokuapp.com/',
              picture: 'http://funbrary.herokuapp.com/funbrary.png',
              description: 'Join us! 瀏覽當紅圖文粉絲專頁, 像是Cherng\'s, 彎彎 ,掰掰啾啾 等的最新圖文!開始收藏你最愛的圖文!!',
              message: 'Funbrary 好好玩!'
            }, 
            function(response){
                if (response && response.post_id) {
                    alert('Post was published.');
                }
                else{
                    alert('Post was not published.');
                }
            });
        });
/*
        $(".fav_tab").click(function() {
            var cata_name = $(this).attr('value');
            if(cata_name != null)
            {
                $.ajax({                                        
                url:'fav_list.php',                                                              
                data:'&cata_name='+cata_name+'&user_id='+uid,      
                type : 'POST',
                //processData: false,                                                                     
                error:function ajaxFailure(ajax, exception) {
                      alert("Error making Ajax request:" + 
                            "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
                            "\n\nServer response text:\n" + ajax.responseText);
                      if (exception) {
                        throw exception;
                      }
                alert("失敗");
                },
                success:function(){
                    $("iframe").contentWindow.location.reloaded();
                alert("成功");
                }
            });
            }
        });

        $("#FavoriteList").click(function() {
            var cata_name = $(this).attr('value');
            if(cata_name != null)
            {
                $.ajax({                                        
                url:'fav_list.php',                                                              
                data:'&cata_name='+cata_name+'&user_id='+uid,      
                type : 'POST',
                //processData: false,                                                                     
                error:function ajaxFailure(ajax, exception) {
                      alert("Error making Ajax request:" + 
                            "\n\nServer status:\n" + ajax.status + " " + ajax.statusText + 
                            "\n\nServer response text:\n" + ajax.responseText);
                      if (exception) {
                        throw exception;
                      }
                alert("失敗");
                },
                success:function(){
                alert("成功");
                }
            });
            }
        });
 
        /*$("#share_app").click(function() {
            FB.api('/'+uid+'/friends', {
              fields: 'id,first_name,last_name,name,picture,installed'
            }, function(response){
              console.log(response.data);
            });
            FB.ui({method: 'apprequests',
              message: 'My Great Request'
            }, requestCallback);
        });*/
        
}

function fblogin() {
    FB.login(function (response) {
        if (response.authResponse) {
            uid = response.authResponse.userID;
            accessToken = response.authResponse.accessToken;
            document.cookie = "user_id="+uid;
            fetch_my_profile();
        } else {
            alert('登入失敗!');
        }}, {
            scope: 'publish_actions , publish_stream , read_stream , read_friendlists'
    });
}

function fbloginstatus() {
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        uid = response.authResponse.userID;
        accessToken = response.authResponse.accessToken;
      }
      else{
        FB.login(function (response) {
            if (response.authResponse) {
                uid = response.authResponse.userID;
                accessToken = response.authResponse.accessToken;
            } else {
                alert('登入失敗!');
            }}, {
                scope: 'publish_actions , publish_stream , read_stream , read_friendlists'
        });
      }
    });
}


function fetch_my_profile() {
    /*
    Fetching profile information.
    For more detail, please vist the following url:

    (Graph API: User documentation)
    https://developers.facebook.com/docs/graph-api/reference/user/
    */
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
    FB.api('/me/picture?width=250', function(response) {
        var my_picture_url = response.data.url;
    
        $("#my-profile-picture").attr('src', my_picture_url);
    });
};

