<?php

namespace Core\Mailer;

use Core\Config;
use Core\DevTools\VarDumper;
use Core\Parameters;

class Mailer
{
    private static ?Mailer $instance = null;

    private array $to = [];
    private array $cc = [];
    private array $bcc = [];
    private string $from = '';
    private string $reply_to = '';
    private string $subject = '<No Subject>';
    private string $message = '<No Message Body>';


    public function __construct()
    {
    }

    public function send(): array
    {
        if(!$this->from || (empty($this->to) && empty($this->cc) && empty($this->bcc))) {
            throw new \Exception("Empty fields");
        }

        // using basic sendmail for now
        $headers = [
            'From: ' . $this->from,
            'X-Mailer: ColdPhusion PHP/' . PHP_VERSION
        ];
        if($this->reply_to) {
            $headers['Reply-To'] = $this->reply_to;
        }
        $headers = implode(PHP_EOL, $headers);

        $errors = [];
        foreach($this->to as $recipient) {
            $result = mail($recipient, $this->subject, $this->message, $headers);
            if(!$result) {
                $errors[] = [
                    'recipient' => $recipient,
                    'error' => error_get_last()['message']
                ];
            }
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @param array $to
     * @return Mailer
     */
    public function setTo(array $to): Mailer
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return array
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @param array $cc
     * @return Mailer
     */
    public function setCc(array $cc): Mailer
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @return array
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * @param array $bcc
     * @return Mailer
     */
    public function setBcc(array $bcc): Mailer
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return Mailer
     */
    public function setFrom(string $from): Mailer
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getReplyTo(): string
    {
        return $this->reply_to;
    }

    /**
     * @param string $reply_to
     * @return Mailer
     */
    public function setReplyTo(string $reply_to): Mailer
    {
        $this->reply_to = $reply_to;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return Mailer
     */
    public function setSubject(string $subject): Mailer
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Mailer
     */
    public function setMessage(string $message): Mailer
    {
        $this->message = $message;
        return $this;
    }

}
