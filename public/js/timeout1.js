    window.onload = reset_main_timer;
    document.onmousemove = reset_main_timer;
    document.onkeypress = reset_main_timer;
    
    // create main_timer and sub_timer variable with 0 value, we will assign them setInterval object
    var main_timer = 0;
    var sub_timer = 0;

    // this will ensure that timer will start only when user is loged in
    var user_loged_in = $("#user_loged_in").val()

   // within dilog_set_interval function we have created object of setInterval and assigned it to main_timer.
   // within this we have again created an object of setInterval and assigned it to sub_timer. for the main_timer
   // value is set to 15000000 i,e 25 minute.note that if subtimer repeat itself after 5 minute we set user_activity
   // flag to inactive
    function dialog_set_interval(){
        main_timer = setInterval(function(){
            if(user_loged_in == "true"){
                $("#inactivity_warning").modal("show");
                sub_timer = setInterval(function()
                {                   
                    $("#user_activity").val("inactive")
                     
                },30000);
            }
        },60000);
    }
   // maintimer is set to 0 by calling the clearInterval function. note that clearInterval function takes
   // setInterval object as argument, which it then set to 0
    function reset_main_timer(){
        clearInterval(main_timer);
        dialog_set_interval();
    }

    // logout user if user_activity flag set to inactive, when he click ok on popup. whenuser click O.K
    // on the warning the subtimer is reset to 0
    $(".inactivity_ok").click(function(){
        clearInterval(sub_timer);
        if($("#user_activity").val() == "inactive"){
            window.location = 'http://10.1.2.40/salescrm/admin/logout' // if your application not manage session expire 
                                             //automatically. clear cookies and session her
        }
    });