<script type="text/javascript" src="<?= base_url() ?>public/js/store.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/jquery-idleTimeoutlive.js"></script> 

   
<script type="text/javascript" charset="utf-8">
       base_url = "<?= base_url() ?>";
            alert("base_url in incldue "+base_url);
    init()
    function init() {
        if (!store.enabled) {
            alert('Local storage is not supported by your browser. Please disable "Private Mode", or upgrade to a modern browser.')
            return
             }
            var user = store.get('user')
        }
          $(document).ready(function(){
           $(document).idleTimeout({
                    idleTimeLimit: 600000,     // 'No activity' time limit in milliseconds. 10 mins , 1 min = 60000 milliseconds
                    dialogDisplayLimit: 60000, // Time to display the warning dialog before redirect (and optional callback) in milliseconds. 60000 = 1 Minute
                    redirectUrl: base_url+'admin/logout/timeout', // redirect to this url
                    // optional custom callback to perform before redirect
                    customCallback: false,       // set to false for no customCallback
                    // customCallback:    function() {// define optional custom js function perform custom action before logout },
                    
                    // configure which activity events to detect
                    activityEvents: 'click keypress scroll wheel mousewheel mousemove', // separate each event with a space

                    //dialog box configuration
                    dialogTitle: 'Session Expiration Warning',
                    dialogText: 'Because you have been inactive, your session is about to expire.',

                    // server-side session keep-alive timer & url
                    sessionKeepAliveTimer: 60000, // Ping the server at this interval in milliseconds. 60000 = 1 Minute
                    // sessionKeepAliveTimer: false, // Set to false to disable pings.
                    sessionKeepAliveUrl: '/',  // url to ping
                    multipleTabs: true,
                });

          });
    
        </script>
