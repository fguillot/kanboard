<?php

namespace Kanboard\Subscriber;

use Kanboard\Event\GenericEvent;
use Kanboard\Model\TaskModel;
use Kanboard\Model\SubtaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProjectModificationDateSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            TaskModel::EVENT_CREATE_UPDATE    => 'execute',
            TaskModel::EVENT_CLOSE            => 'execute',
            TaskModel::EVENT_OPEN             => 'execute',
            TaskModel::EVENT_MOVE_SWIMLANE    => 'execute',
            TaskModel::EVENT_MOVE_COL_AND_SWL => 'execute',
            TaskModel::EVENT_MOVE_COLUMN      => 'execute',
            TaskModel::EVENT_MOVE_POSITION    => 'execute',
            TaskModel::EVENT_MOVE_PROJECT     => 'execute',
            TaskModel::EVENT_ASSIGNEE_CHANGE  => 'execute',
            SubtaskModel::EVENT_CREATE_UPDATE => 'execute',
            SubtaskModel::EVENT_DELETE        => 'execute',
        );
    }

    public function execute(GenericEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $this->projectModel->updateModificationDate($event['task']['project_id']);
    }
}
