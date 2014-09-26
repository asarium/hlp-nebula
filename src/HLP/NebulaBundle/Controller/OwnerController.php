<?php

/*
* Copyright 2014 HLP-Nebula authors, see NOTICE file
4
*
* Licensed under the EUPL, Version 1.1 or – as soon they
will be approved by the European Commission - subsequent
versions of the EUPL (the "Licence");
* You may not use this work except in compliance with the
Licence.
* You may obtain a copy of the Licence at:
*
*
http://ec.europa.eu/idabc/eupl
5
*
* Unless required by applicable law or agreed to in
writing, software distributed under the Licence is
distributed on an "AS IS" basis,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
express or implied.
* See the Licence for the specific language governing
permissions and limitations under the Licence.
*/ 

namespace HLP\NebulaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use HLP\NebulaBundle\Entity\OwnerInterface;
use HLP\NebulaBundle\Entity\User;
use HLP\NebulaBundle\Entity\FSMod;
use HLP\NebulaBundle\Form\FSModType;

class OwnerController extends Controller
{
  public function indexAction($owner)
  {
    return $this->redirect($this->generateUrl('hlp_nebula_owner_mods', array('owner' => $owner)), 301);
  }
  
  public function modsAction(OwnerInterface $owner)
  {
    $modsList = $owner->getMods();
    
    return $this->render('HLPNebulaBundle:AdvancedUI:modder_mods.html.twig', array('modder' => $owner, 'modsList' => $modsList));
  }
  
  public function profileAction(OwnerInterface $owner)
  {
    return $this->render('HLPNebulaBundle:AdvancedUI:modder_profile.html.twig', array('modder' => $owner));
  }
  
  public function activityAction(OwnerInterface $owner)
  {
    return $this->render('HLPNebulaBundle:AdvancedUI:modder_activity.html.twig', array('modder' => $owner));
  }
  
  public function newModAction(Request $request, OwnerInterface $owner)
  {
    if (false === $this->get('security.context')->isGranted('add', $owner)) {
        throw new AccessDeniedException('Unauthorised access!');
    }
    
    $mod = new FSMod;
    $mod->setFirstRelease(new \Datetime());
    $mod->setOwner($owner);
    $form = $this->createForm(new FSModType(), $mod);
    
    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($mod);
      $em->flush();
      
      $request->getSession()->getFlashBag()->add('success', "New mod <strong>".$mod->getTitle()."</strong> successfully created.");

      return $this->redirect($this->generateUrl('hlp_nebula_owner', array('owner' => $owner)));
    }
    
    return $this->render('HLPNebulaBundle:AdvancedUI:new_mod.html.twig', array(
      'owner' => $owner,
      'form' => $form->createView()
    ));
  }
}
