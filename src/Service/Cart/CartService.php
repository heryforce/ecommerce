<?php

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $rs;

    public function __construct(RequestStack $rs)
    {
        $this->rs = $rs;
    }

    public function add($id)
    {
        $cart = $this->rs->getSession()->get('cart', []);
        // récupère le panier s'il existe, ou un tableau vide par défaut

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        // si l'id du produit existe déjà, j'augmente sa quantité de 1
        // sinon, l'id du produit n'existe pas, je lui attribue une quantité de 1

        $this->rs->getSession()->set('cart', $cart);
        // le panier dans la session 'cart' prend la valeur de notre panier $cart
    }

    public function delete($id)
    {
        $cart = $this->rs->getSession()->get('cart', []);
        // récupère le panier s'il existe, ou un tableau vide par défaut

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        // si l'id du produit existe dans notre panier, alors je supprime cet id du panier

        $this->rs->getSession()->set('cart', $cart);
        // le panier dans la session 'cart' prend la valeur de notre panier $cart
    }

    public function decrement($id)
    {
        $cart = $this->rs->getSession()->get('cart', []);
        // récupère le panier s'il existe, ou un tableau vide par défaut

        if ($cart[$id] > 1)
            $cart[$id]--;
        else
            unset($cart[$id]);
        /* si l'id du produit existe dans le panier :
            - soit sa quantité est supérieure à 1, alors je décrémente sa quantité de 1
            - soit sa quantité est égale ou inférieure à 1, dans les deux cas je supprime le produit de mon panier
        */

        $this->rs->getSession()->set('cart', $cart);
        // je sauvegarde l'état du panier dans la session
    }

    public function empty()
    {
        $this->rs->getSession()->set('cart', []);
        // pour vider le panier, il suffit de remplacer le panier dans la session par un tableau vide
    }

    public function getCartWithData($stripeSK)
    {
        \Stripe\Stripe::setApiKey($stripeSK);
        $stripe = new \Stripe\StripeClient($stripeSK);

        $cart = $this->rs->getSession()->get('cart', []);

        $cartWithData = [];

        foreach ($cart as $price_id => $quantity) {
            $cartWithData[] = [
                // 'product' => $this->repo->find($id),
                'product' => $stripe->products->retrieve($stripe->prices->retrieve($price_id)->product),
                'price' => $stripe->prices->retrieve($price_id)->unit_amount / 100,
                'id' => $price_id,
                'quantity' => $quantity
            ];
        }
        // pour chaque id contenu dans mon tableau $cart
        // je rajoute une case à mon tableau multidimensionnel $cartWithData
        // chaque case de ce tableau est elle-même un tableau contenant deux cases : une case product qui contient le produit lié à l'id et une case quantity qui contient sa quantité

        return $cartWithData;
    }

    public function getTotal($stripeSK)
    {
        $total = 0;
        $cartWithData = $this->getCartWithData($stripeSK);

        \Stripe\Stripe::setApiKey($stripeSK);
        // $stripe = new \Stripe\StripeClient($stripeSK);

        foreach ($cartWithData as $item) {
            $totalItem = $item['price'] * $item['quantity'];
            $total += $totalItem;
            // équivaut à écrire $total = $total + $totalItem;

            // à chaque tour de boucle, j'ajoute au total du panier les totaux par produit
        }
        return $total;
    }
}
