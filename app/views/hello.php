<!DOCTYPE html>
<html>
<head>
  <title>Pusher Test</title>
</head>
<body>
  <h1 class="message"></h1>
  <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
  <script src="http://js.pusher.com/2.1/pusher.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  $(function () {
    // Enable pusher logging - don't include this in production
    Pusher.log = function(message) {
      if (window.console && window.console.log) {
        window.console.log(message);
      }
    };

    var pusher = new Pusher('d88fb18122533c6b1e99');
    var channel = pusher.subscribe('test_channel');
    channel.bind('test_event', function(data) {
      $('.message').text(data.message);
    });
  });
  </script>
</body>
</html>