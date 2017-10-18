<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CoreBundle\Security\Voter;

use Claroline\CoreBundle\Security\AbstractVoter;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * @DI\Service
 * @DI\Tag("security.voter")
 */
class OrganizationVoter extends AbstractVoter
{
    public function checkPermission(TokenInterface $token, $object, array $attributes, array $options)
    {
        if (!$object->getWorkspace()) {
            return $this->hasAdminToolAccess($token, 'organization_management') ?
            VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
        }

        //not used in workspaces yet so no implementation
        return VoterInterface::ACCESS_ABSTAIN;
    }

    public function getClass()
    {
        return 'Claroline\CoreBundle\Organization\Organization';
    }

    public function getSupportedActions()
    {
        return[self::CREATE, self::EDIT, self::DELETE, self::PATCH];
    }
}
