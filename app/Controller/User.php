<?php

namespace Kanboard\Controller;

use Kanboard\Notification\Mail as MailNotification;
use Kanboard\Model\Project as ProjectModel;
use Kanboard\Core\Security\Role;

/**
 * User controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class User extends Base
{
    /**
     * Common layout for user views
     *
     * @access protected
     * @param  string    $template   Template name
     * @param  array     $params     Template parameters
     * @return string
     */
    protected function layout($template, array $params)
    {
        $content = $this->template->render($template, $params);
        $params['user_content_for_layout'] = $content;
        $params['board_selector'] = $this->projectUserRole->getActiveProjectsByUser($this->userSession->getId());

        if (isset($params['user'])) {
            $params['title'] = ($params['user']['name'] ?: $params['user']['username']).' (#'.$params['user']['id'].')';
        }

        return $this->template->layout('user/layout', $params);
    }

    /**
     * List all users
     *
     * @access public
     */
    public function index()
    {
        $paginator = $this->paginator
                ->setUrl('user', 'index')
                ->setMax(30)
                ->setOrder('username')
                ->setQuery($this->user->getQuery())
                ->calculate();

        $this->response->html(
            $this->template->layout('user/index', array(
                'board_selector' => $this->projectUserRole->getActiveProjectsByUser($this->userSession->getId()),
                'title' => t('Users').' ('.$paginator->getTotal().')',
                'paginator' => $paginator,
        )));
    }

    /**
     * Public user profile
     *
     * @access public
     */
    public function profile()
    {
        $user = $this->user->getById($this->request->getIntegerParam('user_id'));

        if (empty($user)) {
            $this->notfound();
        }

        $this->response->html(
            $this->template->layout('user/profile', array(
                'board_selector' => $this->projectUserRole->getActiveProjectsByUser($this->userSession->getId()),
                'title' => $user['name'] ?: $user['username'],
                'user' => $user,
            )
        ));
    }

    /**
     * Display a form to create a new user
     *
     * @access public
     */
    public function create(array $values = array(), array $errors = array())
    {
        $is_remote = $this->request->getIntegerParam('remote') == 1 || (isset($values['is_ldap_user']) && $values['is_ldap_user'] == 1);

        $this->response->html($this->template->layout($is_remote ? 'user/create_remote' : 'user/create_local', array(
            'timezones' => $this->config->getTimezones(true),
            'languages' => $this->config->getLanguages(true),
            'roles' => $this->role->getApplicationRoles(),
            'board_selector' => $this->projectUserRole->getActiveProjectsByUser($this->userSession->getId()),
            'projects' => $this->project->getList(),
            'errors' => $errors,
            'values' => $values + array('role' => Role::APP_USER),
            'title' => t('New user')
        )));
    }

    /**
     * Validate and save a new user
     *
     * @access public
     */
    public function save()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->userValidator->validateCreation($values);

        if ($valid) {
            $project_id = empty($values['project_id']) ? 0 : $values['project_id'];
            unset($values['project_id']);

            $is_invitation = (isset($values['email_invitation']) && $values['email_invitation'] == 1);
            if ($is_invitation) {
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $values['password'] = substr(str_shuffle($chars), 0, 12);
                unset($values['email_invitation']);
            }
            $user_id = $this->user->create($values);

            if ($user_id !== false) {
                $this->projectUserRole->addUser($project_id, $user_id, Role::PROJECT_MEMBER);

                if (! empty($values['notifications_enabled'])) {
                    $this->userNotificationType->saveSelectedTypes($user_id, array(MailNotification::TYPE));
                }

                if ($is_invitation) {
                    $this->sendInvitationEmail($values['username']);
                }

                $this->flash->success(t('User created successfully.').($is_invitation ? ' '.t('An invitation has been sent by email.') : ''));
                $this->response->redirect($this->helper->url->to('user', 'show', array('user_id' => $user_id)));
            } else {
                $this->flash->failure(t('Unable to create your user.'));
                $values['project_id'] = $project_id;
            }
        }

        $this->create($values, $errors);
    }

    /**
     * Send an invitation email to the new user
     */
    private function sendInvitationEmail($username)
    {
        $invitor = $this->getUser();
        $token = $this->passwordReset->create($username, time()+157680000); // Expire in 5 years

        if ($token !== false) {
            $user = $this->user->getByUsername($username);

            $this->emailClient->send(
                $user['email'],
                $user['name'] ?: $user['username'],
                t('New account for Kanboard'),
                $this->template->render('user/email_invitation', array('token' => $token, 'user' => $user, 'invitor' => $invitor))
            );
        }
    }

    /**
     * Display user information
     *
     * @access public
     */
    public function show()
    {
        $user = $this->getUser();
        $this->response->html($this->layout('user/show', array(
            'user' => $user,
            'timezones' => $this->config->getTimezones(true),
            'languages' => $this->config->getLanguages(true),
        )));
    }

    /**
     * Display timesheet
     *
     * @access public
     */
    public function timesheet()
    {
        $user = $this->getUser();

        $subtask_paginator = $this->paginator
            ->setUrl('user', 'timesheet', array('user_id' => $user['id'], 'pagination' => 'subtasks'))
            ->setMax(20)
            ->setOrder('start')
            ->setDirection('DESC')
            ->setQuery($this->subtaskTimeTracking->getUserQuery($user['id']))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'subtasks');

        $this->response->html($this->layout('user/timesheet', array(
            'subtask_paginator' => $subtask_paginator,
            'user' => $user,
        )));
    }

    /**
     * Display last password reset
     *
     * @access public
     */
    public function passwordReset()
    {
        $user = $this->getUser();
        $this->response->html($this->layout('user/password_reset', array(
            'tokens' => $this->passwordReset->getAll($user['id']),
            'user' => $user,
        )));
    }

    /**
     * Display last connections
     *
     * @access public
     */
    public function last()
    {
        $user = $this->getUser();
        $this->response->html($this->layout('user/last', array(
            'last_logins' => $this->lastLogin->getAll($user['id']),
            'user' => $user,
        )));
    }

    /**
     * Display user sessions
     *
     * @access public
     */
    public function sessions()
    {
        $user = $this->getUser();
        $this->response->html($this->layout('user/sessions', array(
            'sessions' => $this->rememberMeSession->getAll($user['id']),
            'user' => $user,
        )));
    }

    /**
     * Remove a "RememberMe" token
     *
     * @access public
     */
    public function removeSession()
    {
        $this->checkCSRFParam();
        $user = $this->getUser();
        $this->rememberMeSession->remove($this->request->getIntegerParam('id'));
        $this->response->redirect($this->helper->url->to('user', 'sessions', array('user_id' => $user['id'])));
    }

    /**
     * Display user notifications
     *
     * @access public
     */
    public function notifications()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            $this->userNotification->saveSettings($user['id'], $values);
            $this->flash->success(t('User updated successfully.'));
            $this->response->redirect($this->helper->url->to('user', 'notifications', array('user_id' => $user['id'])));
        }

        $this->response->html($this->layout('user/notifications', array(
            'projects' => $this->projectUserRole->getProjectsByUser($user['id'], array(ProjectModel::ACTIVE)),
            'notifications' => $this->userNotification->readSettings($user['id']),
            'types' => $this->userNotificationType->getTypes(),
            'filters' => $this->userNotificationFilter->getFilters(),
            'user' => $user,
        )));
    }

    /**
     * Display user integrations
     *
     * @access public
     */
    public function integrations()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            $this->userMetadata->save($user['id'], $values);
            $this->flash->success(t('User updated successfully.'));
            $this->response->redirect($this->helper->url->to('user', 'integrations', array('user_id' => $user['id'])));
        }

        $this->response->html($this->layout('user/integrations', array(
            'user' => $user,
            'values' => $this->userMetadata->getall($user['id']),
        )));
    }

    /**
     * Display external accounts
     *
     * @access public
     */
    public function external()
    {
        $user = $this->getUser();
        $this->response->html($this->layout('user/external', array(
            'last_logins' => $this->lastLogin->getAll($user['id']),
            'user' => $user,
        )));
    }

    /**
     * Public access management
     *
     * @access public
     */
    public function share()
    {
        $user = $this->getUser();
        $switch = $this->request->getStringParam('switch');

        if ($switch === 'enable' || $switch === 'disable') {
            $this->checkCSRFParam();

            if ($this->user->{$switch.'PublicAccess'}($user['id'])) {
                $this->flash->success(t('User updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update this user.'));
            }

            $this->response->redirect($this->helper->url->to('user', 'share', array('user_id' => $user['id'])));
        }

        $this->response->html($this->layout('user/share', array(
            'user' => $user,
            'title' => t('Public access'),
        )));
    }

    /**
     * Password modification
     *
     * @access public
     */
    public function password()
    {
        $user = $this->getUser();
        $values = array('id' => $user['id']);
        $errors = array();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            list($valid, $errors) = $this->userValidator->validatePasswordModification($values);

            if ($valid) {
                if ($this->user->update($values)) {
                    $this->flash->success(t('Password modified successfully.'));
                } else {
                    $this->flash->failure(t('Unable to change the password.'));
                }

                $this->response->redirect($this->helper->url->to('user', 'show', array('user_id' => $user['id'])));
            }
        }

        $this->response->html($this->layout('user/password', array(
            'values' => $values,
            'errors' => $errors,
            'user' => $user,
        )));
    }

    /**
     * Display a form to edit a user
     *
     * @access public
     */
    public function edit()
    {
        $user = $this->getUser();
        $values = $user;
        $errors = array();

        unset($values['password']);

        if ($this->request->isPost()) {
            $values = $this->request->getValues();

            if (! $this->userSession->isAdmin()) {
                if (isset($values['role'])) {
                    unset($values['role']);
                }
            }

            list($valid, $errors) = $this->userValidator->validateModification($values);

            if ($valid) {
                if ($this->user->update($values)) {
                    $this->flash->success(t('User updated successfully.'));
                } else {
                    $this->flash->failure(t('Unable to update your user.'));
                }

                $this->response->redirect($this->helper->url->to('user', 'show', array('user_id' => $user['id'])));
            }
        }

        $this->response->html($this->layout('user/edit', array(
            'values' => $values,
            'errors' => $errors,
            'user' => $user,
            'timezones' => $this->config->getTimezones(true),
            'languages' => $this->config->getLanguages(true),
            'roles' => $this->role->getApplicationRoles(),
        )));
    }

    /**
     * Display a form to edit authentication
     *
     * @access public
     */
    public function authentication()
    {
        $user = $this->getUser();
        $values = $user;
        $errors = array();

        unset($values['password']);

        if ($this->request->isPost()) {
            $values = $this->request->getValues() + array('disable_login_form' => 0, 'is_ldap_user' => 0);
            list($valid, $errors) = $this->userValidator->validateModification($values);

            if ($valid) {
                if ($this->user->update($values)) {
                    $this->flash->success(t('User updated successfully.'));
                } else {
                    $this->flash->failure(t('Unable to update your user.'));
                }

                $this->response->redirect($this->helper->url->to('user', 'authentication', array('user_id' => $user['id'])));
            }
        }

        $this->response->html($this->layout('user/authentication', array(
            'values' => $values,
            'errors' => $errors,
            'user' => $user,
        )));
    }

    /**
     * Remove a user
     *
     * @access public
     */
    public function remove()
    {
        $user = $this->getUser();

        if ($this->request->getStringParam('confirmation') === 'yes') {
            $this->checkCSRFParam();

            if ($this->user->remove($user['id'])) {
                $this->flash->success(t('User removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this user.'));
            }

            $this->response->redirect($this->helper->url->to('user', 'index'));
        }

        $this->response->html($this->layout('user/remove', array(
            'user' => $user,
        )));
    }
}
