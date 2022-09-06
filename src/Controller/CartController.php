<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/{_locale}/cart", name="cart")
     */
    public function index(CartService $cs): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cs->getFilledCart(),
            'total' => $cs->getTotal()
        ]);
    }

    /**
     * @Route("/{_locale}/cart/add/{id}", name="cart_add")
     */
    public function add($id, CartService $cs)
    {
        $cs->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/{_locale}/cart/delete/{id}", name="cart_delete")
     */
    public function delete($id, CartService $cs)
    {
        $cs->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/{_locale}/cart/decrement/{id}", name="cart_decrement")
     */
    public function decrement($id, CartService $cs)
    {
        $cs->decrement($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/{_locale}/cart/empty", name="cart_empty")
     */
    public function empty(CartService $cs)
    {
        $cs->empty();
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/{_locale}/cart/pay", name="cart_pay")
     */
    public function pay(CartService $cs)
    {
        $cs->empty();
        return $this->render('cart/pay.html.twig');
        // pour "payer", il suffit de lancer la méthode empty() qui vide le panier, puis afficher le template de paiement effectué
    }
}
