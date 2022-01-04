<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DocumentationController extends AbstractController
{
    /**
     * @Route("/documentation", name="documentation")
     */
    public function index()
    {
        //les fichiers sont à déposer dans public/doc
        $entries = [
            [
                'name' => 'Votre&nbsp;compte',
                'url' => 'profil',
                'icone' => 'fa fa-user'
            ],
            [
                'name' => 'Recherche',
                'url' => 'recherche',
                'icone' => 'fas fa-search'
            ],
            [
                'name' => 'Accueil',
                'url' => 'home',
                'icone' => 'fas fa-home'
            ],   
            [
                'name' => 'Tableau de bord',
                'url' => 'dashboard',
                'icone' => 'fas fa-tachometer-alt'
            ],    
            
            [
                'name' => 'Gérer&nbsp;les&nbsp;actions',
                'url' => 'action_crud',
                'icone' => 'fas fa-bolt'
            ],            
            [
                'name' => 'Administration',
                'url' => 'admin',
                'icone' => 'fas fa-wrench'
            ],                                   

            [
                'name' => 'Modifier&nbsp;un&nbsp;porte-document',
                'url' => 'backpack_edit',
                'icone' => 'fas fa-suitcase'
            ],
            [
                'name' => 'Un problème ?',
                'url' => 'alert',
                'icone' => 'fas fa-exclamation-triangle'
            ]            
        ];

        return $this->render('documentation/index.html.twig', [
            'entries' => $entries,
        ]);
    }

    /**
     * @Route("/documentation/{data}", name="documentation_show_page")
     */
    public function showPage(string $data)
    {
        $msgOK = "<span class='alert alert-success'>" . $data . " </span>";
        $msgK0 = "<span class='alert alert-danger'>" . $data . "</span>";

        return $this->json([
            'code' => 200,
            'value' => $this->renderView('documentation/' . $data . '.html.twig'),
            'message' => 'données transmises'
        ], 200);

        return $this->render('documentation/' . $data . '.html.twig');
    }
}
