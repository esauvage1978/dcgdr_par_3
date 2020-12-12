<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index()
    {
        $general_entries = [

            [
                'name' => 'Organisme',
                'route' => 'organisme_list',
                'content' => 'Gestion des organismes',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-building text-p-dark'
            ],
            [
                'name' => 'Corbeille',
                'route' => 'corbeille_list',
                'content' => 'Gestion des corbeilles',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-boxes text-p-dark'
            ],
            [
                'name' => 'Utilisateurs',
                'route' => 'user_list',
                'content' => 'Gestion des utilisateurs',
                'smallcontent' => 'réservé à l\'administrateur',
                'icon' => 'fa fa-users text-p-dark'
            ],
            [
                'name' => 'Informations générales',
                'route' => 'gpi_list',
                'content' => 'Gestion des données affichées sur les pages',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-bullhorn text-p-dark'
            ]

        ];

        $app_entries = [
            [
                'name' => 'Plan d\'actions',
                'route' => 'axe_list',
                'content' => 'Gestion des plan d\'actions de la DCGDR',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-compress-arrows-alt text-p-dark'
            ],
            [
                'name' => 'Pôle',
                'route' => 'pole_list',
                'content' => 'Gestion des pôles',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-parking text-p-dark'
            ],
            [
                'name' => 'Thématique',
                'route' => 'thematique_list',
                'content' => 'Gestion des thématiques',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fab fa-tumblr text-p-dark'
            ],
            [
                'name' => 'Catégorie',
                'route' => 'category_list',
                'content' => 'Gestion des catégories',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'far fa-copyright text-p-dark'
            ],
            [
                'name' => 'Cible',
                'route' => 'cible_list',
                'content' => 'Gestion des cibles',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fas fa-bullseye text-p-dark'
            ],
            [
                'name' => 'Vecteur',
                'route' => 'vecteur_list',
                'content' => 'Gestion des vecteurs',
                'smallcontent' => 'Réservé au gestionnaire',
                'icon' => 'fab fa-vuejs text-p-dark'
            ],                       
    ];



        $action_entries = [
           
        ];

        return $this->render('admin/index.html.twig', [
            'general_entries' => $general_entries,
            'app_entries' => $app_entries,
            'action_entries' => $action_entries,
        ]);
    }
}
