<?php

namespace Kanboard\ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Kanboard\Action\TaskAssignCategorySwimlaneChange;
use Kanboard\Action\TaskAssignColorOnDueDate;
use Kanboard\Action\TaskAssignColorOnStartDate;
use Kanboard\Action\TaskAssignColorPriority;
use Kanboard\Action\TaskAssignDueDateOnCreation;
use Kanboard\Action\TaskMoveColumnClosed;
use Kanboard\Action\TaskMoveColumnNotMovedPeriod;
use Kanboard\Action\TaskMoveColumnOnDueDate;
use Kanboard\Core\Action\ActionManager;
use Kanboard\Action\CommentCreation;
use Kanboard\Action\CommentCreationMoveTaskColumn;
use Kanboard\Action\TaskAssignCategoryColor;
use Kanboard\Action\TaskAssignCategoryLabel;
use Kanboard\Action\TaskAssignCategoryLink;
use Kanboard\Action\TaskAssignColorCategory;
use Kanboard\Action\TaskAssignColorColumn;
use Kanboard\Action\TaskAssignColorLink;
use Kanboard\Action\TaskAssignColorUser;
use Kanboard\Action\TaskAssignCreator;
use Kanboard\Action\TaskAssignCurrentUser;
use Kanboard\Action\TaskAssignCurrentUserColumn;
use Kanboard\Action\TaskAssignSpecificUser;
use Kanboard\Action\TaskAssignUser;
use Kanboard\Action\TaskAssignUserSwimlaneChange;
use Kanboard\Action\TaskClose;
use Kanboard\Action\TaskCloseColumn;
use Kanboard\Action\TaskCreation;
use Kanboard\Action\TaskDuplicateAnotherProject;
use Kanboard\Action\TaskEmail;
use Kanboard\Action\TaskEmailNoActivity;
use Kanboard\Action\TaskMoveAnotherProject;
use Kanboard\Action\TaskMoveColumnAssigned;
use Kanboard\Action\TaskMoveSwimlaneAssigned;
use Kanboard\Action\TaskMoveColumnCategoryChange;
use Kanboard\Action\TaskMoveColumnUnAssigned;
use Kanboard\Action\TaskMoveSwimlaneCategoryChange;
use Kanboard\Action\TaskOpen;
use Kanboard\Action\TaskUpdateStartDate;
use Kanboard\Action\TaskUpdateStartDateOnMoveColumn;
use Kanboard\Action\TaskCloseNoActivity;
use Kanboard\Action\TaskCloseNoActivityColumn;
use Kanboard\Action\TaskCloseNotMovedColumn;
use Kanboard\Action\TaskAssignColorSwimlane;
use Kanboard\Action\TaskAssignPrioritySwimlane;
use Kanboard\Action\SubtaskTimerMoveTaskColumn;
use Kanboard\Action\StopSubtaskTimerMoveTaskColumn;
use Kanboard\Action\TaskMoveColumnOnStartDate;
use Kanboard\Action\TaskAssignDueDateOnMoveColumn;


/**
 * Action Provider
 *
 * @package Kanboard\ServiceProvider
 * @author  Frederic Guillot
 */
class ActionProvider implements ServiceProviderInterface
{
    /**
     * Register providers
     *
     * @access public
     * @param  \Pimple\Container $container
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['actionManager'] = new ActionManager($container);
        $container['actionManager']->register(new CommentCreation($container));
        $container['actionManager']->register(new CommentCreationMoveTaskColumn($container));
        $container['actionManager']->register(new TaskAssignCategorySwimlaneChange($container));
        $container['actionManager']->register(new TaskAssignCategoryColor($container));
        $container['actionManager']->register(new TaskAssignCategoryLabel($container));
        $container['actionManager']->register(new TaskAssignCategoryLink($container));
        $container['actionManager']->register(new TaskAssignColorCategory($container));
        $container['actionManager']->register(new TaskAssignColorColumn($container));
        $container['actionManager']->register(new TaskAssignColorLink($container));
        $container['actionManager']->register(new TaskAssignColorUser($container));
        $container['actionManager']->register(new TaskAssignColorPriority($container));
        $container['actionManager']->register(new TaskAssignCreator($container));
        $container['actionManager']->register(new TaskAssignCurrentUser($container));
        $container['actionManager']->register(new TaskAssignCurrentUserColumn($container));
        $container['actionManager']->register(new TaskAssignSpecificUser($container));
        $container['actionManager']->register(new TaskAssignUser($container));
        $container['actionManager']->register(new TaskAssignUserSwimlaneChange($container));
        $container['actionManager']->register(new TaskClose($container));
        $container['actionManager']->register(new TaskCloseColumn($container));
        $container['actionManager']->register(new TaskCloseNoActivity($container));
        $container['actionManager']->register(new TaskCloseNoActivityColumn($container));
        $container['actionManager']->register(new TaskCloseNotMovedColumn($container));
        $container['actionManager']->register(new TaskCreation($container));
        $container['actionManager']->register(new TaskDuplicateAnotherProject($container));
        $container['actionManager']->register(new TaskEmail($container));
        $container['actionManager']->register(new TaskEmailNoActivity($container));
        $container['actionManager']->register(new TaskMoveAnotherProject($container));
        $container['actionManager']->register(new TaskMoveColumnAssigned($container));
        $container['actionManager']->register(new TaskMoveSwimlaneAssigned($container));
        $container['actionManager']->register(new TaskMoveColumnCategoryChange($container));
        $container['actionManager']->register(new TaskMoveColumnClosed($container));
        $container['actionManager']->register(new TaskMoveColumnNotMovedPeriod($container));
        $container['actionManager']->register(new TaskMoveColumnOnDueDate($container));
        $container['actionManager']->register(new TaskMoveColumnUnAssigned($container));
        $container['actionManager']->register(new TaskMoveSwimlaneCategoryChange($container));
        $container['actionManager']->register(new TaskOpen($container));
        $container['actionManager']->register(new TaskUpdateStartDate($container));
        $container['actionManager']->register(new TaskUpdateStartDateOnMoveColumn($container));
        $container['actionManager']->register(new TaskAssignDueDateOnCreation($container));
        $container['actionManager']->register(new TaskAssignColorSwimlane($container));
        $container['actionManager']->register(new TaskAssignPrioritySwimlane($container));
        $container['actionManager']->register(new TaskAssignColorOnDueDate($container));
        $container['actionManager']->register(new SubtaskTimerMoveTaskColumn($container));
        $container['actionManager']->register(new StopSubtaskTimerMoveTaskColumn($container));
        $container['actionManager']->register(new TaskMoveColumnOnStartDate($container));
        $container['actionManager']->register(new TaskAssignColorOnStartDate($container));
        $container['actionManager']->register(new TaskAssignDueDateOnMoveColumn($container));
        
        return $container;
    }
}
