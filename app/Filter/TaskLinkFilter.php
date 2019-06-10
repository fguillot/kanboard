<?php

namespace Kanboard\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Model\LinkModel;
use Kanboard\Model\TaskModel;
use Kanboard\Model\TaskLinkModel;
use PicoDb\Database;
use PicoDb\Table;

/**
 * Filter tasks by link name
 *
 * @package filter
 * @author  Frederic Guillot
 */
class TaskLinkFilter extends BaseFilter implements FilterInterface
{
    /**
     * Database object
     *
     * @access private
     * @var Database
     */
    private $db;

    /**
     * Set database object
     *
     * @access public
     * @param  Database $db
     * @return TaskLinkFilter
     */
    public function setDatabase(Database $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('link');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return string
     */
    public function apply()
    {
        if ($this->value === 'none') {
            $task_ids = $this->getWithoutLinkTaskIds();
        } elseif ($this->value === 'toplevel') {
            $task_ids = $this->getTopLevelTaskIds();
        } else {
            $task_ids = $this->getLinkLabelTaskIds();
        }

        if (! empty($task_ids)) {
            $this->query->in(TaskModel::TABLE.'.id', $task_ids);
        } else {
            $this->query->eq(TaskModel::TABLE.'.id', 0); // No match
        }
    }

    /**
     * Get IDs of tasks according to the label of their links
     *
     * @access protected
     * @return array
     */
    protected function getLinkLabelTaskIds()
    {
        return $this->db->table(TaskLinkModel::TABLE)
            ->columns(
                TaskLinkModel::TABLE.'.task_id',
                LinkModel::TABLE.'.label'
            )
            ->join(LinkModel::TABLE, 'id', 'link_id', TaskLinkModel::TABLE)
            ->ilike(LinkModel::TABLE.'.label', $this->value)
            ->findAllByColumn('task_id');
    }

    /**
     * Get IDs of tasks that are not linked to other tasks
     *
     * @access protected
     * @return array
     */
    protected function getWithoutLinkTaskIds()
    {
        return $this->db->table(TaskModel::TABLE)
            ->left(TaskLinkModel::TABLE, 'tt', 'task_id', TaskModel::TABLE, 'id')
            ->isNull('tt.link_id')
            ->findAllByColumn(TaskModel::TABLE . '.id');
    }

    /**
     * Get IDs of tasks that are not a child of other tasks
     *
     * @access protected
     * @return array
     */
    protected function getTopLevelTaskIds()
    {
        // tasks with links "is a child of" :
        $subquery = $this->db->table(TaskLinkModel::TABLE)
            ->columns('task_id')->eq('link_id', '6');
        // the other tasks
        $result = $this->db->table(TaskModel::TABLE)
            ->notInSubquery('id', $subquery)
            ->findAllByColumn(TaskModel::TABLE . '.id');
        return $result;
    }
}
