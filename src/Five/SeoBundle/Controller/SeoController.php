<?php

namespace Five\SeoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Five\SeoBundle\Entity\Seo;
use Five\SeoBundle\Form\SeoType;

/**
 * Seo controller.
 *
 */
class SeoController extends Controller
{
    public function renderEditFormAction( $route, $_locale, $url )
    {
//        var_dump( $route, $_locale, $url );
        $request = array(
            'route' => $route,
            '_locale' => $_locale,
            'url' => $url
        );

        //return new Response( $route . '  ' . $_locale . '  ' . $url );
        $entity   = $this->getOrCreateSeoEntity( $request );
        $editForm = $this->createEditForm($entity);

        return $this->render('FiveSeoBundle:Seo:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView()
        ));

    }

    public function getOrCreateSeoEntity( $request = null )
    {
        $em = $this->getDoctrine()->getManager();

        $FiveSeo  = $em->getRepository( 'FiveSeoBundle:Seo' )->findOneBy( array(
            'locale'  => $request[ '_locale' ],
            'route'   => $request[ 'route' ] ,
            'url'     => $request[ 'url' ] ,
            'enabled' => true
        ) );

        if( !$FiveSeo )
        {
            $Seo = new Seo();
            $FiveSeoDefault  = $em->getRepository( 'FiveSeoBundle:Seo' )->findOneBy( array(
                'locale'  => $request[ '_locale' ],
                'route'   => $request[ 'route' ] ,
                'enabled' => true
            ) );

           $Seo
                ->setRoute( $request[ 'route' ] )
                ->setTitle( ' '  )
                ->setUrl( $request[ 'url' ]  )
                ->setLocale( $request[ '_locale' ] )
                ->setMetasName( array(
                    array( 'name' => 'description', 'content' => '' ),
                    array( 'name' => 'keywords', 'content' => '' )
                ) )
                ->setEnabled( true )                
            ;

            if( $FiveSeoDefault )
            {
                $Seo
                    ->setMetasName( $FiveSeoDefault->getMetasName() )
                    ->setTitle( $FiveSeoDefault->getTitle() )
                ;
            }

            $em->persist( $Seo );
            $em->flush( $Seo );
        }
        else
        {
            $Seo = $FiveSeo;
        }    

        return $Seo;
    }

    /**
     * Lists all Seo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FiveSeoBundle:Seo')->findAll();

        return $this->render('FiveSeoBundle:Seo:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Seo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Seo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('seo_show', array('id' => $entity->getId())));
        }

        return $this->render('FiveSeoBundle:Seo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Seo entity.
     *
     * @param Seo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Seo $entity)
    {
        $form = $this->createForm(new SeoType(), $entity, array(
            'action' => $this->generateUrl('seo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Seo entity.
     *
     */
    public function newAction()
    {
        $entity = new Seo();
        $form   = $this->createCreateForm($entity);

        return $this->render('FiveSeoBundle:Seo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Seo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FiveSeoBundle:Seo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FiveSeoBundle:Seo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Seo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FiveSeoBundle:Seo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FiveSeoBundle:Seo:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Seo entity.
    *
    * @param Seo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm( Seo $entity)
    {
        $form = $this->createForm(new SeoType(), $entity, array(
            'action' => $this->generateUrl( 'seo_update', array('id' => $entity->getId() )),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array( 'class' => 'btn btn-info', 'type' => 'button' )));
        $form->add('close', 'button', array('label' => 'Close', 'attr' => array( 'class' => 'btn btn-default', 'type' => 'button', 'data-dismiss' => 'modal' )));

        return $form;
    }
    /**
     * Edits an existing Seo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FiveSeoBundle:Seo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seo entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        
        if( in_array( $this->getRequest()->getMethod(), array( 'PUT', 'POST' ) ) )
        {
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();

                return new JsonResponse( array(
                    'status' => 'success',
                    'errors' => array()
                ) );
            }
            else
            {
                return new JsonResponse( array(
                    'status' => 'error',
                    'errors' => array()
                ) );                
            }
        }
        else
        {
            return $this->redirect($this->generateUrl('seo_edit', array('id' => $id)));
        }
    }
    /**
     * Deletes a Seo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FiveSeoBundle:Seo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Seo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('seo'));
    }

    /**
     * Creates a form to delete a Seo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('seo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
