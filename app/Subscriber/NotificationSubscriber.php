<?php

namespace Kanboard\Subscriber;

use Kanboard\Event\GenericEvent;
use Kanboard\Model\TaskLinkModel;
use Kanboard\Model\TaskModel;
use Kanboard\Model\CommentModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskFileModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotificationSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            TaskModel::EVENT_USER_MENTION      => 'handleEvent',
            TaskModel::EVENT_CREATE            => 'handleEvent',
            TaskModel::EVENT_UPDATE            => 'handleEvent',
            TaskModel::EVENT_CLOSE             => 'handleEvent',
            TaskModel::EVENT_OPEN              => 'handleEvent',
            TaskModel::EVENT_MOVE_COLUMN       => 'handleEvent',
            TaskModel::EVENT_MOVE_PROJECT      => 'handleEvent',
            TaskModel::EVENT_MOVE_POSITION     => 'handleEvent',
            TaskModel::EVENT_MOVE_SWIMLANE     => 'handleEvent',
            TaskModel::EVENT_MOVE_COL_AND_SWL  => 'handleEvent',
            TaskModel::EVENT_ASSIGNEE_CHANGE   => 'handleEvent',
            SubtaskModel::EVENT_CREATE         => 'handleEvent',
            SubtaskModel::EVENT_UPDATE         => 'handleEvent',
            SubtaskModel::EVENT_DELETE         => 'handleEvent',
            CommentModel::EVENT_CREATE         => 'handleEvent',
            CommentModel::EVENT_UPDATE         => 'handleEvent',
            CommentModel::EVENT_DELETE         => 'handleEvent',
            CommentModel::EVENT_USER_MENTION   => 'handleEvent',
            TaskFileModel::EVENT_CREATE        => 'handleEvent',
            TaskFileModel::EVENT_DESTROY       => 'handleEvent',
            TaskLinkModel::EVENT_CREATE_UPDATE => 'handleEvent',
            TaskLinkModel::EVENT_DELETE        => 'handleEvent',
        );
    }

    public function handleEvent(GenericEvent $event, $eventName)
    {
        $this->logger->debug('Subscriber executed: ' . __METHOD__);
        $this->queueManager->push($this->notificationJob->withParams($event, $eventName));
    }
}
