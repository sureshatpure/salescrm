/**
 * This work is licensed under the Creative Commons Attribution-Share Alike 3.0
 * United States License. To view a copy of this license,
 * visit http://creativecommons.org/licenses/by-sa/3.0/us/ or send a letter
 * to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA
 *
 * Configurable idle (no activity) timer and logout redirect for jQuery.
 * Works cross-browser with multiple windows and tabs within the same domain.
 *
 * Dependencies: JQuery v1.7+, JQuery UI, store.js from https://github.com/marcuswestin/store.js - v1.3.4+
 *
 * Commented and console logged for debugging with Firefox & Firebug or similar
 * v1.0.3
 */

(function($) {

  $.fn.idleTimeout = function(options) {
     //##############################
    //## Configuration Variables
    //##############################
    var defaults = {
      idleTimeLimit: 600000,     // 'No activity' time limit in milliseconds. 10 mins , 1 min = 60000 milliseconds
      dialogDisplayLimit: 60000, // Time to display the warning dialog before redirect (and optional callback) in milliseconds. 60000 = 1 Minute
      redirectUrl: base_url+'admin/logout/timeout', // redirect to this url
      // optional custom callback to perform before redirect
      customCallback: false,         // set to false for no customCallback
      // define optional custom js function
      // customCallback: function() {
          // User to be logged out, perform custom action
      // },
      // configure which activity events to detect
      // activityEvents: 'click keypress scroll wheel mousewheel mousemove', // separate each event with a space
      activityEvents: 'click keypress scroll wheel mousewheel mousemove', // customize events for testing - remove mousemove
      //dialog box configuration
      dialogTitle: 'Session Expiration Warning',
      dialogText: 'Because you have been inactive, your session is about to expire.',

      // server-side session keep-alive timer & url
      sessionKeepAliveTimer: 600000, // Ping the server at this interval in milliseconds. 60000 = 1 Minute
      // sessionKeepAliveTimer: false, // Set to false to disable pings.
      sessionKeepAliveUrl: '/', // url to ping
    };

    //##############################
    //## Private Variables
    //##############################
    var opts = $.extend(defaults, options);
    var idleTimer, dialogTimer, idleTimerLastActivity;
    var checkHeartbeat = 2000; // frequency to check for timeouts

    //##############################
    //## Private Functions
    //##############################

    // open warning dialog function
    var openWarningDialog = function() {
      var dialogContent = "<div id='idletimer_warning_dialog'><p>" + opts.dialogText + "</p></div>";

      var warningDialog = $(dialogContent).dialog({
        buttons: {
          "Stay Logged In": function() {
            destroyWarningDialog();
            stopDialogTimer();
            startIdleTimer();
          },
          "Log Out Now": function() {
            logoutUser();
          }
        },
        closeOnEscape: false,
        modal: true,
        title: opts.dialogTitle
      });

      // hide the dialog's upper right corner "x" close button
      $('.ui-dialog-titlebar-close').css('display', 'none');
    };

    // is dialog open function
    var isDialogOpen = function() {
      var dialogOpen = $('#idletimer_warning_dialog').dialog('isOpen');

      if (dialogOpen === true) {
        return true;
      } else {
        return false;
      }
    };

    // destroy warning dialog function
    var destroyWarningDialog = function() {
      $(".ui-dialog-content").dialog('destroy').remove();
    };

    // check idle timeout function
    var checkIdleTimeout = function() {
      var timeNow = $.now();
      var timeIdleTimeout = (store.get('idleTimerLastActivity') + opts.idleTimeLimit);

      if (timeNow > timeIdleTimeout) {
        console.log('timeNow: ' + timeNow + ' > idle ' + timeIdleTimeout);
        if (isDialogOpen() !== true) {
          openWarningDialog();
          startDialogTimer();
        }
      } else if (store.get('idleTimerLoggedOut') === true) { //a 'manual' user logout?
        logoutUser();
      } else {
         console.log('idle not yet timed out');
        if (isDialogOpen() === true) {
          destroyWarningDialog();
          stopDialogTimer();
        }
      }
    };

    // start idle timer function
    var startIdleTimer = function() {
      stopIdleTimer();
      idleTimerLastActivity = $.now();
      store.set('idleTimerLastActivity', idleTimerLastActivity);
      idleTimer = setInterval(checkIdleTimeout, checkHeartbeat);
    };

    // stop idle timer function
    var stopIdleTimer = function() {
      clearInterval(idleTimer);
    };

    // check dialog timeout function
    var checkDialogTimeout = function() {
      var timeNow = $.now();
      var timeDialogTimeout = (store.get('idleTimerLastActivity') + opts.idleTimeLimit + opts.dialogDisplayLimit);

      if ((timeNow > timeDialogTimeout) || (store.get('idleTimerLoggedOut') === true)) {
      //  console.log('timeNow: ' + timeNow + ' > dialog' + timeDialogTimeout);
        logoutUser();
      } else {
      }
    };

    // start dialog timer function
    var startDialogTimer = function() {
      dialogTimer = setInterval(checkDialogTimeout, checkHeartbeat);
    };

    // stop dialog timer function
    var stopDialogTimer = function() {
      clearInterval(dialogTimer);
    };

    // perform logout procedure function
    var logoutUser = function() {
      store.set('idleTimerLoggedOut', true);

      if (opts.customCallback) {
        console.log('custom callback');
        opts.customCallback();
      }

      if (opts.redirectUrl) {
        window.location.href = opts.redirectUrl;
      }
    };

    // activity detector function
    // if warning dialog is NOT open, restarts idle timer
    var activityDetector = function() {

      $('body').on(opts.activityEvents, function() {

        if (isDialogOpen() !== true) {
          startIdleTimer();
        } else {
        }

      });
    };

    // if keep-alive sessionKeepAliveTimer value is not false,
    // ping the server at regular intervals to prevent a server idle timeout
    var keepSessionAlive = function() {

      if (opts.sessionKeepAliveTimer) {
        var keepSession = function() {
          // if this is the most recently active window or tab
          if (idleTimerLastActivity === store.get('idleTimerLastActivity')) {
            $.get(opts.sessionKeepAliveUrl);
          }
        };

        setInterval(keepSession, opts.sessionKeepAliveTimer);
      }
    };

    //###############################
    // Build & Return the instance of the item as a plugin
    // This is basically your construct.
    //###############################
    return this.each(function() {
      if (store.enabled) {
        // initial values
        idleTimerLastActivity = $.now();
        store.set('idleTimerLastActivity', idleTimerLastActivity);
        store.set('idleTimerLoggedOut', false);
      } else {
        alert('Dependent file missing. Please see: https://github.com/marcuswestin/store.js');
      }
      activityDetector();
      keepSessionAlive();
      startIdleTimer();
    });
  }
})(jQuery);
