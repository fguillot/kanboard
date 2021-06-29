<?php

namespace Kanboard\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Model\SubtaskModel;

/**
 * Create a subtask and activate the timer when moving a task to another column.
 *
 * @package Kanboard\Action
 * @author  Frederic Guillot
 */
class SubtaskTimerMoveTaskColumn extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Add a subtask and activate the timer when moving a task to another column');
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
            'subtask' => t('Subtask Title'),
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
                'id',
                'column_id',
                'project_id',
                'creator_id',
            ),
        );
    }

    /**
     * Execute the action (append to the task description).
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $subtaskID = $this->subtaskModel->create(array(
            'title' => $this->getParam('subtask'),
            'user_id' => $data['task']['creator_id'],
            'task_id' => $data['task']['id'],
            'status' => SubtaskModel::STATUS_INPROGRESS,
        ));

        if ($subtaskID !== false) {
            return $this->subtaskTimeTrackingModel->toggleTimer($subtaskID, $data['task']['creator_id'], SubtaskModel::STATUS_INPROGRESS);
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
