<?php

namespace Portal\PortalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Portal\PortalBundle\Entity\Announce;
use Portal\PortalBundle\Form\AnnounceType;
use Portal\PortalBundle\Form\AnnounceFilterType;

/**
 * Announce controller.
 *
 */
class AnnounceController extends Controller
{
    /**
     * Lists all Announce entities.
     *
     */
    public function indexAction()
    {
        list($filterForm, $queryBuilder) = $this->filter();
        //->where('userId', $this->getUser()->getId())

        //echo($this->getUser()->getId());

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

        return $this->render('PortalBundle:Announce:index.html.twig', array(
            'entities' => $entities,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'userId' => $this->getUser()->getId(),
        ));
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new AnnounceFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PortalBundle:Announce')->createQueryBuilder('e');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('AnnounceControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->bind($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('AnnounceControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('AnnounceControllerFilter')) {
                $filterData = $session->get('AnnounceControllerFilter');
                $filterForm = $this->createForm(new AnnounceFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder)
    {
        // Paginator

        /*$em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('PortalBundle:Announce');

        $query = $repository->createQueryBuilder('u')
            ->where('u.userId = :id')
            ->setParameter('id', $this->getUser()->getId())
            ->getQuery();

        $adapter = new DoctrineORMAdapter( $query );*/


        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('announce', array('page' => $page));
        };

        // Paginator - view
        $translator = $this->get('translator');
        $view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => $translator->trans('views.index.pagprev', array(), 'JordiLlonchCrudGeneratorBundle'),
            'next_message' => $translator->trans('views.index.pagnext', array(), 'JordiLlonchCrudGeneratorBundle'),
        ));

        return array($entities, $pagerHtml);
    }

    /**
     * Creates a new Announce entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Announce();
        $form = $this->createForm(new AnnounceType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUserId($this->getUser());
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('announce_show', array('id' => $entity->getId())));
        }

        return $this->render('PortalBundle:Announce:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Announce entity.
     *
     */
    public function newAction()
    {
        $entity = new Announce();
        $form   = $this->createForm(new AnnounceType(), $entity);

        return $this->render('PortalBundle:Announce:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Announce entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PortalBundle:Announce')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Announce entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PortalBundle:Announce:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'userId' => $this->getUser()->getId(),

            ));
    }

    /**
     * Displays a form to edit an existing Announce entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PortalBundle:Announce')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Announce entity.');
        }

        $editForm = $this->createForm(new AnnounceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PortalBundle:Announce:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'userId' => $this->getUser()->getId()
        ));
    }

    /**
     * Edits an existing Announce entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PortalBundle:Announce')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Announce entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AnnounceType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');

            return $this->redirect($this->generateUrl('announce_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        return $this->render('PortalBundle:Announce:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Announce entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PortalBundle:Announce')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Announce entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('announce'));
    }

    /**
     * Creates a form to delete a Announce entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
