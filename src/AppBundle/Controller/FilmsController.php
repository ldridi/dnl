<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Entity\Film;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\FilmType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FilmsController
 * @package AppBundle\Controller
 *
 * @Route("/film")
 */

class FilmsController extends Controller
{
    /**
     * @param $categorie
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{categorie}", name="film_index")
     *
     */
    public function indexAction(Categorie $categorie)
    {
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository(Film::class)->findBy(
            ['Categorie' => $categorie]
        );

        return $this->render('film/index.html.twig', array(
            'films' => $films
        ));
    }

    /**
     * @Route("/create/", name="film_create")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(FilmType::class, new Film());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $film = $form->getData();
            $em->persist($film);
            $em->flush();

            $this->addFlash('success', 'Ajout avec succès');

            return $this->redirectToRoute('film_index', [
                'categorie' => $film->getCategorie()->getId()
            ]);
        }

        return $this->render('film/create.html.twig', array(
            'form' => $form->createView()
        ));
    }



    /**
     * @Route("/{film}/edit", name="film_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Film $film)
    {
        $form = $this->createForm(FilmType::class, $film);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $film = $form->getData();
            $em->persist($film);
            $em->flush();

            $this->addFlash('success', 'Modification avec succès');

            return $this->redirectToRoute('film_edit', array('film' => $film->getId()));
        }

        return $this->render('film/edit.html.twig', array(
            'form' => $form->createView(),
            'film' => $film
        ));
    }

    /**
     * @Route("/{film}/delete", name="film_delete")
     * @Method()d({"GET", "DELETE"})
     */
    public function deleteAction(Film $film)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($film);
        $em->flush();

        $this->addFlash('success', 'Suppression avec succès');
        return $this->redirectToRoute('film_index', [
            'categorie' => $film->getCategorie()->getId()
        ]);

    }
}
