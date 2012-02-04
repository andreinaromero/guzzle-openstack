<?php

namespace Guzzle\Openstack\Tests\Identity\Command;

/**
 * List Tenants command unit test
 */
class ListUsersTest extends \Guzzle\Tests\GuzzleTestCase
{

    public function setUp()
    {
        $authclient = \Guzzle\Openstack\Authentication\AuthenticationClient::factory(array('username' => 'username', 'password' => 'password', 'ip' => '192.168.4.100', 'port'=>'35357'));
        $this->client = \Guzzle\Openstack\Identity\IdentityClient::factory(array('identity' => $authclient, 'username'=>'username', 'password'=>'password'));        
        $this->setMockResponse($this->client->getIdentity(), 'authentication/AuthenticateAuthorized');                
    }
    
    public function testListUsers()
    {
        $authclient = \Guzzle\Openstack\Authentication\AuthenticationClient::factory(array('username' => 'username', 'password' => 'password', 'ip' => '192.168.4.100'));
        $this->client = \Guzzle\Openstack\Identity\IdentityClient::factory(array('identity' => $authclient, 'username'=>'username', 'password'=>'password'));
        $this->setMockResponse($this->client->getIdentity(), array('authentication/AuthenticateAuthorized'));  
        $this->setMockResponse($this->client, 'identity/ListUsers');        
        $command = $this->client->getCommand('ListUsers');
        $command->prepare();
      
        //Check method and resource
        $this->assertEquals('http://192.168.4.100:5000/v2.0/users', $command->getRequest()->getUrl());
        $this->assertEquals('GET', $command->getRequest()->getMethod());
                
        //Check for authentication header
        $this->assertTrue($command->getRequest()->hasHeader('X-Auth-Token'));
                        
        $this->client->execute($command);
      
        $result = $command->getResult();
        $this->assertTrue(is_array($result));
        
        $this->assertTrue(array_key_exists('users', $result));
        
    }
    
    public function testMarkerParameter() {
        $command = $this->client->getCommand('ListUsers', array("marker" => "1979"));
        $this->setMockResponse($this->client->getIdentity(), array('authentication/AuthenticateAuthorized'));  
        $this->setMockResponse($this->client, 'identity/ListUsers'); 
        $command->prepare();
        $this->assertEquals('http://192.168.4.100:5000/v2.0/users/marker=1979', $command->getRequest()->getUrl());
    }
    
    
}