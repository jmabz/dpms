<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Message;
use App\Entity\Patient;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

use Faker\Provider\Lorem;

class MessageController extends Controller
{
    /**
     * @Route("/messages", name="message_index", methods="GET|POST")
     */
    public function displayInbox(UserInterface $user, Request $request): Response
    {
        $messages = $user->getMessages();

        if($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = array();

            foreach($messages as $message) {
                $temp = [
                    'id' => $message->getId(),
                    'sender' => $message->getSenderName(),
                    'subject' => $message->getSubject(),
                    'message' => $message->getMessage(),
                    'datesent' => $message->getDateSent()->format('m/d/Y h:i:s A'),
                    'isread' => $message->getIsRead(),
                ];
                $jsonData[] = $temp;
            }
            return new JsonResponse($jsonData);

        }
        return $this->render('message/index.html.twig', ['messages' => $user->getMessages()]);
    }

    /**
     * @Route("/messages/sent", name="sent", methods="GET")
     */
    public function displaySentItems(UserInterface $user, MessageRepository $msgRepository): Response
    {
        $messages = $msgRepository->findBy(['sender' => $user->getId()]);
        return $this->render('message/index.html.twig', ['messages' => $messages]);
    }

    /**
     * @Route("/message/compose", name="message_new", methods="GET|POST")
     */
    public function composeMessage(UserInterface $user, Request $request): Response
    {
        $message = new Message();
        $message->setSender($user);
        $message->setDateSent(new \DateTime('now'));
        $message->setSubject(Lorem::words($nb = 2, $asText = true));
        $message->setMessage(Lorem::paragraph($nbSentences = 3, $variableNbSentences = true));
        $form = $this->createForm(MessageType::class, $message, [
            'userId' => $user->getId()
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/composemessage.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/reply/{messageId}", name="message_reply", methods="GET|POST")
     */
    public function replyToMessage(UserInterface $user, Request $request, $messageId): Response
    {
        $messageToReply = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($messageId);

        $message = new Message();
        $message->setSender($user);
        $message->setSubject($messageToReply->getSubject());
        $message->setRecepient($messageToReply->getRecepient());
        $message->setDateSent(new \DateTime('now'));
        $form = $this->createForm(MessageType::class, $message, [
            'userId' => $user->getId()
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/composemessage.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/show/{messageId}", name="message_show", methods="GET")
     */
    public function show($messageId): Response
    {
        $message = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($messageId);

        $message->setIsRead(true);

        $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($message);
                    $entityManager->flush();

        return $this->render('message/show.html.twig', ['message' => $message]);
    }

    /**
     * @Route("/message/edit/{messageId}", name="message_edit", methods="GET|POST")
     */
    public function edit(Request $request, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_edit', ['messageId' => $message->getId()]);
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/delete/{messageId}", name="message_delete", methods="GET|DELETE")
     */
    public function delete(MessageRepository $msgRepository, Request $request, $messageId): Response
    {
        $message =  $msgRepository->find($messageId);
        if($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            if (!$message) {
                return new JsonResponse(['failure' => 'No message found for ID ' . $messageId]);
            }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($message);
                $entityManager->flush();

                return new JsonResponse(['success' => 'Deleted message ' . $messageId]);
        }
        //return $this->redirectToRoute('message_index');
    }
}
