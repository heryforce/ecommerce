<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Form\CommentaireType;
use App\Form\ProduitType;
use App\Form\SearchType;
use App\Repository\CommentaireRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Egulias\EmailValidator\Parser\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProduitController extends AbstractController
{
    /**
     * @Route("/locale", name="set_locale")
     */
    public function set_locale()
    {
        return $this->redirectToRoute("produit");
    }

    /**
     * @Route("/{_locale}/produit", name="produit", requirements={"_locale": "en|fr"})
     */
    public function index(ProduitRepository $repo, Request $rq): Response
    {
        $form = $this->createForm(SearchType::class, null);
        // mon formulaire de recherche de produit par nom

        $form->handleRequest($rq);
        if ($form->isSubmitted() && $form->isValid())
            $produits = $repo->getProduitsByName($form->get('recherche')->getData());
        else
            $produits = $repo->findAllOrderAsc();
        // si on a lancé une recherche de produit, je récupère les produits recherchés via la méthode que j'ai créee dans le repository
        // sinon, je récupère tous les produits en BDD via une autre méthode que j'ai créee dans le repository qui trie les produits par ordre alphabétique

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'sForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{_locale}/produit/add", name="produit_add")
     * @Route("/{_locale}/produit/edit/{id}", name="produit_edit")
     */
    public function form(Produit $produit = null, EntityManagerInterface $manager, Request $rq)
    {
        $user = $this->getUser();
        if ($produit && $produit->getAuteur() != $user)
            return $this->render('errors/403.html.twig');

        // si un user essaie de modifier un produit qui ne lui appartient pas, il est redirigé vers une erreur 403 (forbidden)

        if ($rq->attributes->get('_route') === 'produit_edit' && !$produit)
            return $this->redirectToRoute('produit_add');

        // si un user essaye de modifier un produit qui n'existe pas, il est redirigé vers la page d'ajout de produit

        if (!$produit)
            $produit = new Produit;
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($rq);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$produit->getId()) {
                $produit->setAuteur($this->getUser());
                /*
                équivalent à :
                    $user = $this->getUser();
                    $produit->setAuteur($user);
                */
                // si le produit n'a pas d'id, ça veut dire que je suis en création, donc l'auteur du produit est l'utilisateur actuellement connecté
            }
            $manager->persist($produit);
            $manager->flush();
            return $this->redirectToRoute('produit_show', [
                'id' => $produit->getId()
            ]);
        }
        return $this->render('produit/form.html.twig', [
            'pForm' => $form->createView(),
            'editMode' => $produit->getId() != NULL,
            'produit' => $produit
        ]);
    }

    /**
     * @Route("/{_locale}/produit/show/{id}", name="produit_show")
     */
    public function show(EntityManagerInterface $manager, CommentaireRepository $cRepo, Request $rq, $id, ProduitRepository $pRepo, TranslatorInterface $t)
    {
        $produit = $pRepo->find($id);
        if (!$produit)
            return $this->render('errors/404.html.twig');
        // si un visiteur essaie de visualiser un produit qui n'existe pas, il est redirigé vers une erreur 404 (not found)

        $com = new Commentaire;
        $form = $this->createForm(CommentaireType::class, $com);

        $coms = $cRepo->getComsByProduit($produit);
        // cette méthode récupère les commentaires liés au produit

        $form->handleRequest($rq);
        if ($form->isSubmitted() && $form->isValid()) {
            $com->setAuteur($this->getUser())
                ->setCreatedAt(new \DateTime())
                ->setProduit($produit);
            // si le commentaire est envoyé, son auteur est bien l'utilisateur actuellement connecté

            $manager->persist($com);
            $manager->flush();

            $text = $t->trans('Votre commentaire a bien été posté !');

            $this->addFlash('success', $text);
            return $this->redirectToRoute('produit_show', [
                'id' => $produit->getId()
            ]);
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'cForm' => $form->createView(),
            'coms' => $coms
        ]);
    }
}
