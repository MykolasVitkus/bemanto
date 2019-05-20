<?php

namespace App\Service;

class EmailManager
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendEmail(string $messageTitle, string $sendToEmail, string $template, string $textType, array $variables = []): \Swift_Message
    {
        $messageToSend = (new \Swift_Message($messageTitle))
            ->setFrom('bemantelio@gmail.com')
            ->setTo($sendToEmail)
            ->setBody(
                $this->templating->render($template, $variables),
                /*$this->templating->render(
                    $template,
                    [
                        $variables 
                    ]
                ),*/
                $textType
            );

        return $messageToSend;
    }
}