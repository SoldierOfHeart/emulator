<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel implements EventSubscriberInterface
{
    use MicroKernelTrait;

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'handleRequest'];
    }

    public function handleRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ('json' === $request->getContentType() && $request->getContent()) {
            $data = \json_decode($request->getContent(), true);

            if (\is_array($data)) {
                $request->request->replace($data);
            }
        }
    }

    public function registerBundles(): array
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
        ];

        if ($this->getEnvironment() == 'dev') {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    protected function configureContainer(ContainerConfigurator $c): void
    {
        $c->import(__DIR__.'/../config/framework.yaml');

        // register all classes in /src/ as service
        $c->services()
            ->load('App\\', __DIR__.'/*')
            ->autowire()
            ->autoconfigure()
        ;

        // configure WebProfilerBundle only if the bundle is enabled
        if (isset($this->bundles['WebProfilerBundle'])) {
            $c->extension('web_profiler', [
                'toolbar' => true,
                'intercept_redirects' => false,
            ]);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        // import the WebProfilerRoutes, only if the bundle is enabled
        if (isset($this->bundles['WebProfilerBundle'])) {
            $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml')->prefix('/_wdt');
            $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml')->prefix('/_profiler');
        }

        // load the annotation routes
        $routes->import(__DIR__.'/Controllers/', 'annotation');
    }

    // optional, to use the standard Symfony cache directory
    public function getCacheDir(): string
    {
        return __DIR__.'/../var/cache/'.$this->getEnvironment();
    }

    // optional, to use the standard Symfony logs directory
    public function getLogDir(): string
    {
        return __DIR__.'/../var/log';
    }
}