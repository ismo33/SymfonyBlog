<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;




class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
        
    public function index(ArticleRepository $repo): Response
    {
       
        $articles=$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=>$articles
        ]);
    }
/**
 * @Route("/", name="home")
 */

    public function home(){
        return $this->render('/blog/home.html.twig');
   }

   /**
 * @Route("blog/new" , name="blog_create")
 * @Route("blog/{id}/edit" , name="blog_edit")
 */
public function form( Article $article=null ,Request $Request ,EntityManagerInterface $em){
    if(!$article){
    $article=new article();
    }

   /**  $form=$this->createFormBuilder($article)
     *          ->add('title')
      *         ->add('content')
       *        ->add('image')
        *       ->getform();
*/
    $form=$this->createForm(ArticleType::class, $article);
     $form->handleRequest($Request);
     if($form->isSubmitted() && $form->isValid()){
         if(!$article->getId()){
         $article->setCreatedAt(new \Datetime()); 
         }
         $em->persist($article);
         $em->flush();
         return $this->RedirectToRoute('blog_show',['id'=>$article->getId()]);
     }

             
    return $this->render('blog/create.html.twig',[
        'formArticle'=>$form->createView(),
        'editmode'=>$article->getId()!==null
    ]);
}

   /**
    * @Route("/blog/{id}", name="blog_show")
    */
    public function show($id){
        /**or just pass a param in the function show (article $article)
         * and you don't need no more for lines 40-41
         */
        $repo=$this->getDoctrine()->getRepository(article::class);
        $article=$repo->find($id);
        
        return $this->render('blog/show.html.twig',
    ['article'=>$article]);
    }
}

