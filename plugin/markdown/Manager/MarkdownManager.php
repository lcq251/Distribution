<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindme\MarkdownBundle\Manager;

use Claroline\AppBundle\Persistence\ObjectManager;
use Mindme\MarkdownBundle\Entity\Revision;
use Mindme\MarkdownBundle\Entity\Markdown;
use Mindme\MarkdownBundle\Entity\Widget\MarkdownDisplayConfig;
use Claroline\CoreBundle\Manager\UserManager;
use Mindme\MarkdownBundle\Event\Log\LogEditResourceMarkdownEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;

/**
 * @DI\Service("claroline.manager.markdown_manager")
 */
class MarkdownManager
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var ObjectManager */
    private $om;

    /** @var UserManager */
    private $userManager;

    /** @var markdownDisplayConfigRepo */
    private $markdownDisplayConfigRepo;

    /**
     * @DI\InjectParams({
     *     "eventDispatcher" = @DI\Inject("event_dispatcher"),
     *     "om"              = @DI\Inject("claroline.persistence.object_manager"),
     *     "userManager"     = @DI\Inject("claroline.manager.user_manager"),
     *
     * })
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param ObjectManager            $om
     * @param UserManager              $userManager
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ObjectManager $om,
        UserManager $userManager
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->om = $om;
	$this->userManager = $userManager;

	$this->markdownDisplayConfigRepo = $om->getRepository('MindmeMarkdownBundle:Widget\MarkdownDisplayConfig');
    }

    public function create($content, $htmlcontent, $title, User $user = null)
    {
        $revision = new Revision();
	$revision->setContent($content);
	$revision->setHtmlcontent($htmlcontent);
        $revision->setUser($user);
        $markdown = new Markdown();
        $markdown->setName($title);
        $revision->setMarkdown($Markdown);
        $this->om->persist($Markdown);
        $this->om->persist($revision);
        $this->om->flush();

        return $markdown;
    }

    public function getLastContentRevision(Markdown $markdown)
    {
        $revisionRepo = $this->om->getRepository('MindmeMarkdownBundle:Revision');

        return $revisionRepo->getLastRevision($markdown)->getContent();
    }
    public function getLastHtmlcontentRevision(Markdown $markdown)
    {
         $revisionRepo = $this->om->getRepository('MindmeMarkdownBundle:Revision');

         return $revisionRepo->getLastRevision($markdown)->getHtmlcontent();
     }


    public function createRevision(Markdown $markdown, $content, $htmlcontent, User $user = null)
    {
        $version = $markdown->getVersion() + 1;

        $revision = new Revision();
	$revision->setContent($content);
	$revision->setHtmlcontent($htmlcontent);
        $revision->setUser($user);
        $revision->setMarkdown($markdown);
        $revision->setVersion($version);
        $markdown->setVersion($version);
        $this->om->persist($revision);
        $this->om->persist($markdown);
        $this->om->flush();

        $workspace = $markdown->getResourceNode()->getWorkspace();
        $usersToNotify = $workspace ?
            $this->userManager->getUsersByWorkspaces([$workspace], null, null, false) :
            [];
        $event = new LogEditResourceMarkdownEvent($Markdown->getResourceNode(), $usersToNotify);
        $this->eventDispatcher->dispatch('log', $event);

        return $revision;
    }

     public function getMarkdownDisplayConfiguration(WidgetInstance $widgetInstance)
     {
        $config = $this->markdownDisplayConfigRepo->findOneBy(['widgetInstance' => $widgetInstance->getId()]);

        if (is_null($config)) {
        $config = new MarkdownDisplayConfig();
        $config->setWidgetInstance($widgetInstance);
        $this->persistMarkdownDisplayConfiguration($config);
      }

         return $config;
     }

       public function persistMarkdownDisplayConfiguration(MarkdownDisplayConfig $config)
       {
         $this->om->persist($config);
         $this->om->flush();
       }




}
