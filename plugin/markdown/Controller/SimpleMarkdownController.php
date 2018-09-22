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

use Mindme\MarkdownBundle\Entity\Widget\SimpleMarkdownConfig;
use Mindme\MarkdownBundle\Entity\Markdown;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;
use Mindme\MarkdownBundle\Form\SimpleMarkdownType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SimpleMarkdownController extends Controller
{
    /**
     * @EXT\Route(
     *     "/mindme_markdown/simple_markdown_update/config/{widget}",
     *     name="claro_simple_markdown_update_config",
     * )
     * @EXT\Method("POST")
     */
    public function updateSimpleMarkdownWidgetConfig(WidgetInstance $widget, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('edit', $widget)) {
            throw new AccessDeniedException();
        }

        $simpleMarkdownConfig = $this->get('claroline.manager.simple_markdown_manager')->getMarkdownConfig($widget);
        //wtf !
        $keys = array_keys($request->request->all());
        $id = array_pop($keys);
        $form = $this->get('form.factory')->create(new SimpleMarkdownType($id));
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $formDatas = $form->get('content')->getData();
            $content = is_null($formDatas) ? '' : $formDatas;

            if ($simpleMarkdownConfig) {
                $simpleMarkdownConfig->setContent($content);
            } else {
                $simpleMarkdownConfig = new SimpleMarkdownConfig();
                $simpleMarkdownConfig->setWidgetInstance($widget);
                $simpleMarkdownConfig->setContent($content);
            }
        } else {
            $simpleMarkdownConfig = new SimpleMarkdownConfig();
            $simpleMarkdownConfig->setWidgetInstance($widget);
            $errorForm = $this->container->get('form.factory')
                ->create(new SimpleMarkdownType('widget_markdown_'.rand(0, 1000000000)), $simpleMarkdownConfig);
            $errorForm->setData($form->getData());
            $children = $form->getIterator();
            $errorChildren = $errorForm->getIterator();

            foreach ($children as $key => $child) {
                $errors = $child->getErrors();

                foreach ($errors as $error) {
                    $errorChildren[$key]->addError($error);
                }
            }

            return $this->render(
                'MindmeMarkdownBundle:Widget:SimpleMarkdown\configure.html.twig',
                [
                    'form' => $errorForm->createView(),
                    'config' => $widget,
                ]
            );
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($simpleMarkdownConfig);
        $em->flush();

        return new Response('success', 204);
    }
}
