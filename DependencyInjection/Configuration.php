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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Vyacheslav Salakhutdinov <megazoll@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('staffim_splunk');

        $rootNode->children()
                ->scalarNode('token')->isRequired()->end()
                ->scalarNode('project')->isRequired()->end()
                ->scalarNode('host')->defaultValue('api.splunkstorm.com')->end()
                ->scalarNode('level')->defaultValue(constant('Monolog\Logger::DEBUG'))->end()
                ->booleanNode('bubble')->defaultValue(true)->end()
            ->end();

        return $treeBuilder;
    }
}
