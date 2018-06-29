<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Entity\Film;
use AppBundle\Form\CategorieType;
use AppBundle\Service\MailSenderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class CategorieController
 * @package AppBundle\Controller
 *
 * @Route("/")
 */
class CategorieController extends Controller
{
    /**
     * @Route("", name="categorie_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * @Route("/create", name="categorie_create")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CategorieType::class, new Categorie());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $categorie = $form->getData();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Ajout avec succès');

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/create.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{categorie}/edit", name="categorie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Categorie $categorie)
    {
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $categorie = $form->getData();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Modification avec succès');

            return $this->redirectToRoute('categorie_edit', array('categorie' => $categorie->getId()));
        }

        return $this->render('categorie/edit.html.twig', array(
            'form' => $form->createView(),
            'categorie' => $categorie
        ));
    }

    /**
     * @Route("/{categorie}/delete", name="categorie_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Categorie $categorie)
    {
        if($categorie->getFilms()->first())
        {
            $this->addFlash('warning', 'Cette catégorie à un ou plusieurs filmes!');
            return $this->redirectToRoute('categorie_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        $this->addFlash('success', 'Suppression avec succès');
        return $this->redirectToRoute('categorie_index');

    }

}
