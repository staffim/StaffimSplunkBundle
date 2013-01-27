<?php

/*
 * This file is part of the StaffimSplunkBundle.
 *
 * (c) Staffim
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Staffim\SplunkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Vyacheslav Salakhutdinov <megazoll@gmail.com>
 */
class StaffimSplunkExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Set the level to the correct integer value provided by Monoglog
        $config['level'] = is_int($config['level']) ? $config['level'] : constant('Monolog\Logger::'.strtoupper($config['level']));

        $container->setParameter('staffim_splunk.splunk.token', $config['token']);
        $container->setParameter('staffim_splunk.splunk.project', $config['project']);
        $container->setParameter('staffim_splunk.splunk.host', $config['host']);
        $container->setParameter('staffim_splunk.splunk.level', $config['level']);
        $container->setParameter('staffim_splunk.splunk.bubble', $config['bubble']);
    }
}
