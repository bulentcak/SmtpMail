# SmtpMail
 ```php
 require_once 'smtp.class.php';
 
 $smtp=new SmtpClass;
 $response = $smtp->send((object)array(
         'to'=>'to mail address',
         'subject'=>'Subject My Message',
         'message'=>'<div>Hello, How Are You </div><p><span>'
                    .date("d.m.Y H:i:s")
                    .'</span></p>',
     ));
```
