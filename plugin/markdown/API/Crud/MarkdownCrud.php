<?php

namespace Mindme\MarkdownBundle\API\Crud;

use Mindme\MarkdownBundle\Manager\MarkdownManager;
use Claroline\AppBundle\API\Options;
use Claroline\AppBundle\Event\Crud\CreateEvent;
use Claroline\AppBundle\Event\Crud\DeleteEvent;
use Claroline\AppBundle\Event\Crud\UpdateEvent;
use Claroline\AppBundle\Event\StrictDispatcher;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("claroline.crud.markdown")
 * @DI\Tag("claroline.crud")
 */
class MarkdownCrud
{
    /**
     * MarkdownManager constructor.
     *
     * @DI\InjectParams({
     *     "eventDispatcher" = @DI\Inject("claroline.event.event_dispatcher"),
     *     "manager"         = @DI\Inject("claroline.manager.markdown_manager")
     * })
     *
     * @param StrictDispatcher $eventDispatcher
     */
    public function __construct(
        StrictDispatcher $eventDispatcher,
        MarkdownManager $manager
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->manager = $manager;
    }

    /**
     * @DI\Observe("crud_pre_create_object_mindme_markdownbundle_entity_markdown")
     *
     * @param CreateEvent $event
     */
    public function preCreate(CreateEvent $event)
    {
        $markdown = $event->getObject();
        $options = $event->getOptions();


        if (!in_array(Options::NO_LOG, $options)) {
            $this->eventDispatcher->dispatch(
                'log',
                'Mindme\\MarkdownBundle\\Event\\Log\\LogMarkdownCreateEvent',
                [$markdown->getAggregate(), $markdown]
            );
        }
    }

    /**
     * @DI\Observe("crud_post_update_object_claroline_markdownbundle_entity_markdown")
     *
     * @param CreateEvent $event
     */
    public function postUpdate(UpdateEvent $event)
    {
        $markdown = $event->getObject();

        $this->eventDispatcher->dispatch(
            'log',
            'Claroline\\MarkdownBundle\\Event\\Log\\LogMarkdownEditEvent',
            [$markdown->getAggregate(), $markdown]
        );
    }

    /**
     * @DI\Observe("crud_pre_delete_object_claroline_markdownbundle_entity_markdown")
     *
     * @param CreateEvent $event
     */
    public function preDelete(DeleteEvent $event)
    {
        $markdown = $event->getObject();
        // delete scheduled task is any
        $this->manager->unscheduleMessage($markdown);

        $this->eventDispatcher->dispatch(
            'log',
            'Claroline\\MarkdownBundle\\Event\\Log\\LogMarkdownDeleteEvent',
            [$markdown->getAggregate(), $markdown]
        );
    }
}
