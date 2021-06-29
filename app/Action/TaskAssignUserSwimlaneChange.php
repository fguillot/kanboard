<?php

namespace Kanboard\Action;

use Kanboard\Model\TaskModel;

/**
 * Assign a user when the task is moved to a specific swimlane
 *
 * @package Kanboard\Action
 * @author  @Interleaved
 */
class TaskAssignUserSwimlaneChange extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Assign the task to a specific user when the task is moved to a specific swimlane');
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
            TaskModel::EVENT_CREATE,
            TaskModel::EVENT_MOVE_SWIMLANE,
            TaskModel::EVENT_MOVE_COL_AND_SWL,
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
            'swimlane_id' => t('Swimlane'),
            'user_id' => t('Assignee'),
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
                'swimlane_id',
            )
        );
    }

    /**
     * Execute the action (set the task category)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $values = array(
            'id' => $data['task_id'],
            'owner_id' => $this->getParam('user_id'),
        );

        return $this->taskModificationModel->update($values, false);
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
        return $data['task']['swimlane_id'] == $this->getParam('swimlane_id');
    }
}
