<?php

namespace App\Service\Cart;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;
    private $pRepo;

    public function __construct(SessionInterface $session, ProduitRepository $pRepo)
    {
        $this->session = $session;
        $this->pRepo = $pRepo;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []);
        // récupère le panier s'il existe, ou un tableau vide par défaut

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        // si l'id du produit existe déjà, j'augmente sa quantité de 1
        // sinon, l'id du produit n'existe pas, je lui attribue une quantité de 1

        $this->session->set('cart', $cart);
        // le panier dans la session 'cart' prend la valeur de notre panier $cart
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        // récupère le panier s'il existe, ou un tableau vide par défaut

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        // si l'id du produit existe dans notre panier, alors je supprime cet id du panier

        $this->session->set('cart', $cart);
        // le panier dans la session 'cart' prend la valeur de notre panier $cart
    }

    public function decrement($id)
    {
        $cart = $this->session->get('cart', []);
        // récupère le panier s'il existe, ou un tableau vide par défaut

        if ($cart[$id] > 1)
            $cart[$id]--;
        else
            unset($cart[$id]);
        /* si l'id du produit existe dans le panier :
            - soit sa quantité est supérieure à 1, alors je décrémente sa quantité de 1
            - soit sa quantité est égale ou inférieure à 1, dans les deux cas je supprime le produit de mon panier
        */

        $this->session->set('cart', $cart);
        // je sauvegarde l'état du panier dans la session
    }

    public function empty()
    {
        $this->session->set('cart', []);
        // pour vider le panier, il suffit de remplacer le panier dans la session par un tableau vide
    }

    public function getFilledCart()
    {
        $cart = $this->session->get('cart', []);
        // récupère le panier s'il existe, ou un tableau vide par défaut

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $this->pRepo->find($id),
                'quantity' => $quantity
            ];
        }
        return $cartWithData;
        // pour chaque id de produit stocké dans $cart, je remplis mon tableau cartWithData[] avec des objets Produit et la quantité de chaque objet
    }

    public function getTotal()
    {
        $total = 0;
        $cartWithData = $this->getFilledCart();

        if ($cartWithData) {
            foreach ($cartWithData as $item) {
                $totalItem = $item['product']->getPrix() * $item['quantity'];
                $total += $totalItem;
            }
        }
        return $total;
        // pour chaque produit stocké dans $cartWithData, je calcule d'abord le total par produit (produit * quantité), puis je l'ajoute au total final

    }
}
