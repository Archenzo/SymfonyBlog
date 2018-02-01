<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Entity\Comments;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class BlogController extends Controller
{
    /**
     * @Route("/blog", name="homepage")
     */
    public function listAction()
    {

        $articles = $this->getDoctrine()->getRepository('AppBundle:Blog')->findAll();
        return $this->render('blog/index.html.twig', array(

            'articles' => $articles
        ));


    }


    /**
     * @Route("/blog/create", name="create_post")
     */
    public function createAction(Request $request)
    {

        $blog = new blog;
        $form = $this->createFormBuilder($blog)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('post', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('submit', SubmitType::class, array('label' => 'Post Article', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top:15px ')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #  die('Submitted');

            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $post = $form['post']->getData();
            $now = new\DateTime('now');

            $blog->setName($name);
            $blog->setDescription($description);
            $blog->setCreatedDate($now);
            $blog->setPost($post);

            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            $this->addFlash('notice', 'Article Added');

            return $this->redirectToRoute('homepage');
        }
        return $this->render('blog/create.html.twig', array(
            'form' => $form->createView()
        ));


    }

    /**
     * @Route("/blog/edit/{id}", name="edit_post")
     */
    public function editAction($id, Request $request)
    {

        $blog= $this->getDoctrine()->getRepository('AppBundle:Blog')->find($id);
         $now = new\DateTime('now');

        $blog->setName($blog->getName());
        $blog->setDescription($blog->getDescription());
        $blog->setCreatedDate($blog->getCreatedDate());
        $blog->setPost($blog->getPost());

        $form = $this->createFormBuilder($blog)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('post', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('submit', SubmitType::class, array('label' => 'Update Article', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top:15px ')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #  die('Submitted');

            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $post = $form['post']->getData();
            $now = new\DateTime('now');
            $em = $this->getDoctrine()->getManager();

            $blog->setName($name);
            $blog->setDescription($description);
            $blog->setCreatedDate($now);
            $blog->setPost($post);

            $em = $this->getDoctrine()->getManager();

            $em->flush();

            $this->addFlash('notice', 'Article Edited');

            return $this->redirectToRoute('admin_page');
        }

        return $this->render('blog/edit.html.twig', array(

            'blog' => $blog,
            'form' => $form->createView()
        ));


    }


    /**
     * @Route("/blog/details/{id}", name="post_details")
     */
    public function detailsAction($id,Request $request)
    {
        //view article
        $article = $this->getDoctrine()->getRepository('AppBundle:Blog')->find($id);

        //comments code
        $comment = new comments;

        $form = $this->createFormBuilder($comment)
            ->add('readername', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('readercomment', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('submit', SubmitType::class, array('label' => 'Comment', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top:15px ')))
            ->getForm();

        $form->handleRequest($request);
       // $articleid =$this->getDoctrine()->getRepository('AppBundle:Blog')->find($id)->__toString();

        $articleid =$article->getId();
        if ($form->isSubmitted() && $form->isValid()) {


            $readername = $form['readername']->getData();
            $readercomment = $form['readercomment']->getData();


            $comment->setReadername($readername);
            $comment->setReadercomment($readercomment);
            $comment->setArticleid($articleid);


            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('notice', 'Comment Added');

           // return $this->redirectToRoute('post_details');
            return $this->redirectToRoute($this->generateUrl('post_details',array('id'=>$id)));
        }

        $comments = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['articleid' => $id]);


        return $this->render('blog/details.html.twig', array(

            'article' => $article,
            'form' => $form->createView(),
            'comments'=>$comments
        ));


    }


    /**
     * @Route("/blog/delete/{id}", name="post_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('AppBundle:Blog')->find($id);

        $em->remove($blog);
        $em->flush();

        $this->addFlash('notice', 'Article Removed');
        return $this->redirectToRoute('admin_page');


    }

    //Admin

    /**
     * @Route("/blog/admin", name="admin_page")
     */
    public function adminAction(Request $request)
    {

        $articles = $this->getDoctrine()->getRepository('AppBundle:Blog')->findAll();
        return $this->render('blog/admin.html.twig', array(

            'articles' => $articles
        ));

    }

}