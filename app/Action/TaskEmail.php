<?php

namespace Kanboard\Action;

use Kanboard\Model\TaskModel;

/**
 * Email a task to someone
 *
 * @package Kanboard\Action
 * @author  Frederic Guillot
 */
class TaskEmail extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Send a task by email to someone');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            TaskModel::EVENT_MOVE_COLUMN,
            TaskModel::EVENT_MOVE_COL_AND_SWL,
            TaskModel::EVENT_CLOSE,
            TaskModel::EVENT_CREATE,
        );
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'column_id' => t('Column'),
            'user_id' => t('User that will receive the email'),
            'subject' => t('Email subject'),
        );
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'task' => array(
                'project_id',
                'column_id',
            ),
        );
    }

    /**
     * Execute the action (move the task to another column)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $user = $this->userModel->getById($this->getParam('user_id'));
        $subject = $this->getParam('subject');

        foreach ($data["task"] as $key => $value) {
            $placeholder = sprintf('{{%s}}', $key);
            $subject = str_replace($placeholder, $value, $subject);
        }

        if (! empty($user['email'])) {
            $this->emailClient->send(
                $user['email'],
                $user['name'] ?: $user['username'],
                $subject,
                $this->template->render('notification/task_create', array(
                    'task' => $data['task'],
                ))
            );

            return true;
        }

        return false;
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return $data['task']['column_id'] == $this->getParam('column_id');
    }
}
