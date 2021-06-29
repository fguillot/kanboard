<?php

namespace Kanboard\Action;

use Kanboard\Model\TaskModel;

/**
 * Set the start date when task move away from certain column
 *
 * @package Kanboard\Action
 */
class TaskUpdateStartDateOnMoveColumn extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Automatically update the start date when the task is moved away from a specific column');
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
                'project_id',
            ),
            'src_column_id',
        );
    }

    /**
     * Execute the action (set the task date_started)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $values = array(
            'id' => $data['task_id'],
            'date_started' => time(),
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
        return empty($data['task']['date_started']) && $data['src_column_id'] == $this->getParam('column_id');
    }
}
