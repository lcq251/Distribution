<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\ResultBundle\Controller;

use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Form\Handler\FormHandler;
use Claroline\ResultBundle\Entity\Result;
use Claroline\ResultBundle\Manager\ResultManager;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @EXT\Route(requirements={"id"="\d+", "abilityId"="\d+"}, options={"expose"=true})
 * @EXT\Method("GET")
 */
class ResultController
{
    private $manager;
    private $formHandler;

    /**
     * @DI\InjectParams({
     *     "manager" = @DI\Inject("claroline.result.result_manager"),
     *     "handler" = @DI\Inject("claroline.form_handler"),
     *     "checker" = @DI\Inject("security.authorization_checker")
     * })
     *
     * @param ResultManager                 $manager
     * @param FormHandler                   $handler
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(
        ResultManager $manager,
        FormHandler $handler,
        AuthorizationCheckerInterface $checker
    )
    {
        $this->manager = $manager;
        $this->formHandler = $handler;
        $this->checker = $checker;
    }

    /**
     * @EXT\Route("/{id}", name="claroline_open_result")
     * @EXT\ParamConverter("user", converter="current_user")
     * @EXT\Template
     *
     * @param Result $result
     * @return array
     */
    public function resultAction(Result $result, User $user)
    {
        if (!$this->checker->isGranted('OPEN', $result)) {
            throw new AccessDeniedException();
        }

        $canEdit = $this->checker->isGranted('EDIT', $result);

        return [
            '_resource' => $result,
            'marks' => $this->manager->getMarks($result, $user, $canEdit),
            'users' => $this->manager->getUsers($result, $canEdit)
        ];
    }

    /**
     * @param Request   $request
     * @param Result    $result
     */
    public function addUserAction(Request $request, Result $result)
    {

    }
}
