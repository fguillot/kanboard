<?php

namespace Kanboard\Controller;

/**
 * Class TaskBulkController
 *
 * @package Kanboard\Controller
 * @author  Frederic Guillot
 */
class TaskBulkController extends BaseController
{
    /**
     * Show the form
     *
     * @param array $values
     * @param array $errors
     */
    public function show(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();

        if (empty($values)) {
            $values = array(
                'swimlane_id' => $this->request->getIntegerParam('swimlane_id'),
                'column_id' => $this->request->getIntegerParam('column_id'),
                'project_id' => $project['id'],
            );
        }

        $this->response->html($this->template->render('task_bulk/show', array(
            'project' => $project,
            'values' => $values,
            'errors' => $errors,
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($project['id'], true, false, $project['is_private'] == 1),
            'colors_list' => $this->colorModel->getList(),
            'categories_list' => $this->categoryModel->getList($project['id']),
            'task_description_templates' => $this->predefinedTaskDescriptionModel->getList($project['id']),
        )));
    }

    /**
     * Save all tasks in the database
     */
    public function save()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();
        list($valid, $errors) = $this->taskValidator->validateBulkCreation($values);

        if (! $valid) {
            $this->show($values, $errors);
        } else if (! $this->helper->projectRole->canCreateTaskInColumn($project['id'], $values['column_id'])) {
            $this->flash->failure(t('You cannot create tasks in this column.'));
            $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $project['id'])), true);
        } else {
            $this->createTasks($project, $values);
            $this->response->redirect($this->helper->url->to(
                'BoardViewController',
                'show',
                array('project_id' => $project['id']),
                'swimlane-'. $values['swimlane_id']
            ), true);
        }
    }

    /**
     * Create all tasks
     *
     * @param array $project
     * @param array $values
     */
    protected function createTasks(array $project, array $values)
    {
        $tasks = preg_split('/\r\n|[\r\n]/', $values['tasks']);

        $last_task_id = null;
        foreach ($tasks as $title) {
            $title = trim($title);
            // add subtask if needed
            $matches = [];
            if(preg_match('/^\s*-\s*(\[[- xX]\])?\s*(.*)/', $title, $matches) && $last_task_id)
            {
              $status = strtolower($matches[1]);
              $sub=['task_id' => $last_task_id, 'title' => $matches[2]];
              if ($status  == '[-]')
                $sub['status'] = \Kanboard\Model\SubtaskModel::STATUS_INPROGRESS;
              if ($status  == '[x]')
                $sub['status'] = \Kanboard\Model\SubtaskModel::STATUS_DONE;
              $this->subtaskModel->create($sub);
              continue;
            }

            $column_id = $values['column_id'];
            $swimlane_id = $values['swimlane_id'];
            $category_id = empty($values['category_id']) ? 0 : $values['category_id'];
            $owner_id = empty($values['owner_id']) ? 0 : $values['owner_id'];
            $color_id = $values['color_id'];
            $project_id = $project['id'];
            $description = $this->getTaskDescription($project, $values);
            $tags = $values['tags'];
            $priority = $values['priority'];
            $score = $values['score'];
            $time_estimated = $values['time_estimated'];
            $date_due = $values['date_due'];
            // extract the colour
            $matches = [];
            if(preg_match('/(.*) *!([a-zA-Z]+)(.*)/', $title, $matches))
            {
              $color= $this->colorModel->find($matches[2]);
              if(color) {
                $color_id = $color;
              }
              $title = $matches[1].$matches[3];
            }
            // extract tags topic from 
            $tag_splits = preg_split('/:/', $title);
            if  ($tag_splits)
            {
              $title = array_shift($tag_splits);
              $tags = $tag_splits;
            }
            // extract description from title
            $matches = [];
            if(preg_match('/^(.*) *\[(.*)\] *$/', $title, $matches))
            {
              $title = $matches[1];
              $description = $matches[2];
            }

            if (! empty($title)) {
              $last_task_id = 
                $this->taskCreationModel->create(array(
                    'title' => $title,
                    'column_id' => $column_id,
                    'swimlane_id' => $swimlane_id,
                    'category_id' => $category_id,
                    'owner_id' => $owner_id,
                    'color_id' => $color_id,
                    'project_id' => $project_id,
                    'description' => $description,
                    'tags' => $tags,
                    'priority' => $priority,
                    'score' => $score,
                    'time_estimated' => $time_estimated,
                    'date_due' => $date_due
                ));

            }
        }
    }

    protected function getTaskDescription(array $project, array $values)
    {
        if (empty($values['task_description_template_id'])) {
            return '';
        }

        return $this->predefinedTaskDescriptionModel->getDescriptionById($project['id'], $values['task_description_template_id']);
    }
}
