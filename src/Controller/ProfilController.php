<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
    //  * @Route("/{_locale}/profil", name="profil")
     */
    public function index(ProduitRepository $pRepo): Response
    {
        $user = $this->getUser();
        $produits = $pRepo->getProduitsByUser($user);
        // cette méthode retourne les produits appartenant à l'utilisateur passé en argument

        return $this->render('profil/index.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
    //  * @Route("/{_locale}/profil/show/{id}", name="profil_show")
     */
    public function show(ProduitRepository $pRepo, $id, UserRepository $uRepo)
    {
        $user = $uRepo->find($id);
        if ($user == NULL)
            return $this->render('errors/404.html.twig');
        elseif ($user === $this->getUser())
            return $this->redirectToRoute('profil');
        // si l'id de l'utilisateur n'existe pas, j'envoie une erreur 404 (not found)
        // sinon, si l'id de l'utilisateur est le mien, je suis renvoyé vers ma propre page de profil

        $produits = $pRepo->getProduitsByUser($user);
        // cette méthode retourne les produits appartenant à l'utilisateur passé en argument

        return $this->render('profil/show.html.twig', [
            'produits' => $produits,
            'user' => $user
        ]);
    }

    /**
    //  * @Route("/{_locale}/profil/delete/{id}", name="profil_delete")
     */
    public function delete($id, EntityManagerInterface $mg)
    {
        $user = $this->getUser();
        if ($user == null || $id != $user->getId())
            return $this->render("errors/403.html.twig");
        // si j'essaye de supprimer un utilisateur autre que moi-même, je suis renvoyé vers une erreur 403 (forbidden)

        $ss = new Session();
        $ss->invalidate();
        $mg->remove($user);
        $mg->flush();
        /*
            ces 4 lignes permettent de :
                - déconnecter l'utilisateur :
                    $ss = new Session();
                    $ss->invalidate();

                - puis supprimer l'utilisateur de la BDD :
                    $mg->remove($user);
                    $mg->flush();
        */

        return $this->redirectToRoute('produit');
    }
}
