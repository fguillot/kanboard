<?php

namespace Kanboard\Core\Event;

use Kanboard\Model\TaskModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskLinkModel;

/**
 * Event Manager
 *
 * @package  event
 * @author   Frederic Guillot
 */
class EventManager
{
    /**
     * Extended events
     *
     * @access private
     * @var array
     */
    private $events = array();

    /**
     * Add new event
     *
     * @access public
     * @param  string  $event
     * @param  string  $description
     * @return EventManager
     */
    public function register($event, $description)
    {
        $this->events[$event] = $description;
        return $this;
    }

    /**
     * Get the list of events and description that can be used from the user interface
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        $events = array(
            TaskLinkModel::EVENT_CREATE_UPDATE => t('Task link creation or modification'),
            TaskModel::EVENT_MOVE_COLUMN       => t('Move a task to another column'),
            TaskModel::EVENT_UPDATE            => t('Task modification'),
            TaskModel::EVENT_CREATE            => t('Task creation'),
            TaskModel::EVENT_OPEN              => t('Reopen a task'),
            TaskModel::EVENT_CLOSE             => t('Closing a task'),
            TaskModel::EVENT_CREATE_UPDATE     => t('Task creation or modification'),
            TaskModel::EVENT_ASSIGNEE_CHANGE   => t('Task assignee change'),
            TaskModel::EVENT_DAILY_CRONJOB     => t('Daily background job for tasks'),
            TaskModel::EVENT_MOVE_SWIMLANE     => t('Move a task to another swimlane'),
            TaskModel::EVENT_MOVE_COL_AND_SWL  => t('Move a task to another column AND swimlane'),
            SubtaskModel::EVENT_CREATE_UPDATE  => t('Subtask creation or modification'),
        );

        $events = array_merge($events, $this->events);
        asort($events);

        return $events;
    }
}
