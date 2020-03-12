<?php
class SmtpClass
{
    private $user = 'YourAccount';
    private $pass = 'YourPass';
    private $host = 'YourMailHost'; 
    private $port = 25;
    private $from =  '';
    public $params;
    function send($params=null)
    {
        if($params==null) $params=(object)array();
        if($params->to && $params->subject && $params->message ) 
        {
            return $this->smtp_mail($params->to, $params->subject, $params->message,'');
        }
        return false;
    }
    
    private function smtp_mail($to, $subject, $message, $headers = '')
    {
        
        $recipients = explode(',', $to);
        
        
        if (!($smtp = fsockopen($this->host, $this->port, $errno, $errstr, 15)))
        {
            return false;
        }
        echo $this->get($smtp); // should return a 220 if you want to check
        
        
        $cmd = "EHLO ".$_SERVER['HTTP_HOST'];
        $this->put($smtp,$cmd);
        echo $this->get($smtp);
        
        $cmd = "AUTH LOGIN";
        $this->put($smtp,$cmd);
        echo $this->get($smtp);
        
        $cmd = base64_encode($this->user);
        $this->put($smtp,$cmd);
        echo $this->get($smtp);
        
        $cmd = base64_encode($this->pass);
        $this->put($smtp,$cmd);
        echo $this->get($smtp);
        
        $cmd = 'MAIL FROM: <'.$this->user.'>';
        $this->put($smtp,$cmd);
        echo $this->get($smtp);
        
        
        foreach ($recipients as $email)
        {
            $cmd = 'RCPT TO: <'.$email.'>';
            $this->put($smtp,$cmd);
            echo $this->get($smtp);
            
        }
        
        $cmd = 'DATA';
        $this->put($smtp,$cmd);
        echo $this->get($smtp);
        
		if(!$this->from) $this->from=$this->user;
        $content = "From: {$this->from}\r\n";
        $content .= "To: <".implode(">, <", $recipients).">\r\n";
        $content .= "Subject: {$subject}\r\n";
        $content .= "MIME-Version: 1.0\r\n";
        $content .= "Content-Type: text/html; charset=UTF-8; format=flowed\r\n";
        $content .= "Content-Transfer-Encoding: 7bit\r\n";
        $content .= "<html><body>{$message}</body></html> ";
        
        $cmd = $content;
        $this->put($smtp,$cmd);
        $this->get($smtp);
        
        
        $cmd = '.';
        $this->put($smtp,$cmd);
        $this->get($smtp);
        
        $cmd = 'QUIT';
        $this->put($smtp,$cmd);
        $this->get($smtp);
        
        
         
        return true;
    }
    
    private function get($socket,$length=1024)
    {
        $send = '';
        $sr = fgets($socket,$length);
        while( $sr ){
            $send .= $sr;
            if( $sr[3] != '-' ){ break; }
            $sr = fgets($socket,$length);
        }
          $send;
        return $send;
    }
    
    private function put($socket,$cmd,$length=1024)
    {
        fputs($socket,$cmd."\r\n",$length);
    }
}
?>