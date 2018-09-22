<?php

namespace Mindme\MarkdownBundle\API\Serializer;

use Claroline\AppBundle\API\Serializer\SerializerTrait;
use Mindme\MarkdownBundle\Entity\Markdown;
use Mindme\MarkdownBundle\Manager\MarkdownManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @DI\Service("claroline.serializer.markdown")
 * @DI\Tag("claroline.serializer")
 */
class MarkdownSerializer
{
    use SerializerTrait;

    /** @var MarkdownManager */
    private $manager;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * MarkdownSerializer constructor.
     *
     * @DI\InjectParams({
     *     "manager"      = @DI\Inject("claroline.manager.markdown_manager"),
     *     "tokenStorage" = @DI\Inject("security.token_storage")
     * })
     *
     * @param MarkdownManager           $manager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(MarkdownManager $manager, TokenStorageInterface $tokenStorage)
    {
        $this->manager = $manager;
	$this->tokenStorage = $tokenStorage;

    }

    public function getSchema()
    {
        return '#/plugin/markdown/markdown.json';
    }

    /**
     * Serializes a Markdown resource entity for the JSON api.
     *
     * @param Markdown $markdown
     */
    public function serialize(Markdown $markdown)
    {
        return [
            'id' => $markdown->getId(),  
             'defaultMode' => $markdown->getDefaultMode(),
	    'content' => $markdown->getContent(),
	    'htmlcontent' => $markdown->getHtmlcontent(),
            'meta' => [
                'version' => $markdown->getVersion(),
            ],
        ];
    }

    /**
     * @param array $data
     * @param Markdown  $markdown
     *
     * @return Markdown
     */
    public function deserialize($data, Markdown $markdown)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $revision = $this->manager->createRevision($markdown, $data['content'],$data['htmlcontent'], $user === 'anon.' ? null : $user);

        return $revision->getMarkdown();
    }
}
