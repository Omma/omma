<?php
namespace Omma\AppBundle\EventListener\Request;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Sets the current browser locale as default.
 * Ignores manual locale changes
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LocaleListener implements EventSubscriberInterface
{

    /**
     *
     * @var array
     */
    protected $languages;

    public function __construct(array $languages)
    {
        $this->languages = $languages;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->hasSession()) {
            return;
        }

        $session = $request->getSession();

        if (null !== ($locale = $session->get("_locale"))) {
            if (!in_array($locale, $this->languages)) {
                $locale = reset($this->languages);
            }
            $request->setLocale($locale);

            return;
        }

        $locale = $request->getPreferredLanguage($this->languages);
        $request->setLocale($locale);
        $session->set("_locale", $locale);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array("onKernelRequest", 17),
        );
    }
}
