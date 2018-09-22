<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindme\MarkdownBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;

/**
 * @ORM\Table(name="mindme_markdown")
 * @ORM\Entity(repositoryClass="Mindme\MarkdownBundle\Repository\MarkdownRepository")
 */
class Markdown extends AbstractResource
{
    const MODE_NORMAL = 0;
    const MODE_LAB = 1;
    const MODE_NOTE = 2;
    const MODE_PPT = 3;
    /**
     * @ORM\Column(type="integer")
     */
    protected $version;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Mindme\MarkdownBundle\Entity\Revision",
     *     mappedBy="markdown",
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"version" = "DESC"})
     */
    protected $revisions;

    /** @var string */
    protected $markdown;
    
   /**
     * @ORM\Column(name="default_mode", nullable=false, type="integer", options={"default" = 0})
     */
    protected $defaultMode = self::MODE_NORMAL;

    public function __construct()
    {
        $this->version = 1;
        $this->revisions = new ArrayCollection();
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function getRevisions()
    {
        return $this->revisions;
    }
      
    public function addRevision($revision)
    {
        $this->revisions->add($revision);
    }

    public function removeRevision($revision)
    {
        $this->revisions->removeElement($revision);
    }
    

    /**
     * Required for the formtype.
     *
     * @param string $markdwon
     */
    public function setMarkdown($markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * Required for the formtype.
     *
     * @return string
     */
    public function getMarkdown()
    {
        return $this->markdown;
    }

    /**
     * Get the current content of the Resource.
     *
     * @return string
     */
    public function getContent()
    {
        $content = null;
        if (!empty($this->revisions)) {
            $content = $this->revisions->get(0)->getContent();
        }
 
        return $content;
    }
   
    /**
     * Get the current content of the Resource.
     *
     * @return string
     */
      public function getHtmlContent()
    {
       $htmlcontent = null;
       if (!empty($this->revisions)) {
           $htmlcontent = $this->revisions->get(0)->getHtmlContent();
        }

          return $htmlcontent;
     }
     
    public function getDefaultMode()
    {
        return $this->defaultMode;
    }

    public function setDefaultMode($defaultMode)
    {
        $this->defaultMode = $defaultMode;
    }

}
