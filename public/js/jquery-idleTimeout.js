//######
//## This work is licensed under the Creative Commons Attribution-Share Alike 3.0 
//## United States License. To view a copy of this license, 
//## visit http://creativecommons.org/licenses/by-sa/3.0/us/ or send a letter 
//## to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
//######

(function($){
 $.fn.idleTimeout = function(options) {
    var defaults = {
      inactivity: 30000, //20 Minutes
      noconfirm: 10000, //10 Seconds
      sessionAlive: 30000, //10 Minutes
      redirect_url: 'http://10.1.2.40/salescrm/admin/logout',
      click_reset: true,
      alive_url: '',
      logout_url: 'http://10.1.2.40/salescrm/admin/logout',
      showDialog: true,
	  dialogTitle: 'Auto Logout',
	  dialogText: 'You are about to be signed out due to inactivity.',
	  dialogButton: 'Stay Logged In'
    }
    
    //##############################
    //## Private Variables
    //##############################
    var opts = $.extend(defaults, options);
    var liveTimeout, confTimeout, sessionTimeout;
    var modal = "<div id='modal_pop'><p>"+opts.dialogText+"</p></div>";

    //##############################
    //## Private Functions
    //##############################
    var start_liveTimeout = function()
    {
      clearTimeout(liveTimeout);
      clearTimeout(confTimeout);
      liveTimeout = setTimeout(logout, opts.inactivity);
      
      if(opts.sessionAlive)
      {
        clearTimeout(sessionTimeout);
        sessionTimeout = setTimeout(keep_session, opts.sessionAlive);
      }
    }
    
    var logout = function()
    {
      var my_dialog;
	    var buttonsOpts = {};
	  
      confTimeout = setTimeout(redirect, opts.noconfirm);
	  
	  buttonsOpts[opts.dialogButton] = function(){
		my_dialog.dialog('close');
		stay_logged_in();
	  }
	  
      if(opts.showDialog)
      {
        my_dialog = $(modal).dialog({
          buttons: buttonsOpts,
          modal: true,
          title: opts.dialogTitle
        });
      }
    }
    
    var redirect = function()
    {
      if(opts.logout_url)
      {
        $.get(opts.logout_url);
      }
      window.location.href = opts.redirect_url;
    }
    
    var stay_logged_in = function(el)
    {
      start_liveTimeout();
      if ( opts.alive_url ) 
      {       
      // RH Mod : 08/07/2013
       get_alive_url();
      }
        
    }
    
    var keep_session = function() {
      if ( opts.alive_url ) {         
          // RH Mod : 08/07/2013
          get_alive_url();
      }

      // Loop
      clearTimeout(sessionTimeout);
      sessionTimeout = setTimeout(keep_session, opts.sessionAlive);
    } 

    var get_alive_url = function() {
    // We will update SessionTimeout in a cookie and check it for changes !!
    // You will need to create a getCookie() function for this. You likely have one in your core js libraries
    var new_inactivity = parseFloat( getCookie( "SessionTimeout" ) );

    // Only update if the value has changed !!
    if ( new_inactivity ) {
        if ( new_inactivity != opts.inactivity ) {
            // Update our inactivity value (milliseconds) !!
            opts.inactivity = new_inactivity;

            //alert("New Session Timeout: " + new_inactivity);

            // Reset our logout timeout - There's no point in updating opts.inactivity without updating the timeout call which uses it !!
            clearTimeout(liveTimeout);
            liveTimeout = setTimeout(logout, opts.inactivity);              
        }
    }

    /*
    THIS DOESN'T WORK IN IIS - Do Not Send ServerXMLHTTP or WinHTTP Requests to the Same Server. 
    If you monitor http requests in Firebug or Fiddler etc you will see that they hang waiting for an available thread or worker process to complete
    http://support.microsoft.com/kb/316451

    $.get(opts.alive_url, function(data) {
      var new_inactivity = parseFloat(data);

      // Only update if the value has changed !!
      if ( new_inactivity ) {  
            if ( new_inactivity != opts.inactivity ) {
                // Update our inactivity value (milliseconds) !!
                opts.inactivity = new_inactivity;

                // Reset our logout timeout - There's no point in updating opts.inactivity without updating the timeout call which uses it !!
                clearTimeout(liveTimeout);
                liveTimeout = setTimeout(logout, opts.inactivity);
            }
      }
    });
    */  
}
    
    //###############################
    //Build & Return the instance of the item as a plugin
    // This is basically your construct.
    //###############################
    return this.each(function() {
      obj = $(this);

      if ( opts.click_reset ) {

          $(document).bind('click', start_liveTimeout);
      }

      start_liveTimeout();            
    });
    
 };
})(jQuery);

