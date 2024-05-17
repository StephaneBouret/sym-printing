<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationFormType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController
{
    protected $em;
    protected $cartService;
    protected $persister;

    public function __construct(EntityManagerInterface $em, CartService $cartService, PurchasePersister $persister)
    {
        $this->em = $em;
        $this->cartService = $cartService;
        $this->persister = $persister;
    }

    #[Route('/purchases/confirm', name: 'purchase_confirm')]
    #[IsGranted('ROLE_USER', message: 'Vousdevez être connecté pour confirmer une commande')]
    public function confirm(Request $request)
    {
        // 1. Nous voulons lire les données du formulaire - Request
        $form = $this->createForm(CartConfirmationFormType::class);
        $form->handleRequest($request);
        // 2. Si le formulaire n'est pas soumis : redirection
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
            return $this->redirectToRoute('cart_show');
        }

        // 4. S'il n'y a pas de produits dans le panier : redirection - CartService
        $cartItems = $this->cartService->getDetailedCartItems();
        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'Vous ne pouvez pas confirmer une commande avec un panier vide');
            return $this->redirectToRoute('cart_show');
        }
        // 5. Nous voulons créer une Purchase
        /** @var Purchase */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);
        
        // 8. Nous allons enregistrer la commande - EntityManagerInterface
        $this->em->flush();
        // $this->cartService->empty();
        // $this->addFlash('success', 'La commande a bien été enregistrée');
        return $this->redirectToRoute('purchase_payment_form', [
            'id' => $purchase->getId()
        ]);
    }
}
