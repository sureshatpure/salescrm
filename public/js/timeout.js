// Set timeout variables.
//var timoutWarning = 840000; // Display warning in 14 Mins.
//var timoutNow = 900000; // Timeout in 15 mins.

var timoutWarning = 30000; // value in millisecs, Display warning in 14 Mins.
var timoutNow = 60000; // Timeout in 2 mins.
var logoutUrl = 'http://10.1.2.40/salescrm/admin/logout'; // URL to logout page.

var warningTimer;
var timeoutTimer;

// Start timers.
function StartTimers() {
    warningTimer = setTimeout("IdleWarning()", timoutWarning);
    timeoutTimer = setTimeout("IdleTimeout()", timoutNow);
}

// Reset timers.
function ResetTimers() {
    clearTimeout(warningTimer);
    clearTimeout(timeoutTimer);
    StartTimers();
   // $("#timeout").dialog('close');
}

// Show idle timeout warning dialog.
function IdleWarning() {
    $("#timeout").dialog({
        modal: true
    });
}

// Logout the user.
function IdleTimeout() {
    window.location = logoutUrl;
}

