<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Service\SendMailService;
use App\Event\PurchaseSuccessEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseEmailSuccessSubscriber implements EventSubscriberInterface
{
    protected $sendMail;
    protected $security;

    public function __construct(SendMailService $sendMail, Security $security)
    {
        $this->sendMail = $sendMail;
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        // 1. Récupérer l'utilisateur actuellement en ligne (service: Security)
        /** @var User */
        $currentUser = $this->security->getUser();

        // 2. Récupérer la commande (je la trouve dans PurchaseSuccessEvent)
        $purchase = $purchaseSuccessEvent->getPurchase();

        // 3. Envoyer le mail (service: SendMailService)
        $this->sendMail->sendEmail(
            "no-reply@monsite.net",
            "Votre commande",
            $currentUser->getEmail(),
            "Bravo votre commande n°{$purchase->getId()} a bien été confirmée",
            "purchase_success",
            [
                'purchase' => $purchase,
                'user' => $currentUser,
            ]
        );
    }
}
