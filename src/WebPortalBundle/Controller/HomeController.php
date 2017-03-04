<?php

namespace WebPortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use WebPortalBundle\Entity\Contact;

class HomeController extends Controller {

    public function indexAction() {
        return $this->render('WebPortalBundle:Home:index.html.twig', array('mutant_navbar' => true));
    }

    public function tandcAction() {
        return $this->render('WebPortalBundle:Home:tandc.html.twig', array('mutant_navbar' => false));
    }

    public function contactAction(Request $request) {
        try {
            $contact = new Contact();
            $em = $this->getDoctrine()->getManager();
            $name = $request->get('name');
            $email = $request->get('email');
            $phone = $request->get('phone');
            $message = $request->get('message');
            $target = '';
            $error = true;
            if (empty($name)) {
                $target = 'Name';
            } else if (empty($email)) {
                $target = 'Email';
            } else if (empty($phone)) {
                $target = 'Phone number';
            } else if (empty($message)) {
                $target = 'Message';
            } else {
                $error = false;
            }
            if ($error) {
                return new JsonResponse(array('success' => false, 'msg' => 'check your ' . $target));
            } else {
                $contact->setFullname($name);
                $contact->setEmail($email);
                $contact->setPhone($phone);
                $contact->setMessage($message);
                $em->persist($contact);
                $em->flush();
                $this->sendMailAlert($contact);
                return new JsonResponse(array('success' => true));
            }
        } catch (\Exception $ex) {
            return new JsonResponse(array('success' => false, 'msg' => 'try again later? We have encountered a problem during message registration ' . $ex->getMessage()));
        }
    }

    private function sendMailAlert($contact) {
        $confirmation_message = \Swift_Message::newInstance()
                ->setSubject('Message Received Confirmation')
                ->setFrom(array('no-reply@ngbytes.com' => 'NGBytes Customer Care'))
                ->setTo(array(
                    $contact->getEmail() => $contact->getFullname()
                ))
                ->setBody($this->renderView('WebPortalBundle:Home:confirmation_mail.html.twig', array('contact' => $contact)), 'text/html');
        
        $alert_message = \Swift_Message::newInstance()
                ->setSubject('New Contact Message Test')
                ->setFrom($contact->getEmail())
                ->setTo(array(
                    'noy@ngbytes.com' => 'Luis Enrique',
                    'julio.garcia@ngbytes.com' => 'Julio',
                    'rrubio@ngbytes.com' => 'Rene'
                ))
                ->setBody($this->renderView('WebPortalBundle:Home:contact_mail.html.twig', array('contact' => $contact)), 'text/html');
        $this->get('mailer')->send($alert_message);
        $this->get('mailer')->send($confirmation_message);
    }

}
