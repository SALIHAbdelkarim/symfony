<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Service\MathService;
use Symfony\Component\HttpFoundation\Response;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AdminBundle:Post')->findBy([],['datepublication'=>'DESC'],3,0);
        return $this->render('default/index.html.twig',['posts' => $posts]);
    }
    
     /**
     * @Route("/blog", name="blog_index")
     */
    public function blogAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AdminBundle:Post')->findAll();
        return $this->render('default/blog.html.twig',['posts' => $posts]);
    }
     /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AdminBundle:Post')->find($id);
        return $this->render('default/show.html.twig',['post' => $post]);  
    }

       /**
     * @Route("/contact", name="contact_form")
     *@Method({"GET", "POST"})
     */
    public function contactAction(Request $req,\Swift_Mailer $mailer)
    {
        $form = $this->createFormBuilder()
                    ->add('from')
                    ->add('subject')
                    ->add('body',TextareaType::class)
                    ->add('send',SubmitType::class)
                    ->getForm();
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {
            //traitement d'envoi un mail
            $data = $form->getData();
            $message = (new \Swift_Message($data['subject']))
                        ->setFrom($data['from'])
                        ->setTo('salih.abdelkarim1@gmail.com')
                        ->setBody($data['body'],'text/plain');
            $mailer->send($message);
        }
        return $this->render('default/contact.html.twig',['form' => $form->createView()]);
    }
    /**
     * @Route("/operation", name="operation")
     *
     */
    public function operationAction(MathService $math)
    {
        return new Response($math->addition(1,30));
    }
}
