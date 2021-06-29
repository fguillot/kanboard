<?php

namespace Kanboard\EventBuilder;

use Kanboard\Event\TaskEvent;
use Kanboard\Model\TaskModel;

/**
 * Class TaskEventBuilder
 *
 * @package Kanboard\EventBuilder
 * @author  Frederic Guillot
 */
class TaskEventBuilder extends BaseEventBuilder
{
    /**
     * TaskId
     *
     * @access protected
     * @var int
     */
    protected $taskId = 0;

    /**
     * Task
     *
     * @access protected
     * @var array
     */
    protected $task = array();

    /**
     * Extra values
     *
     * @access protected
     * @var array
     */
    protected $values = array();

    /**
     * Changed values
     *
     * @access protected
     * @var array
     */
    protected $changes = array();

    /**
     * Set TaskId
     *
     * @param  int $taskId
     * @return $this
     */
    public function withTaskId($taskId)
    {
        $this->taskId = $taskId;
        return $this;
    }

    /**
     * Set task
     *
     * @param  array $task
     * @return $this
     */
    public function withTask(array $task)
    {
        $this->task = $task;
        return $this;
    }

    /**
     * Set values
     *
     * @param  array $values
     * @return $this
     */
    public function withValues(array $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * Set changes
     *
     * @param  array $changes
     * @return $this
     */
    public function withChanges(array $changes)
    {
        $this->changes = $changes;
        return $this;
    }

    /**
     * Build event data
     *
     * @access public
     * @return TaskEvent|null
     */
    public function buildEvent()
    {
        $eventData = array();
        $eventData['task_id'] = $this->taskId;
        $eventData['task'] = $this->taskFinderModel->getDetails($this->taskId);

        if (empty($eventData['task'])) {
            $this->logger->debug(__METHOD__.': Task not found');
            return null;
        }

        if (! empty($this->changes)) {
            if (empty($this->task)) {
                $this->task = $eventData['task'];
            }

            $eventData['changes'] = array_diff_assoc($this->changes, $this->task);
            unset($eventData['changes']['date_modification']);
        }

        return new TaskEvent(array_merge($eventData, $this->values));
    }

    /**
     * Get event title with author
     *
     * @access public
     * @param  string $author
     * @param  string $eventName
     * @param  array  $eventData
     * @return string
     */
    public function buildTitleWithAuthor($author, $eventName, array $eventData)
    {
        switch ($eventName) {
            case TaskModel::EVENT_ASSIGNEE_CHANGE:
                $assignee = $eventData['task']['assignee_name'] ?: $eventData['task']['assignee_username'];

                if (! empty($assignee)) {
                    return e('%s changed the assignee of the task #%d to %s', $author, $eventData['task']['id'], $assignee);
                }

                return e('%s removed the assignee of the task %s', $author, e('#%d', $eventData['task']['id']));
            case TaskModel::EVENT_UPDATE:
                return e('%s updated the task #%d', $author, $eventData['task']['id']);
            case TaskModel::EVENT_CREATE:
                return e('%s created the task #%d', $author, $eventData['task']['id']);
            case TaskModel::EVENT_CLOSE:
                return e('%s closed the task #%d', $author, $eventData['task']['id']);
            case TaskModel::EVENT_OPEN:
                return e('%s opened the task #%d', $author, $eventData['task']['id']);
            case TaskModel::EVENT_MOVE_PROJECT:
                return e(
                    '%s moved the task #%d "%s" to the project "%s"',
                    $author,
                    $eventData['task']['id'],
                    $eventData['task']['title'],
                    $eventData['task']['project_name']
                );
            case TaskModel::EVENT_MOVE_COLUMN:
                return e(
                    '%s moved the task #%d to the column "%s"',
                    $author,
                    $eventData['task']['id'],
                    $eventData['task']['column_title']
                );
            case TaskModel::EVENT_MOVE_POSITION:
                return e(
                    '%s moved the task #%d to the position %d in the column "%s"',
                    $author,
                    $eventData['task']['id'],
                    $eventData['task']['position'],
                    $eventData['task']['column_title']
                );
            case TaskModel::EVENT_MOVE_SWIMLANE:
                if ($eventData['task']['swimlane_id'] == 0) {
                    return e('%s moved the task #%d to the first swimlane', $author, $eventData['task']['id']);
                }

                return e(
                    '%s moved the task #%d to the swimlane "%s"',
                    $author,
                    $eventData['task']['id'],
                    $eventData['task']['swimlane_name']
                );

            case TaskModel::EVENT_MOVE_COL_AND_SWL:
                if ($eventData['task']['swimlane_id'] == 0) {
                    return e(
                        '%s moved the task #%d to the column "%s" in the first swimlane',
                        $author,
                        $eventData['task']['id'],
                        $eventData['task']['column_title']
                    );
                }

                return e(
                    '%s moved the task #%d to the column "%s" in the swimlane "%s"',
                    $author,
                    $eventData['task']['id'],
                    $eventData['task']['column_title'],
                    $eventData['task']['swimlane_name']
                );

            case TaskModel::EVENT_USER_MENTION:
                return e('%s mentioned you in the task #%d', $author, $eventData['task']['id']);
            default:
                return '';
        }
    }

    /**
     * Get event title without author
     *
     * @access public
     * @param  string $eventName
     * @param  array  $eventData
     * @return string
     */
    public function buildTitleWithoutAuthor($eventName, array $eventData)
    {
        switch ($eventName) {
            case TaskModel::EVENT_CREATE:
                return e('New task #%d: %s', $eventData['task']['id'], $eventData['task']['title']);
            case TaskModel::EVENT_UPDATE:
                return e('Task updated #%d', $eventData['task']['id']);
            case TaskModel::EVENT_CLOSE:
                return e('Task #%d closed', $eventData['task']['id']);
            case TaskModel::EVENT_OPEN:
                return e('Task #%d opened', $eventData['task']['id']);
            case TaskModel::EVENT_MOVE_PROJECT:
                return e('Task #%d "%s" has been moved to the project "%s"', $eventData['task']['id'], $eventData['task']['title'], $eventData['task']['project_name']);
            case TaskModel::EVENT_MOVE_COLUMN:
                return e('Column changed for task #%d', $eventData['task']['id']);
            case TaskModel::EVENT_MOVE_POSITION:
                return e('New position for task #%d', $eventData['task']['id']);
            case TaskModel::EVENT_MOVE_SWIMLANE:
                return e('Swimlane changed for task #%d', $eventData['task']['id']);
            case TaskModel::EVENT_ASSIGNEE_CHANGE:
                return e('Assignee changed on task #%d', $eventData['task']['id']);
            case TaskModel::EVENT_OVERDUE:
                $nb = count($eventData['tasks']);
                return $nb > 1 ? e('%d overdue tasks', $nb) : e('Task #%d is overdue', $eventData['tasks'][0]['id']);
            case TaskModel::EVENT_USER_MENTION:
                return e('You were mentioned in the task #%d', $eventData['task']['id']);
            default:
                return '';
        }
    }
}
