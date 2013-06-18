<?php

/*
 * This file is part of the StaffimSplunkBundle.
 *
 * (c) Staffim
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Staffim\SplunkBundle\Monolog\Handler;

use Staffim\SplunkBundle\StaffimSplunkBundle;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;

/**
 * A splunk log handler for Symfony.
 *
 * @author Vyacheslav Salakhutdinov <megazoll@gmail.com>
 */
class SplunkHandler extends AbstractProcessingHandler
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $project;

    /**
     * @var string
     */
    private $host;

    public function __construct($token, $project, $host = 'api.splunkstorm.com', $level = Logger::DEBUG, $bubble = true)
    {
        $this->token = $token;
        $this->project = $project;
        $this->host = $host;

        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $this->send((string) $record['formatted'], $record);
    }

    /**
     * Send data to server
     *
     * @param string $message
     * @param array  $record
     * @return bool
     */
    protected function send($message, array $record)
    {
        $params = array(
            'project' => $this->project,
            'sourcetype' => 'json_auto_timestamp',
            'host' => gethostname() ?: null
        );

        $ch = curl_init();;
        curl_setopt($ch, CURLOPT_URL, 'https://'.$this->host.'/1/inputs/http?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'splunk:' . $this->token);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Staffim SplunkBundle ' . StaffimSplunkBundle::VERSION);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $statusCode == 200;
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new JsonFormatter;
    }
}
