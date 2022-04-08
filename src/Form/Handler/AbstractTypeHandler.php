<?php

/**
 * Created by PhpStorm.
 * User: SAM Johnny
 * Date: 22/03/2022
 * Time: 18:12
 */

namespace App\Form\Handler;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTypeHandler
{
    /**
     * @var FormInterface
     */
    protected FormInterface $form;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var ManagerRegistry
     */
    protected ManagerRegistry $manager;

    public function __construct(FormInterface $form, Request $request, ManagerRegistry $manager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function setForm(FormInterface $form): void
    {
        $this->form = $form;
    }

    public function process(): bool
    {
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->onSuccess();
            return true;
        }
        return false;
    }

    abstract public function onSuccess();
}
