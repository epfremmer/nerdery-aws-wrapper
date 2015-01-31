<?php

use AwsWrap\ClientService;

$params = ClientService::getParams();

return array(
    'includes' => array('_aws'),
    'class' => 'Aws\Common\Aws',
    'services' => array(
        'default_settings' => array(
            'params' => $params
        ),
        'opsworks' => array(
            'alias'   => 'OpsWorks',
            'extends' => 'default_settings',
            'class'   => 'AwsWrap\Clients\OpsWorks'
        ),
        'iam' => array(
            'alias'   => 'Iam',
            'extends' => 'default_settings',
            'class'   => 'AwsWrap\Clients\Iam'
        ),
        'ec2' => array(
            'alias'   => 'Ec2',
            'extends' => 'default_settings',
            'class'   => 'AwsWrap\Clients\Ec2'
        ),
        // additional services
    )
);