# SmtpMail

$smtp=new SmtpClass;
$donen->process_status = $smtp->send((object)array(
        'to'=>'bulent@bulentcakir.com.tr',
        'subject'=>'Subject My Message',
        'message'=>'<div>Hello, How Are You </div><p><span>'.date("d.m.Y H:i:s").'</span></p>',
    ));
