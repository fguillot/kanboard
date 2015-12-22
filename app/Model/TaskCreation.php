<?php

namespace Kanboard\Model;

use Kanboard\Event\TaskEvent;

/**
 * Task Creation
 *
 * @package  model
 * @author   Frederic Guillot
 */
class TaskCreation extends Base
{
    /**
     * Create a task
     *
     * @access public
     * @param  array    $values   Form values
     * @return integer
     */
    public function create(array $values)
    {
        if (! $this->project->exists($values['project_id'])) {
            return 0;
        }

        $position = empty($values['position']) ? 0 : $values['position'];

        $opposite_task_id = $values['opposite_task_id'];

        $this->prepare($values);
        $task_id = $this->persist(Task::TABLE, $values);

        if ($task_id !== false) {
            if ($position > 0 && $values['position'] > 1) {
                $this->taskPosition->movePosition($values['project_id'], $task_id, $values['column_id'], $position, $values['swimlane_id'], false);
            }

            if(!empty($opposite_task_id)) {
                $this->taskLink->create($task_id, $opposite_task_id, TaskLink::RELATION_IS_A_PARENT_OF);
            }
            
            $this->fireEvents($task_id, $values);
        }

        return (int) $task_id;
    }

    /**
     * Prepare data
     *
     * @access public
     * @param  array    $values    Form values
     */
    public function prepare(array &$values)
    {
        $this->dateParser->convert($values, array('date_due'));
        $this->dateParser->convert($values, array('date_started'), true);
        $this->removeFields($values, array('another_task', 'opposite_task_id', 'link_id', 'parent_title'));
        $this->resetFields($values, array('date_started', 'creator_id', 'owner_id', 'swimlane_id', 'date_due', 'score', 'category_id', 'time_estimated'));

        if (empty($values['column_id'])) {
            $values['column_id'] = $this->board->getFirstColumn($values['project_id']);
        }

        if (empty($values['color_id'])) {
            $values['color_id'] = $this->color->getDefaultColor();
        }

        if (empty($values['title'])) {
            $values['title'] = t('Untitled');
        }

        if ($this->userSession->isLogged()) {
            $values['creator_id'] = $this->userSession->getId();
        }

        $values['swimlane_id'] = empty($values['swimlane_id']) ? 0 : $values['swimlane_id'];
        $values['date_creation'] = time();
        $values['date_modification'] = $values['date_creation'];
        $values['date_moved'] = $values['date_creation'];
        $values['position'] = $this->taskFinder->countByColumnAndSwimlaneId($values['project_id'], $values['column_id'], $values['swimlane_id']) + 1;
    }

    /**
     * Fire events
     *
     * @access private
     * @param  integer  $task_id     Task id
     * @param  array    $values      Form values
     */
    private function fireEvents($task_id, array $values)
    {
        $values['task_id'] = $task_id;
        $this->container['dispatcher']->dispatch(Task::EVENT_CREATE_UPDATE, new TaskEvent($values));
        $this->container['dispatcher']->dispatch(Task::EVENT_CREATE, new TaskEvent($values));
    }
}
