<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace  Mindme\MarkdownBundle\Listener;

use Claroline\AppBundle\Persistence\ObjectManager;
use Mindme\MarkdownBundle\Entity\Widget\SimpleMarkdownConfig;
use Claroline\CoreBundle\Event\ConfigureWidgetEvent;
use Claroline\CoreBundle\Event\CopyWidgetConfigurationEvent;
use Claroline\CoreBundle\Event\DisplayWidgetEvent;
use Mindme\MarkdownBundle\Form\SimpleMarkdownType;
use Mindme\MarkdownBundle\Manager\SimpleMarkdownManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\FormFactory;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;

/**
 * @DI\Service
 */
class SimpleMarkdownWidgetListener
{
    private $simpleMarkdownManager;
    private $formFactory;
    private $templating;
    private $om;

    /**
     * SimpleMarkdownWidgetListener constructor.
     *
     * @DI\InjectParams({
     *      "simpleMarkdownManager" = @DI\Inject("claroline.manager.simple_markdown_manager"),
     *      "formFactory"       = @DI\Inject("form.factory"),
     *      "templating"        = @DI\Inject("templating"),
     *      "om"                = @DI\Inject("claroline.persistence.object_manager")
     * })
     *
     * @param SimpleMarkdownManager $simpleMarkdownManager
     * @param FormFactory       $formFactory
     * @param TwigEngine        $templating
     * @param ObjectManager     $om
     */
    public function __construct(
        SimpleMarkdownManager $simpleMarkdownManager,
        FormFactory $formFactory,
        TwigEngine $templating,
        ObjectManager $om
    ) {
        $this->simpleMarkdownManager = $simpleMarkdownManager;
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->om = $om;
    }

    /**
     * @DI\Observe("widget_simple_markdown")
     *
     * @param DisplayWidgetEvent $event
     */
    public function onDisplay(DisplayWidgetEvent $event)
    {
        $widgetMarkdown = $this->simpleMarkdownManager->getMarkdownConfig($event->getInstance());
        $content = $this->templating->render('MindmeMarkdownBundle:Widget:SimpleMarkdown\display.html.twig', [
            'content' => $widgetMarkdown ? $widgetMarkdown->getContent() : '',
        ]);

        $event->setContent($content);
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("widget_simple_markdown_configuration")
     * @param ConfigureWidgetEvent $event
     */
    public function onConfig(ConfigureWidgetEvent $event)
    {
        $instance = $event->getInstance();
        $txtConfig = $this->simpleMarkdownManager->getMarkdownConfig($instance);

        if (null === $txtConfig) {
            $txtConfig = new SimpleMarkdownConfig();
            $txtConfig->setWidgetInstance($instance);
        }

        $form = $this->formFactory->create(new SimpleMarkdownType('widget_markdown_'.rand(0, 1000000000)), $txtConfig);
        $content = $this->templating->render(
            'MindmeMarkdownBundle:Widget:SimpleMarkdown\configure.html.twig',
            ['form' => $form->createView(), 'config' => $instance]
        );
        $event->setContent($content);
    }

    /**
     * @DI\Observe("copy_widget_config_simple_markdown")
     *
     * @param CopyWidgetConfigurationEvent $event
     */
    public function onCopyWidgetConfiguration(CopyWidgetConfigurationEvent $event)
    {
        $source = $event->getWidgetInstance();
        $copy = $event->getWidgetInstanceCopy();
        $widgetConfig = $this->simpleMarkdownManager->getMarkdownConfig($source);

        if (!is_null($widgetConfig)) {
            $widgetConfigCopy = new SimpleMarkdownConfig();
            $widgetConfigCopy->setWidgetInstance($copy);
            $content = $this->replaceResourceLinks($widgetConfig->getContent(), $event->getResourceInfos());
            $widgetConfigCopy->setContent($this->replaceTabsLinks($content, $event->getTabsInfos()));

            $this->om->persist($widgetConfigCopy);
            $this->om->flush();
        }
        $event->validateCopy();
        $event->stopPropagation();
    }

    private function replaceResourceLinks($content, $resourceInfos)
    {
        foreach ($resourceInfos['copies'] as $resource) {
            $type = $resource['original']->getResourceType()->getName();

            $content = str_replace(
                '/file/resource/media/'.$resource['original']->getId(),
                '/file/resource/media/'.$resource['copy']->getId(),
                $content
            );

            $content = str_replace(
                "/resource/open/{$type}/".$resource['original']->getId(),
                "/resource/open/{$type}/".$resource['copy']->getId(),
                $content
            );
        }

        return $content;
    }

    private function replaceTabsLinks($content, $tabsInfos)
    {
        foreach ($tabsInfos as $tabInfo) {
            $oldWsId = $tabInfo['original']->getWorkspace()->getId();
            $newWsId = $tabInfo['copy']->getWorkspace()->getId();
            $content = str_replace(
                '/workspaces/'.$oldWsId.'/open/tool/home/tab/'.$tabInfo['original']->getId(),
                '/workspaces/'.$newWsId.'/open/tool/home/tab/'.$tabInfo['copy']->getId(),
                $content
            );
        }

        return $content;
    }
}
