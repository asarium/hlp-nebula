<?php

/*
* Copyright 2014 HLP-Nebula authors, see NOTICE file

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

class WorkshopController extends Controller
{
  public function homeAction()
  {
    return $this->render('HLPNebulaBundle:AdvancedUI:homepage.html.twig');
  }
  
  public function startAction()
  {
    return $this->render('HLPNebulaBundle:AdvancedUI:get_started.html.twig');
  }
  
  public function metasAction()
  {
    $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('HLPNebulaBundle:Meta')
    ;
    
    $modsList = $repository->findAll();
    
    return $this->render('HLPNebulaBundle:AdvancedUI:all_mods.html.twig', array(
      'modsList'  => $modsList
    ));
  }
  
  public function usersAction()
  {
    $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('HLPNebulaBundle:User')
    ;
    
    $moddersList = $repository->findAll();
    
    return $this->render('HLPNebulaBundle:AdvancedUI:all_modders.html.twig', array(
      'moddersList'  => $moddersList
    ));
  }
  
  public function activityAction()
  {
    return $this->render('HLPNebulaBundle:AdvancedUI:all_activity.html.twig');
  }
  
}
