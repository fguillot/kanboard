<?php

namespace Kanboard\User;

use Kanboard\Core\User\UserProviderInterface;
use Kanboard\Core\Security\Role;

/**
 * Reverse Proxy User Provider
 *
 * @package  user
 * @author   Frederic Guillot
 */
class ReverseProxyUserProvider implements UserProviderInterface
{
    /**
     * Username
     *
     * @access protected
     * @var string
     */
    protected $username = '';

    /**
     * Email
     *
     * @access protected
     * @var string
     */
    protected $email = '';

    /**
     * User profile if the user already exists
     *
     * @access protected
     * @var array
     */
    private $userProfile = array();

    /**
     * Constructor
     *
     * @access public
     * @param  string $username
     * @param  string $email
     * @param  string  $name
     */
    public function __construct($username, $email, $name, array $userProfile = array())
    {
        $this->username = $username;
        $this->email = $email;
        $this->name = $name;
        $this->userProfile = $userProfile;
    }

    /**
     * Return true to allow automatic user creation
     *
     * @access public
     * @return boolean
     */
    public function isUserCreationAllowed()
    {
        return true;
    }

    /**
     * Get internal id
     *
     * @access public
     * @return string
     */
    public function getInternalId()
    {
        return '';
    }

    /**
     * Get external id column name
     *
     * @access public
     * @return string
     */
    public function getExternalIdColumn()
    {
        return 'username';
    }

    /**
     * Get external id
     *
     * @access public
     * @return string
     */
    public function getExternalId()
    {
        return $this->username;
    }

    /**
     * Get user role
     *
     * @access public
     * @return string
     */
    public function getRole()
    {
        if (REVERSE_PROXY_DEFAULT_ADMIN === $this->username) {
            return Role::APP_ADMIN;
        }

        if (isset($this->userProfile['role'])) {
            return $this->userProfile['role'];
        }

        return Role::APP_USER;
    }

    /**
     * Get username
     *
     * @access public
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get full name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        if (! REVERSE_PROXY_NAME_HEADER ){
            return '';
        }else{
            return $this->name;
        }
    }

    /**
     * Get user email
     *
     * @access public
     * @return string
     */
    public function getEmail()
    {
        if (! REVERSE_PROXY_EMAIL_HEADER ){
            if (REVERSE_PROXY_DEFAULT_DOMAIN !== '' && $this->email === '') {
                return $this->username.'@'.REVERSE_PROXY_DEFAULT_DOMAIN;
            }
            return REVERSE_PROXY_DEFAULT_DOMAIN !== '' ? $this->username.'@'.REVERSE_PROXY_DEFAULT_DOMAIN : '';
        }else{
            return $this->email;
        }
    }

    /**
     * Get external group ids
     *
     * @access public
     * @return array
     */
    public function getExternalGroupIds()
    {
        return array();
    }

    /**
     * Get extra user attributes
     *
     * @access public
     * @return array
     */
    public function getExtraAttributes()
    {
        if (! REVERSE_PROXY_GET_FIELDS){
            return array(
                'is_ldap_user' => 1,
                'disable_login_form' => 1,
            );
        }else{
            return array(
                'is_ldap_user' => 0,
                'disable_login_form' => 1,
            );
	}
    }
}
