<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService
{
    private $entityClass;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;
    private $limit = 10;
    private $currentPage = 1;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath)
    {
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');    
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getData()
    {
        if(empty($this->entityClass)) {
            throw new Exception("Youd didn't specify the entity name for pagination!, 
            Use method setEntityClass of your pagination service");
        }

        // Caluler l'offset
        // 0 = 1 * 10 - 10
        // 10 = 2 * 10 - 10
        $offset = $this->currentPage * $this->limit - $this->limit;

        // Demander au repository de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        // Renvoyez ces éléments
        return $data;
    }

    public function getPages()
    {
        if(empty($this->entityClass)) {
            throw new Exception("Youd didn't specify the entity name for pagination!, 
            Use method setEntityClass of your pagination service");
        }
        
        // connaitre le total des enregistrements de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        // faire la division par la limite de chaque page (arrondie)
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route,
        ]);
    }

}