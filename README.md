# MessageBirdPHP
This is a simple PHP application to handle MessageBird Client API to send SMS

Setup Procedure:

1) Point your web url to public/index.php
2) Go to src/Config/Config.php and add your API TOKEN KEY
3) Run Composer update
4) You're done

How to Send an sms?

 API that accepts SMS messages submitted via a POST request containing a
JSON object as request body.

Example: {"recipient":31612345678,"originator":"MessageBird","message":"This
is a test message."}