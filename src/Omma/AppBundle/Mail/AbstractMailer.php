<?php
namespace Omma\AppBundle\Mail;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Abstract class for rendering twig template to mails and send them to users
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class AbstractMailer
{

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $fromEmail;

    public function __construct(
        \Swift_Mailer $mailer,
        UrlGeneratorInterface $urlGenerator,
        \Twig_Environment $twig,
        $fromEmail
    ) {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @param string $templateName #Template
     * @param array  $context
     * @param User   $user
     */
    public function sendMessageToUser($templateName, $context, User $user)
    {
        $context['user'] = $user;
        if (null !== $user->getFirstname()) {
            $context['username'] = $user->getFirstname() . " " . $user->getLastname();
        } else {
            $context['username'] = $user->getUsername();
        }
        $this->sendMessage($templateName, $context, $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $toEmail
     */
    public function sendMessage($templateName, $context, $toEmail)
    {
        /** @var \Twig_Template $template */
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message
                ->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }

    /**
     * @return UrlGeneratorInterface
     */
    public function getUrlGenerator()
    {
        return $this->urlGenerator;
    }
}
