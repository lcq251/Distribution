<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindme\MarkdownBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Mindme\MarkdownBundle\Entity\Markdown;

class RevisionRepository extends EntityRepository
{
    /**
     * Returns the last revision of a markdown.
     *
     * @param Markdown $markdown
     *
     * @return Revision
     */
    public function getLastRevision(Markdown $markdown)
    {
        $dql = "
            SELECT r FROM Mindme\MarkdownBundle\Entity\Revision r
            JOIN r.markdown t2
            WHERE r.version = (SELECT MAX(r2.version) FROM Mindme\MarkdownBundle\Entity\Revision r2
            JOIN r2.markdown t WHERE t.id = {$markdown->getId()})
            and t2.id = {$markdown->getId()}
        ";
	    $query = $this->_em->createQuery($dql);



        return $query->getSingleResult();
    }
}
