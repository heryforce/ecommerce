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
    public function index(): Response
    {
        $user = $this->getUser();

        // AJOUTER L'HISTORIQUE DE COMMANDE DE L'UTILISATEUR

        return $this->render('profil/index.html.twig', [
        ]);
    }

    /**
    //  * @Route("/{_locale}/profil/delete/{id}", name="profil_delete")
     */
    public function delete($id, EntityManagerInterface $mg)
    {
        $user = $this->getUser();
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
