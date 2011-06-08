Instructions :

This is beta for testing therefore backup everything you're going to change!
Post any bugs in the forum thread.
Upload from zip : eform.inc.php to assets/snippets/eform/
Copy from zip : eform.snippet.php paste to existing snippet eForm
New SMTP snippet parameters have the following default values :
'smtp'      => isset($smtp)? $smtp:false,
'smtp_host' => isset($smtp_host)? $smtp_host:'',
'smtp_port' => isset($smtp_port)? $smtp_port:25,
'smtp_auth' => isset($smtp_auth)? $smtp_auth:'true',
'smtp_user' => isset($smtp_user)? $smtp_user:$modx->config['emailsender'],
'smtp_pass' => isset($smtp_pass)? $smtp_pass:'',

Example minimal snippet call ...

[!eForm?… &smtp=`true` &smtp_pass=`password` &smtp_host=`smtp.your-domian.co.uk`  …`!]

Optionally you can vary the smtp_port (integer), smtp_auth (true or false), smtp_user (if it's not the same as the MODx email sender)

If you don't set &smtp=`true` then the normal mail(); function will be used.
You can now use a config file, an example one is in the configs/ folder.
Good Luck!
