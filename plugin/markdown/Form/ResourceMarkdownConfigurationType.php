<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindme\MarkdownBundle\Form;

use Claroline\CoreBundle\Entity\Resource\ResourceNode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Mindme\MarkdownBundle\Entity\Widget\MarkdownDisplayConfig;

class ResourceMarkdownConfigurationType extends AbstractType
{
    public function __construct(ResourceNode $resourceNode = null)
    {
        $this->resourceNode = $resourceNode;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add(
           'defaultMode',
           'choice',
	   [
                 'multiple' => false,
	          'choices' => [
				  MarkdownDisplayConfig::MODE_NORMAL => 'NORMAL',
                                  MarkdownDisplayConfig::MODE_PPT => 'PPT',
                                  MarkdownDisplayConfig::MODE_TOC => 'TOC',
                                              ],
                  'label' => 'default_mode',
	  ]
        );
 


        $builder->add(
            'resource',
            'resourcePicker',
            [
                'data' => $this->resourceNode ? $this->resourceNode->getId() : null,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'data-is-picker-multi-select-allowed' => '0',
                    'data-is-directory-selection-allowed' => '0',
                    'data-allow-root-selection' => '0',
                    'data-type-white-list' => 'markdown',
                ],
                'display_view_button' => false,
                'display_browse_button' => true,
                'display_download_button' => false,
            ]
        );

    }

    public function getName()
    {
        return 'resources_markdown_widget_configuration_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['translation_domain' => 'platform']);
    }
}
