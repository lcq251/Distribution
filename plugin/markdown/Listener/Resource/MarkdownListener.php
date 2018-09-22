<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindme\MarkdownBundle\Listener\Resource;

use Mindme\MarkdownBundle\Entity\Revision;
use Mindme\MarkdownBundle\Entity\Markdown;
use Claroline\CoreBundle\Event\CopyResourceEvent;
use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\Resource\LoadResourceEvent;
use Mindme\MarkdownBundle\Form\MarkdownType;
use Claroline\ScormBundle\Event\ExportScormResourceEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @DI\Service
 */
class MarkdownListener implements ContainerAwareInterface {

    /** @var ContainerInterface */
    private $container;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container")
     * })
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * @DI\Observe("create_form_markdown")
     *
     * @param CreateFormResourceEvent $event
     */
    public function onCreateForm(CreateFormResourceEvent $event) {
        
        $formFactory = $this->container->get('form.factory');
        $markdownType = new MarkdownType('markdown_' . rand(0, 1000000000));
        $form = $formFactory->create($markdownType);
        $response = $this->container->get('templating')->render(
         'MindmeMarkdownBundle:Markdown:createForm.html.twig', [
            'form' => $form->createView(),
            'resourceType' => 'markdown',
                ]
        );  
        $event->setResponseContent($response);
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("create_markdown")
     *
     * @param CreateResourceEvent $event
     */
    public function onCreate(CreateResourceEvent $event) {
        
        $request = $this->container->get('request');
        $em = $this->container->get('doctrine.orm.entity_manager');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $keys = array_keys($request->request->all());
        $id = array_pop($keys);
        $form = $this->container->get('form.factory')->create(new MarkdownType($id));
        $form->handleRequest($request);
 
        if ($form->isValid()) {
        
            $varArray = $form->getData();
             
            $published = $form->get('published')->getData();
            $event->setPublished($published);
            $revision = new Revision();
            $revision->setContent($varArray[content]);
            $revision->setHtmlcontent($varArray[htmlcontent]);
            $revision->setUser($user);
            $markdown = new Markdown();
            $markdown->setName($varArray[name]);
            $markdown->setDefaultMode($varArray[defaultMode]);
            
            $revision->setMarkdown($markdown);
            $em->persist($markdown);
            $em->persist($revision);
            $event->setResources([$markdown]);
            $event->stopPropagation();

            return;
        }

        $errorForm = $this->container->get('form.factory')->create(new MarkdownType('markdown_' . rand(0, 1000000000)));
        $errorForm->setData($form->getData());
        $children = $form->getIterator();
        $errorChildren = $errorForm->getIterator();

        foreach ($children as $key => $child) {
            $errors = $child->getErrors();
            foreach ($errors as $error) {
                $errorChildren[$key]->addError($error);
            }
        }

        $content = $this->container->get('templating')->render(
                'MindmeMarkdownBundle:Markdown:createForm.html.twig', [
            'form' => $errorForm->createView(),
            'resourceType' => 'markdown',
                ]
        );
        $event->setErrorFormContent($content);
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("copy_markdown")
     *
     * @param CopyResourceEvent $event
     */
    public function onCopy(CopyResourceEvent $event) {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $resource = $event->getResource();
        $revisions = $resource->getRevisions();
        $copy = new Markdown();
        $copy->setVersion($resource->getVersion());

        foreach ($revisions as $revision) {
            $rev = new Revision();
            $rev->setVersion($revision->getVersion());
            $rev->setContent($revision->getContent());
            $rev->setUser($revision->getUser());
            $rev->setMarkdown($copy);
            $em->persist($rev);
        }

        $event->setCopy($copy);
    }

    /**
     * Loads a Markdown resource.
     *
     * @DI\Observe("load_markdown")
     *
     * @param LoadResourceEvent $event
     */
    public function onLoad(LoadResourceEvent $event) {
        $event->setAdditionalData([
            'markdown' => $this->container->get('claroline.api.serializer')->serialize($event->getResource()),
        ]);

        $event->stopPropagation();
    }

    /**
     * @DI\Observe("open_markdown")
     *
     * @param OpenResourceEvent $event
     */
    public function onOpen(OpenResourceEvent $event) {
        $markdown = $event->getResource();
        $defaultmode = $markdown->getDefaultMode();

        switch ($defaultmode) {

            case 1:
                $content = $this->container->get('templating')->render(
                        'MindmeMarkdownBundle:Markdown:index_lab.html.twig', [
                    'markdown' => $markdown,
                    '_resource' => $markdown,
                ]);

                break;
            case 2:
                $content = $this->container->get('templating')->render(
                        'MindmeMarkdownBundle:Markdown:index_note.html.twig', [
                    'markdown' => $markdown,
                    '_resource' => $markdown,
                ]);
                break;
            case 3:
                $content = $this->container->get('templating')->render(
                        'MindmeMarkdownBundle:Markdown:index_ppt.html.twig', [
                    'markdown' => $markdown,
                    '_resource' => $markdown,
                ]);
                break;
            default:
                $content = $this->container->get('templating')->render(
                        'MindmeMarkdownBundle:Markdown:index.html.twig', [
                    'markdown' => $markdown,
                    '_resource' => $markdown,
                ]);
        }

        $response = new Response($content);
        $event->setResponse($response);
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("export_scorm_markdown")
     *
     * @param ExportScormResourceEvent $event
     */
    public function onExportScorm(ExportScormResourceEvent $event) {
        $markdown = $event->getResource();
        $revisionRepo = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('MindmeMarkdownBundle:Revision');

        $markdownContent = $revisionRepo->getLastRevision($markdown)->getContent();
        $parsed = $this->container->get('claroline.scorm.rich_text_exporter')->parse($markdownContent);

        $template = $this->container->get('templating')->render(
                'MindmeMarkdown:Markdown:scorm-export.html.twig', [
            'markdown' => $parsed['markdown'],
            '_resource' => $markdown,
                ]
        );

        // Set export template
        $event->setTemplate($template);
        $event->setEmbedResources($parsed['resources']);

        $event->stopPropagation();
    }

    /**
     * @DI\Observe("delete_markdown")
     *
     * @param DeleteResourceEvent $event
     */
    public function onDelete(DeleteResourceEvent $event) {
        $event->stopPropagation();
    }

}
