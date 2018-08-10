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
    public function displayMessages(UserInterface $user, Request $request): Response
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
     * @Route("/messages/inbox", name="inbox", methods="GET|POST")
     */
    public function displayInbox(UserInterface $user, Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
            $messages = $user->getMessages();
            $jsonData = array();

            foreach($messages as $message) {
                $temp = [
                    'id' => $message->getId(),
                    'sender' => $message->getSenderName(),
                    'recepient' => $message->getRecepientName(),
                    'subject' => $message->getSubject(),
                    'message' => $message->getMessage(),
                    'datesent' => $message->getDateSent()->format('m/d/Y h:i:s A'),
                    'isread' => $message->getIsRead(),
                ];
                $jsonData[] = $temp;
            }
            return new JsonResponse($jsonData);
    }

    /**
     * @Route("/message/compose", name="send_message", methods="GET|POST")
     */
    public function composeMessage(UserInterface $user, MessageRepository $msgRepository, Request $request): Response
    {
        $messages = $user->getMessages();
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

            return $this->redirectToRoute('send_message');
        }

        $sent = $msgRepository->findBy(['sender' => $user->getId()]);

        return $this->render('message/message.html.twig', [
            'messages' => $messages,
            'message' => $message,
            'form' => $form->createView(),
            'sent' => $sent,
        ]);
    }

    /**
     * @Route("/messages/sent", name="sent", methods="GET|POST")
     */
    public function displaySentItems(UserInterface $user, Request $request, MessageRepository $msgRepository): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $messages = $msgRepository->findBy(['sender' => $user->getId()]);

        $jsonData = array();

        foreach($messages as $message) {
            $temp = [
                'id' => $message->getId(),
                'sender' => $message->getSenderName(),
                'recepient' => $message->getRecepientName(),
                'subject' => $message->getSubject(),
                'message' => $message->getMessage(),
                'datesent' => $message->getDateSent()->format('m/d/Y h:i:s A'),
                'isread' => $message->getIsRead(),
            ];
            $jsonData[] = $temp;
        }
        return new JsonResponse($jsonData);
    }

    // /**
    //  * @Route("/message/compose", name="message_new", methods="GET|POST")
    //  */
    // public function composeMessage(UserInterface $user, Request $request): Response
    // {
    //     $message = new Message();
    //     $message->setSender($user);
    //     $message->setDateSent(new \DateTime('now'));
    //     $form = $this->createForm(MessageType::class, $message, [
    //         'userId' => $user->getId()
    //         ]);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($message);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('message_index');
    //     }

    //     return $this->render('message/message.html.twig', [
    //         'message' => $message,
    //         'form' => $form->createView(),
    //     ]);
    // }

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

            return $this->redirectToRoute('send_message');
        }

        return $this->render('message/composemessage.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/show/{messageId}", name="message_show", methods="GET|POST")
     */
    public function show(UserInterface $user, $messageId): Response
    {
        $message = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($messageId);

        //if($user->getId() == $message->receiver->getId())
            $message->setIsRead(true);

        $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($message);
                    $entityManager->flush();

        return $this->render('message/show.html.twig', ['message' => $message]);
    }

    /**
     * Returns a message by ID as a JsonResponse
     *
     * @param Request $request
     * @param [type] $messageId
     * @return JsonResponse
     *
     * @Route("/message/show/ajax/{messageId}", name="message_show_ajax", methods="POST")
     */
    public function showAjax(Request $request, $messageId): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $message = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($messageId);

        $message->setIsRead(true);

        $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($message);
                    $entityManager->flush();

        $jsonData = array();

        $temp = array(
            'id' => $message->getId(),
            'sender' => $message->getSenderName(),
            'recepient' => $message->getRecepientName(),
            'subject' => $message->getSubject(),
            'message' => $message->getMessage(),
            'datesent' => $message->getDateSent()->format('m/d/Y h:i:s A'),
            'isread' => $message->getIsRead(),
            )
        ;
        $jsonData = $temp;

        return new JsonResponse($jsonData);
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
