<?php

namespace Kanboard\Subscriber;

use Kanboard\Event\TaskEvent;
use Kanboard\Model\TaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProjectDailySummarySubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            TaskModel::EVENT_CREATE_UPDATE    => 'execute',
            TaskModel::EVENT_CLOSE            => 'execute',
            TaskModel::EVENT_OPEN             => 'execute',
            TaskModel::EVENT_MOVE_COLUMN      => 'execute',
            TaskModel::EVENT_MOVE_SWIMLANE    => 'execute',
            TaskModel::EVENT_MOVE_COL_AND_SWL => 'execute',
        );
    }

    public function execute(TaskEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $this->queueManager->push($this->projectMetricJob->withParams($event['task']['project_id']));
    }
}
