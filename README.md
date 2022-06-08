# gui-gmailAPI001
example based in:
https://developers.google.com/gmail/api/quickstart/php

- create proyect
- enable GmailAPI
- add quickstart.php to authorized URIs in the google developer page proyect configuration. scopes, these are the permissions the app has to access the account through the api, GMAIL_READONLY is the default setting in the google provided example, we change it to MAIL_GOOGLE_COM so we can have total access to functionalities.
- install composer and google dependency, composer require google/apiclient:^2.0
- run in terminal the file, example: ubuntu@ubuntu:~$ php quickstart.php
- copy the url provided, paste it in browser and go, copy the searchbar result, in there you wil notice 'code=..', thas the code the php is asking for
- token.json is created in this working php script directory
- some result from the api call is displayed in terminal, in this example case the labels in the mail account
- add 002.php to authorized URIs in the google developer page proyect configuration
- from now on you can just use sendGmail() in your scripts, in our example we provide the 001.html+002.php to call it from browser.
