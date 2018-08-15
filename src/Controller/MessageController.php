<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Message;
use App\Entity\Patient;
use App\Entity\Reply;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\ReplyType;
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
    private function listMessagesAsArray($messages) : array
    {
        $jsonData = array();

        foreach($messages as $message) {
            $temp = array(
                'id' => $message->getId(),
                'sender' => $message->getSenderName(),
                'recepient' => $message->getRecepientName(),
                'subject' => $message->getSubject(),
                'message' => $message->getMessage(),
                'datesent' => $message->getDateSent()->format('m/d/Y h:i:s A'),
                'isread' => $message->getIsRead(),
            );
            $jsonData[] = $temp;
        }
        return $jsonData;
    }

    /**
     * @Route("/messages", name="messages", methods="GET|POST")
     */
    public function viewMessages(): Response
    {
        return $this->render('message/message.html.twig');
    }

    /**
     * @Route("/messages/inbox", name="inbox", methods="GET|POST")
     */
    public function displayInbox(UserInterface $user, Request $request): Response
    {
        $messages = $this->getDoctrine()
            ->getRepository(Message::class)
            ->findMessagesWithUser($user->getId())
        ;
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $jsonData = $this->listMessagesAsArray($messages);
        return new JsonResponse($jsonData);
    }

    /**
     * @Route("/messages/sent", name="sent", methods="GET|POST")
     */
    public function displaySentItems(UserInterface $user, Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $jsonData = $this->listMessagesAsArray($user->getSentMessages());
        return new JsonResponse($jsonData);
    }

    /**
     * Sends a new message
     *
     * @param UserInterface $user
     * @param Request $request
     * @return Response
     *
     * @Route("/message/compose", name="message_new", methods="GET|POST")
     */
    public function composeMessage(UserInterface $user, Request $request): Response {
        $message = new Message();
        $message->setSender($user);
        $message->setDateSent(new \DateTime('now'));
        $message->setSubject(Lorem::words($nb = 2, $asText = true));
        $message->setMessage(Lorem::paragraph($nbSentences = 3, $variableNbSentences = true));

        $form = $this->createForm(MessageType::class, $message, [
            'userId' => $user->getId(),
            'action' => $this->generateUrl('message_new'),
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return new JsonResponse(array('message' => 'Success!'), 200);
        }

        return $this->render('message/composemessage.html.twig', [
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

        $reply = new Reply();
        $reply->setSender($user);
        $reply->setReplyDate(new \DateTime('now'));
        $reply->setReplyBody(Lorem::paragraph($nbSentences = 3, $variableNbSentences = true));
        $reply->setMessage($messageToReply);

        $form = $this->createForm(ReplyType::class, $reply, [
            'action' => $this->generateUrl('message_reply', [
                    'messageId' => $messageId,
                ]),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reply);
            $entityManager->flush();

            return new JsonResponse(array('message' => 'Success!'), 200);
        }

        return $this->render('message/reply.html.twig', [
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

        if($user->getId() == $message->getRecepient()->getId())
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
    public function showAjax(UserInterface $user, Request $request, $messageId): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $message = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($messageId);

        if($user->getId() == $message->getRecepient()->getId())
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
