<?php

namespace Kanboard\Model;

use Kanboard\Core\Base;

/**
 * Task Position
 *
 * @package  Kanboard\Model
 * @author   Frederic Guillot
 */
class TaskPositionModel extends Base
{
    public function moveBottom($project_id, $task_id, $swimlane_id, $column_id)
    {
        $this->db->startTransaction();

        $task = $this->taskFinderModel->getById($task_id);

        $result = $this->db->table(TaskModel::TABLE)
            ->eq('project_id', $project_id)
            ->eq('swimlane_id', $swimlane_id)
            ->eq('column_id', $column_id)
            ->columns('MAX(position) AS pos')
            ->findOne();

        $position = 1;
        if (! empty($result)) {
            $position = $result['pos'] + 1;
        }

        $result = $this->db->table(TaskModel::TABLE)
            ->eq('id', $task_id)
            ->eq('project_id', $project_id)
            ->update([
                'swimlane_id' => $swimlane_id,
                'column_id' => $column_id,
                'position' => $position,
                'date_moved' => time(),
                'date_modification' => time(),
            ]);

        $this->db->closeTransaction();

        if ($result) {
            $this->fireEvents($task, $column_id, $position, $swimlane_id);
        }

        return $result;
    }

    /**
     * Move a task to another column or to another position
     *
     * @access public
     * @param  integer $project_id  Project id
     * @param  integer $task_id     Task id
     * @param  integer $column_id   Column id
     * @param  integer $position    Position (must be >= 1)
     * @param  integer $swimlane_id Swimlane id
     * @param  boolean $fire_events Fire events
     * @param  bool    $onlyOpen    Do not move closed tasks
     * @return bool
     */
    public function movePosition($project_id, $task_id, $column_id, $position, $swimlane_id = 0, $fire_events = true, $onlyOpen = true)
    {
        if ($position < 1) {
            return false;
        }

        $task = $this->taskFinderModel->getById($task_id);

        if ($swimlane_id == 0) {
            $swimlane_id = $task['swimlane_id'];
        }

        if ($onlyOpen && $task['is_active'] == TaskModel::STATUS_CLOSED) {
            return true;
        }

        $result = false;

        if ($task['swimlane_id'] != $swimlane_id) {
            $result = $this->saveSwimlaneChange($project_id, $task_id, $position, $task['column_id'], $column_id, $task['swimlane_id'], $swimlane_id);
        } elseif ($task['column_id'] != $column_id) {
            $result = $this->saveColumnChange($project_id, $task_id, $position, $swimlane_id, $task['column_id'], $column_id);
        } elseif ($task['position'] != $position) {
            $result = $this->savePositionChange($project_id, $task_id, $position, $column_id, $swimlane_id);
        }

        if ($result && $fire_events) {
            $this->fireEvents($task, $column_id, $position, $swimlane_id);
        }

        return $result;
    }

    /**
     * Move a task to another swimlane
     *
     * @access private
     * @param  integer    $project_id
     * @param  integer    $task_id
     * @param  integer    $position
     * @param  integer    $original_column_id
     * @param  integer    $new_column_id
     * @param  integer    $original_swimlane_id
     * @param  integer    $new_swimlane_id
     * @return boolean
     */
    private function saveSwimlaneChange($project_id, $task_id, $position, $original_column_id, $new_column_id, $original_swimlane_id, $new_swimlane_id)
    {
        $this->db->startTransaction();
        $r1 = $this->saveTaskPositions($project_id, $task_id, 0, $original_column_id, $original_swimlane_id);
        $r2 = $this->saveTaskPositions($project_id, $task_id, $position, $new_column_id, $new_swimlane_id);
        $r3 = $this->saveTaskTimestamps($task_id);
        $this->db->closeTransaction();

        return $r1 && $r2 && $r3;
    }

    /**
     * Move a task to another column
     *
     * @access private
     * @param  integer    $project_id
     * @param  integer    $task_id
     * @param  integer    $position
     * @param  integer    $swimlane_id
     * @param  integer    $original_column_id
     * @param  integer    $new_column_id
     * @return boolean
     */
    private function saveColumnChange($project_id, $task_id, $position, $swimlane_id, $original_column_id, $new_column_id)
    {
        $this->db->startTransaction();
        $r1 = $this->saveTaskPositions($project_id, $task_id, 0, $original_column_id, $swimlane_id);
        $r2 = $this->saveTaskPositions($project_id, $task_id, $position, $new_column_id, $swimlane_id);
        $r3 = $this->saveTaskTimestamps($task_id);
        $this->db->closeTransaction();

        return $r1 && $r2 && $r3;
    }

    /**
     * Move a task to another position in the same column
     *
     * @access private
     * @param  integer    $project_id
     * @param  integer    $task_id
     * @param  integer    $position
     * @param  integer    $column_id
     * @param  integer    $swimlane_id
     * @return boolean
     */
    private function savePositionChange($project_id, $task_id, $position, $column_id, $swimlane_id)
    {
        $this->db->startTransaction();
        $result = $this->saveTaskPositions($project_id, $task_id, $position, $column_id, $swimlane_id);
        $this->db->closeTransaction();

        return $result;
    }

    /**
     * Save all task positions for one column
     *
     * @access private
     * @param  integer    $project_id
     * @param  integer    $task_id
     * @param  integer    $position
     * @param  integer    $column_id
     * @param  integer    $swimlane_id
     * @return boolean
     */
    private function saveTaskPositions($project_id, $task_id, $position, $column_id, $swimlane_id)
    {
        $tasks_ids = $this->db->table(TaskModel::TABLE)
            ->eq('is_active', 1)
            ->eq('swimlane_id', $swimlane_id)
            ->eq('project_id', $project_id)
            ->eq('column_id', $column_id)
            ->neq('id', $task_id)
            ->asc('position')
            ->asc('id')
            ->findAllByColumn('id');

        $offset = 1;

        foreach ($tasks_ids as $current_task_id) {

            // Insert the new task
            if ($position == $offset) {
                if (! $this->saveTaskPosition($task_id, $offset, $column_id, $swimlane_id)) {
                    return false;
                }
                $offset++;
            }

            // Rewrite other tasks position
            if (! $this->saveTaskPosition($current_task_id, $offset, $column_id, $swimlane_id)) {
                return false;
            }

            $offset++;
        }

        // Insert the new task at the bottom and normalize bad position
        if ($position >= $offset && ! $this->saveTaskPosition($task_id, $offset, $column_id, $swimlane_id)) {
            return false;
        }

        return true;
    }

    /**
     * Update task timestamps
     *
     * @access private
     * @param  integer $task_id
     * @return bool
     */
    private function saveTaskTimestamps($task_id)
    {
        $now = time();

        return $this->db->table(TaskModel::TABLE)->eq('id', $task_id)->update(array(
            'date_moved' => $now,
            'date_modification' => $now,
        ));
    }

    /**
     * Save new task position
     *
     * @access private
     * @param  integer    $task_id
     * @param  integer    $position
     * @param  integer    $column_id
     * @param  integer    $swimlane_id
     * @return boolean
     */
    private function saveTaskPosition($task_id, $position, $column_id, $swimlane_id)
    {
        $result = $this->db->table(TaskModel::TABLE)->eq('id', $task_id)->update(array(
            'position' => $position,
            'column_id' => $column_id,
            'swimlane_id' => $swimlane_id,
        ));

        if (! $result) {
            $this->db->cancelTransaction();
            return false;
        }

        return true;
    }

    /**
     * Fire events
     *
     * @access private
     * @param  array     $task
     * @param  integer   $new_column_id
     * @param  integer   $new_position
     * @param  integer   $new_swimlane_id
     */
    private function fireEvents(array $task, $new_column_id, $new_position, $new_swimlane_id)
    {
        $changes = array(
            'project_id' => $task['project_id'],
            'position' => $new_position,
            'column_id' => $new_column_id,
            'swimlane_id' => $new_swimlane_id,
            'src_column_id' => $task['column_id'],
            'dst_column_id' => $new_column_id,
            'date_moved' => $task['date_moved'],
            'recurrence_status' => $task['recurrence_status'],
            'recurrence_trigger' => $task['recurrence_trigger'],
        );

        if ($task['column_id'] != $new_column_id && $task['swimlane_id'] != $new_swimlane_id) {
            $this->taskEventJob->execute(
                $task['id'],
                array(TaskModel::EVENT_MOVE_COL_AND_SWL),
                $changes,
                $changes
            );
        } elseif ($task['swimlane_id'] != $new_swimlane_id) {
            $this->taskEventJob->execute(
                $task['id'],
                array(TaskModel::EVENT_MOVE_SWIMLANE),
                $changes,
                $changes
            );
        } elseif ($task['column_id'] != $new_column_id) {
            $this->taskEventJob->execute(
                $task['id'],
                array(TaskModel::EVENT_MOVE_COLUMN),
                $changes,
                $changes
            );
        } elseif ($task['position'] != $new_position) {
            $this->taskEventJob->execute(
                $task['id'],
                array(TaskModel::EVENT_MOVE_POSITION),
                $changes,
                $changes
            );
        }
    }
}
