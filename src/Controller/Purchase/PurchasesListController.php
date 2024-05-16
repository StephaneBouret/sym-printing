<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PurchasesListController extends AbstractController
{
    #[Route('/purchases', name: 'purchase_index')]
    #[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour accéder à vos commandes')]
    public function index(): Response
    {
        // 1. Nous devons nous assurer que la personne est connectée sinon redirection vers la page d'accueil
        // 2. Nous voulons savoir qui est connecté ?
        /** @var User */
        $user = $this->getUser();
        // 3. Nous vous passer l'utilisateur à Twig afin d'afficher ses commandes 
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}
