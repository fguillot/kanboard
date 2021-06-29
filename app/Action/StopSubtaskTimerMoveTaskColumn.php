<?php

namespace Kanboard\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Model\SubtaskModel;

/**
 * Stop the timer of all subtasks when moving a task to another column.
 *
 * @package Kanboard\Action
 * @author  Frederic Guillot
 */
class StopSubtaskTimerMoveTaskColumn extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Stop the timer of all subtasks when moving a task to another column');
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
        $subtasks = $this->subtaskModel->getAll($data['task']['id']);
        $results = array();

        foreach ($subtasks as $subtask) {
            $results[] = $this->subtaskModel->update(array('id' => $subtask['id'], 'status' => SubtaskModel::STATUS_DONE));
            $results[] = $this->subtaskTimeTrackingModel->logEndTime($subtask['id'], $subtask['user_id']);
        }

        return !in_array(false, $results, true);
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
