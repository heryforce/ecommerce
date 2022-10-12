<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout($stripeSK, CartService $cs)
    {
        \Stripe\Stripe::setApiKey($stripeSK);
        $cartWithData = $cs->getCartWithData($stripeSK);
        $lineItemsArr = [];

        foreach ($cartWithData as $item) {
            $lineItemsArr[] = [
                'price' => $item['id'],
                'quantity' => $item['quantity']
            ];
        }
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                $lineItemsArr
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('checkout_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $cs->empty();
        return $this->redirect($session->url, 303);
    }

    /**
     * @Route("/checkout/success", name="checkout_success")
     */
    public function checkout_success()
    {
        return $this->render("/checkout/success.html.twig");
    }

    /**
     * @Route("/checkout/cancel", name="checkout_cancel")
     */
    public function checkout_cancel()
    {
        return $this->render("/checkout/cancel.html.twig");
    }
}
