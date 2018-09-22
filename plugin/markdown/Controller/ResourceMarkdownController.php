<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindme\MarkdownBundle\Controller;

use Claroline\CoreBundle\Entity\Resource\ResourceNode;
use Claroline\CoreBundle\Entity\Widget\ResourcesWidgetConfig;
use Claroline\CoreBundle\Entity\Widget\WidgetDisplayConfig;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;
use Claroline\CoreBundle\Form\ResourcesWidgetConfigurationType;
use Claroline\CoreBundle\Manager\ResourceManager;
use Claroline\CoreBundle\Manager\Resource\ResourceNodeManager;
use Claroline\CoreBundle\Manager\ResourcesWidgetManager;
use Claroline\CoreBundle\Manager\WidgetManager;
use Mindme\MarkdownBundle\Manager\MarkdownManager;
use Mindme\MarkdownBundle\Form\ResourceMarkdownConfigurationType;
use Mindme\MarkdownBundle\Entity\Revision;
use Mindme\MarkdownBundle\Entity\Markdown;
use Mindme\MarkdownBundle\Form\MarkdownOpenType;
use Mindme\MarkdownBundle\Entity\Widget\MarkdownDisplayConfig;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;

class ResourceMarkdownController extends Controller {

    private $formFactory;
    private $request;
    private $resourceManager;
    private $resourcesWidgetManager;
    private $translator;
    private $markdownManager;
    private $widgetManager;
    private $resourceNodeManager;

    /**
     * @DI\InjectParams({
     *     "formFactory"            = @DI\Inject("form.factory"),
     *     "requestStack"           = @DI\Inject("request_stack"),
     *     "resourceManager"        = @DI\Inject("claroline.manager.resource_manager"),
     *     "resourcesWidgetManager" = @DI\Inject("claroline.manager.resources_widget_manager"),
     *     "translator"             = @DI\Inject("translator"),
     *     "markdownManager"        = @DI\Inject("claroline.manager.markdown_manager"),
     *     "widgetManager"          = @DI\Inject("claroline.manager.widget_manager"),
     *     "resourceNodeManager"    = @DI\Inject("claroline.manager.resource_node")
     * })
     */
    public function __construct(
            FormFactory $formFactory, 
            RequestStack $requestStack, 
            ResourceManager $resourceManager, 
            ResourcesWidgetManager $resourcesWidgetManager, 
            TranslatorInterface $translator, 
            WidgetManager $widgetManager, 
            MarkdownManager $markdownManager,
            ResourceNodeManager $resourceNodeManager
    ) {
        $this->formFactory = $formFactory;
        $this->request = $requestStack->getCurrentRequest();
        $this->resourceManager = $resourceManager;
        $this->resourcesWidgetManager = $resourcesWidgetManager;
        $this->translator = $translator;
        $this->widgetManager = $widgetManager;
        $this->markdownManager = $markdownManager;
        $this->resourceNodeManager = $resourceNodeManager;
    }

    /**
     * @EXT\Route(
     *     "/resource_markdown/{widgetInstance}/configure/form",
     *     name="claro_resources_markdown_widget_configure_form",
     *     options={"expose"=true}
     * )
     * @EXT\ParamConverter("user", converter="current_user")
     * @EXT\Template("MindmeMarkdownBundle:Widget:resourceMarkdownConfigureForm.html.twig")
     */
    public function resourceMarkdownConfigureFormAction(WidgetInstance $widgetInstance) {
        $config = $this->widgetManager->getConfiguration($widgetInstance);
        $details = $config->getDetails();
        $resourceNode = (isset($details) && isset($details['nodeId'])) ? $this->resourceManager->getById($details['nodeId']) : null;
        $form = $this->formFactory->create(new ResourceMarkdownConfigurationType($resourceNode));

        return ['form' => $form->createView(), 'config' => $config];
    }

    /**
     * @EXT\Route(
     *     "/resource_markdown/{config}/configure/edit",
     *     name="claro_resources_markdown_widget_configure",
     *     options={"expose"=true}
     * )
     * @EXT\ParamConverter("user", converter="current_user")
     * @EXT\Template("MindmeMarkdownBundle:Widget:resourceMarkdownConfigureForm.html.twig")
     */
    public function resourceMarkdownSubmitFormAction(WidgetDisplayConfig $config) {
        $form = $this->formFactory->create(new ResourceMarkdownConfigurationType());
       
	$form->handleRequest($this->request);

        if ($form->isValid()) {
		$resource = $form->get('resource')->getData();

		$mode = $form->get('defaultMode')->getData();
		$widgetInstance = $config->getWidgetInstance();
                 
		$markdownDisplayConfig = $this->markdownManager->getMarkdownDisplayConfiguration($widgetInstance);


            if ($resource) {
		    $config->addDetail('nodeId', $resource->getId());
		    $config->addDetail('defaultMode',$markdownDisplayConfig);
		   $markdownDisplayConfig->setDefaultMode($mode);

            } else {
		    $config->removeDetail('nodeId');
		    $config->removeDetail('defaultMode');
            }

            $this->markdownManager->persistMarkdownDisplayConfiguration($markdownDisplayConfig);
             $this->widgetManager->persistConfiguration($config);

            return new JsonResponse('success', 204);
        }


        return ['form' => $form->createView(), 'config' => $config];
    }

    /**
     *      * @EXT\Route(
     *     "/resource_markdown/{widgetInstance}",
     *     name="claro_resources_widget",
     *     options={"expose"=true}
     * )
     *
     * @EXT\Template("MindmeMarkdownBundle:Widget:resourceMarkdownDisplay.html.twig")
     */
    public function displayResourceMarkdownWidgetAction(WidgetInstance $widgetInstance) {


        $config = $this->widgetManager->getConfiguration($widgetInstance);

        $markdownDisplayConfig = $this->markdownManager->getMarkdownDisplayConfiguration($widgetInstance);
	$defaultMode = $markdownDisplayConfig->getDefaultMode();


          


        $details = $config->getDetails();



        if (isset($details) && isset($details['nodeId'])) {
            $resourceNode = $this->resourceManager->getById($details['nodeId']);
	    $markdown = $this->resourceManager->getResourceFromNode($resourceNode);
             
	    error_log($this->markdownManager->getLastContentRevision($markdown),3,"/tmp/mynew1111111111.log",true);
	    error_log($this->markdownManager->getLastHtmlcontentRevision($markdown),3,"/tmp/mynew1111111111.log",true);

	    if ($defaultMode == 2){
                return $this->render('MindmeMarkdownBundle:Widget:resourceMarkdownTocDisplay.html.twig',
		       ['markdown' => $this->markdownManager->getLastContentRevision($markdown),
		       'htmlcontent' => $this->markdownManager->getLastHtmlcontentRevision($markdown)]);
	    }

	    if ($defaultMode == 0) {
		  return ['markdown' => $this->markdownManager->getLastContentRevision($markdown),
			  'htmlcontent' => $this->markdownManager->getLastHtmlcontentRevision($markdown)];

	    }

	    return $this->render('MindmeMarkdownBundle:Widget:resourceMarkdownPPTDisplay.html.twig',
		   ['markdown' => $this->markdownManager->getLastContentRevision($markdown),
    		   'htmlcontent' => $this->markdownManager->getLastHtmlcontentRevision($markdown) ]);
	}


        return ['markdown' => ''];
    }
}
