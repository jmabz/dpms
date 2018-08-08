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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/message")
 */
class MessageController extends Controller
{
    /**
     * @Route("/", name="message_index", methods="GET")
     */
    public function displayInbox(UserInterface $user): Response
    {
        return $this->render('message/index.html.twig', ['messages' => $user->getMessages()]);
    }

    /**
     * @Route("/new", name="message_new", methods="GET|POST")
     */
    public function composeMessage(UserInterface $user, Request $request): Response
    {
        $message = new Message();
        $message->setSender($user);
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
     * @Route("/{messageId}/reply", name="message_reply", methods="GET|POST")
     */
    public function replyToMessage(UserInterface $user, Request $request, $messageId): Response
    {
        $messageToReply = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($messageId);

        $message = new Message();
        $message->setSender($user);
        $message->setSubject("RE: " . $messageToReply->getSubject());
        $message->setRecepient($messageToReply->getRecepient());
        // $message->setMessage("\n\n----------------------------" . "\n\n" .
        //     $messageToReply->getSenderName() .
        //     "said:\n\n" . $messageToReply->getMessage());
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
     * @Route("/{messageId}", name="message_show", methods="GET")
     */
    public function show($messageId): Response
    {
        $message = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($messageId);

        return $this->render('message/show.html.twig', ['message' => $message]);
    }

    /**
     * @Route("/{messageId}/edit", name="message_edit", methods="GET|POST")
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
     * @Route("/{messageId}", name="message_delete", methods="DELETE")
     */
    public function delete(MessageRepository $msgRepository, $messageId): Response
    {
        $message =  $msgRepository->find($messageId);

        if (!$message) {
            throw $this->createNotFoundException(
                'No appointment found for ID '. $messageId
            );
        }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();

        return $this->redirectToRoute('message_index');
    }
}
