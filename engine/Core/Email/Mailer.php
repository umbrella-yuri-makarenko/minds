<?php
/**
 * Email mailer
 */
namespace Minds\Core\Email;

use Minds\Core;
use Minds\Entities;

class Mailer{

  private $mailer;
  private $stats;

  public function __construct(){
    $this->mailer = new PHPMailer();
    $this->mailer->isSMTP();
    $this->mailer->SMTPKeepAlive = true;
    $this->setup();
    $this->stats = array(
      'sent' => 0,
      'failed' => 0
    );
  }

  private function setup(){
    $this->mailer->Host = \elgg_get_plugin_setting('phpmailer_host', 'phpmailer');
    $this->mailer->Auth = \elgg_get_plugin_setting('phpmailer_smtp_auth', 'phpmailer');
    $this->mailer->SMTPAuth = true;
    $this->mailer->Username = \elgg_get_plugin_setting('phpmailer_username', 'phpmailer');
    $this->mailer->Password = \elgg_get_plugin_setting('phpmailer_password', 'phpmailer');
    $this->mailer->SMTPSecure = "ssl";
    $this->mailer->Port = \elgg_get_plugin_setting('ep_phpmailer_port', 'phpmailer');
  }

  /**
   * Send an email
   * @param Message $message
   * @return $this
   */
  public function send($message){
    $this->mailer->ClearAllRecipients();
    $this->mailer->ClearAttachments();

    $mailer->From = $message->from['email'];
    $mailer->FromName = $message->from['name'];

    foreach($message->to as $to)
      $mailer->AddAddress($to['email'], $to['name']);

    $mailer->Subject = $message->subject;

    $mailer->IsHTML(true);
    $mailer->Body = $message->buildHtml();

    if($mailer->Send()){
      $this->stats['sent']++;
    } else {
      $this->stats['failed']--;
    }

    return $this;
  }

  public function __destruct(){
    $this->mailer->SmtpClose();
  }

}
