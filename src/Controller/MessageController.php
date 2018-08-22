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
use App\Repository\ReplyRepository;
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
     * @Route("/messages", name="messages", methods="GET|POST")
     */
    public function viewMessages(): Response
    {
        return $this->render('message/message.html.twig');
    }

    /**
     * @Route("/messages/inbox", name="inbox", methods="GET")
     */
    public function displayInbox(UserInterface $user, Request $request): Response
    {
        $messages = $this->getDoctrine()
            ->getRepository(Message::class)
            ->findMessagesWithUser($user->getId(), false)
        ;
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        //$jsonData = $this->listMessagesAsArray($messages);
        return $this->render('message/messagelist.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/messages/displayarchive", name="displayarchive", methods="GET")
     */
    public function displayArchive(UserInterface $user, Request $request): Response
    {
        $messages = $this->getDoctrine()
            ->getRepository(Message::class)
            ->findMessagesWithUser($user->getId(), true)
        ;
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        //$jsonData = $this->listMessagesAsArray($messages);
        return $this->render('message/messagelist.html.twig', [
            'messages' => $messages,
        ]);
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

        $reply = new Reply();
        $reply->setSender($user);
        $reply->setReplyDate($message->getDateSent());
        $reply->setReplyBody($message->getMessage());
        $reply->setMessage($message);

        $form = $this->createForm(MessageType::class, $message, [
            'action' => $this->generateUrl('message_new'),
            'userId' => $user->getId(),
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reply);
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
            if($request->isXmlHttpRequest()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($reply);
                $entityManager->flush();

                $template = $this->get('twig')->loadTemplate('message/show.html.twig');

                $newMsg = $template->renderBlock('replytext', [
                    'reply' => $reply,
                    'userId' => $user->getId(),
                ]);

                return new JsonResponse(array('message' => 'Success!', 'newMsg' => $newMsg), 200);
            }
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

        return $this->render('message/show.html.twig', [
            'message' => $message,
            'userId' => $user->getId(),]);
    }

    /**
     * @Route("/message/delete/{messageId}", name="message_delete", methods="GET|DELETE")
     */
    public function delete(UserInterface $user, MessageRepository $msgRepository, Request $request, $messageId): Response
    {
        $message =  $msgRepository->find($messageId);
        if($request->isXmlHttpRequest()) {
            if (!$message) {
                return new JsonResponse(['failure' => 'No message found for ID ' . $messageId]);
            }

                if($message->getSender()->getId() == $user->getId()) {
                    $message->setIsSenderCopyDeleted(true);
                } else {
                    $message->setIsRecepientCopyDeleted(true);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($message);
                $entityManager->flush();

                if($message->getIsSenderCopyDeleted() && $message->getIsRecepientCopyDeleted()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($message);
                    $entityManager->flush();
                }

                return new JsonResponse(['success' => 'Deleted message ' . $messageId]);
        }
        return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
    }

    /**
     * @Route("/message/deletereply/{replyId}", name="reply_delete", methods="POST")
     */
    public function deleteReply(UserInterface $user, ReplyRepository $replyRepository, Request $request, $replyId): Response
    {
        $reply = $replyRepository->find($replyId);
        if($request->isXmlHttpRequest()) {
            if (!$reply) {
                return new JsonResponse(['failure' => 'No reply found for ID ' . $replyId]);
            }

                if($reply->getSenderId() == $user->getId()) {
                    $reply->setIsSenderCopyDeleted(true);
                } else {
                    $reply->setIsReceiverCopyDeleted(true);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($reply);
                $entityManager->flush();

                if($reply->getIsSenderCopyDeleted() && $reply->getIsReceiverCopyDeleted()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($reply);
                    $entityManager->flush();
                }

                return new JsonResponse(['success' => 'Deleted reply ' . $replyId]);
        }
        return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
    }

    /**
     * @Route("/message/archive/{messageId}", name="message_archive", methods="POST")
     */
    public function archive(UserInterface $user, MessageRepository $msgRepository, Request $request, $messageId): Response
    {
        $message =  $msgRepository->find($messageId);
        if($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            if (!$message) {
                return new JsonResponse(['failure' => 'No message found for ID ' . $messageId]);
            }

            if($message->getSender()->getId() == $user->getId()) {
                $message->setIsArchivedBySender($message->getIsArchivedBySender() ? false : true);
            } else {
                $message->setIsArchivedByRecepient($message->getIsArchivedByRecepient() ? false : true);
            }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($message);
                $entityManager->flush();

                return new JsonResponse(['success' => 'Archived message ' . $messageId]);
        }
        return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
    }
}
