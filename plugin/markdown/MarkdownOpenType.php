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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class MarkdownOpenType extends AbstractType
{
    private $formName;

    public function __construct($formName = null)
    {
        $this->formName = $formName;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

          $builder->add('content', 'textarea', ['label' => 'markdown','attr' => ['class' => 'editormd-markdown-textarea']]);
          $builder->add('htmlcontent', 'textarea', ['label' => 'htmlcontent','attr' => ['class' => 'editormd-html-textarea']]);
          
           $builder->add(
           'defaultMode',
           'choice',
	   [
                 'multiple' => false,
	          'choices' => [
				  Markdown::MODE_NORMAL => 'NORMAL',
                                  Markdown::MODE_LAB => 'LAB',
                                  Markdown::MODE_NOTE => 'NOTE',
                                              ],
                  'label' => 'default_mode',
	  ]
        );
    }

    public function getName()
    {
        return $this->formName;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'platform',
            ]
        );
    }
}
