<?php

namespace Mindme\MarkdownBundle\Controller;

use Claroline\AppBundle\Annotations\ApiMeta;
use Claroline\AppBundle\Controller\AbstractCrudController;
use Claroline\CoreBundle\Entity\Resource\ResourceNode;
use mindme\MarkdownBundle\Entity\Markdown;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @ApiMeta(
 *     class="Mindme\MarkdownBundle\Entity\Markdown",
 *     ignore={ "exist", "list", "copyBulk", "deleteBulk", "schema", "find", "get"}
 * )
 * @EXT\Route("resource_markdown")
 */

class MarkdownController extends AbstractCrudController
{
    /**
     * @EXT\Route(
     *     "/{id}/content",
     *     name="apiv2_resource_markdown_content"
     * )
     *
     * @param ResourceNode $resourceNode
     *
     * @return string
     */
    public function getContentAction(ResourceNode $resourceNode)
    {
        /** @var Markdown $markdown */
        $markdown = $this->om->getRepository($resourceNode->getClass())->findOneBy([
            'resourceNode' => $resourceNode,
        ]);

        if (empty($markdown)) {
            throw new NotFoundHttpException();
        }

        return new Response(
            $markdown->getContent()
        );
    }
    
      public function getName()
    {
        return 'resource_markdown';
    }
}

