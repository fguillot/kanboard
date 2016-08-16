<?php

namespace Kanboard\Controller;

use Kanboard\Core\Controller\PageNotFoundException;

/**
 * Task Creation Controller
 *
 * @package  Kanboard\Controller
 * @author   Frederic Guillot
 */
class TaskCreationController extends BaseController
{
    /**
     * Display a form to create a new task
     *
     * @access public
     * @param  array $values
     * @param  array $errors
     * @throws PageNotFoundException
     */
    public function show(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $swimlanes_list = $this->swimlaneModel->getList($project['id'], false, true);

        if (empty($values)) {
            $values = $this->prepareValues($swimlanes_list);
        }

        $this->response->html($this->template->render('task_creation/show', array(
            'project' => $project,
            'errors' => $errors,
            'values' => $values + array('project_id' => $project['id']),
            'columns_list' => $this->columnModel->getList($project['id']),
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($project['id'], true, false, true),
            'categories_list' => $this->categoryModel->getList($project['id']),
            'swimlanes_list' => $swimlanes_list,
        )));
    }

    /**
     * Validate and save a new task
     *
     * @access public
     */
    public function save()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateCreation($values);

        if ($valid && $this->taskCreationModel->create($values)) {
            $this->flash->success(t('Task created successfully.'));
            $this->afterSave($project, $values);
        } else {
            $this->flash->failure(t('Unable to create your task.'));
            $this->show($values, $errors);
        }
    }

    private function afterSave(array $project, array &$values)
    {
        if (isset($values['another_task']) && $values['another_task'] == 1) {
            $this->show(array(
                'owner_id' => $values['owner_id'],
                'color_id' => $values['color_id'],
                'category_id' => isset($values['category_id']) ? $values['category_id'] : 0,
                'column_id' => $values['column_id'],
                'swimlane_id' => isset($values['swimlane_id']) ? $values['swimlane_id'] : 0,
                'another_task' => 1,
            ));
        } else {
            $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $project['id'])), true);
        }
    }

    /**
     * Prepare form values
     *
     * @access protected
     * @param  array $swimlanes_list
     * @return array
     */
    protected function prepareValues(array $swimlanes_list)
    {
        $values = array(
            'swimlane_id' => $this->request->getIntegerParam('swimlane_id', key($swimlanes_list)),
            'column_id'   => $this->request->getIntegerParam('column_id'),
            'color_id'    => $this->colorModel->getDefaultColor(),
            'owner_id'    => $this->userSession->getId(),
        );

        $values = $this->hook->merge('controller:task:form:default', $values, array('default_values' => $values));
        $values = $this->hook->merge('controller:task-creation:form:default', $values, array('default_values' => $values));
        return $values;
    }
}
