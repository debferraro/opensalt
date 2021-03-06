<?php

namespace Salt\SiteBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @DI\Service()
 */
class VaryOriginListener
{
    /**
     * @var string
     * @DI\Inject("%kernel.root_dir%")
     */
    public $rootDir;

    /**
     * @param FilterResponseEvent $event
     *
     * @DI\Observe(KernelEvents::RESPONSE, priority=-10)
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $response = $event->getResponse();
        if (!$response->headers->has('Access-Control-Allow-Origin')) {
            return;
        }

        $origin = $response->headers->get('Access-Control-Allow-Origin');
        if ('*' !== $origin) {
            $response->headers->set('Vary', 'Origin');
        }
    }
}
