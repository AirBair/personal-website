<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppController extends Controller
{
    /**
     * Homepage
     *
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * About section of homepage
     *
     * @param  Request           $request
     * @param  ContentRepository $contentRepository
     *
     * @return Response
     */
    public function about(Request $request, ContentRepository $contentRepository): Response
    {
        $about = $contentRepository->findOneByLabel('about');
        $cv = $contentRepository->findOneByLabel('curriculum_vitae');

        return $this->render('_about.html.twig', array(
            'about' => ($about) ? $about->getContent($request->getLocale()) : null,
            'curriculum_vitae' => ($cv) ? $cv->getContent($request->getLocale()) : null,
        ));
    }

    /**
     * Projects section of homepage
     *
     * @param  ProjectRepository $projectRepository
     *
     * @return Response
     */
    public function projects(ProjectRepository $projectRepository): Response
    {
        return $this->render('_projects.html.twig', array(
            'projects' => $projectRepository->findBy([], ['position' => 'ASC']),
        ));
    }

    /**
     * Change the language of the website
     *
     * @Route("/language/{locale}", name="change_language", requirements={"locale": "fr|en"})
     *
     * @param  Request $request
     * @param  String  $locale
     *
     * @return RedirectResponse
     */
    public function changeLanguageAction(Request $request, String $locale): RedirectResponse
    {
        $request->getSession()->set('_locale', $locale);

        return new RedirectResponse($request->headers->get('referer') ?? $request->getSchemeAndHttpHost());
    }
}