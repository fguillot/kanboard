<?php

namespace Kanboard\Auth;

use Kanboard\Core\Base;
use Kanboard\Core\Security\PreAuthenticationProviderInterface;
use Kanboard\Core\Security\SessionCheckProviderInterface;
use Kanboard\User\ReverseProxyUserProvider;

/**
 * Reverse-Proxy Authentication Provider
 *
 * @package  Kanboard\Auth
 * @author   Frederic Guillot
 */
class ReverseProxyAuth extends Base implements PreAuthenticationProviderInterface, SessionCheckProviderInterface
{
    /**
     * User properties
     *
     * @access protected
     * @var \Kanboard\User\ReverseProxyUserProvider
     */
    protected $userInfo = null;

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'ReverseProxy';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        $username = $this->request->getRemoteUser();
        $name     = $this->request->getRemoteName();
        $email    = $this->request->getRemoteEmail();

        if (! empty($username)) {
            $userProfile = $this->userCacheDecorator->getByUsername($username);
            $this->userInfo = new ReverseProxyUserProvider($username, $email, $name, $userProfile ?: array());
            return true;
        }

        return false;
    }

    /**
     * Check if the user session is valid
     *
     * @access public
     * @return boolean
     */
    public function isValidSession()
    {
        return $this->request->getRemoteUser() === $this->userSession->getUsername();
    }

    /**
     * Get user object
     *
     * @access public
     * @return ReverseProxyUserProvider
     */
    public function getUser()
    {
        return $this->userInfo;
    }
}
