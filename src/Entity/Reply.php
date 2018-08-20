<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReplyRepository")
 */
class Reply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Message", inversedBy="replies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\Column(type="text")
     */
    private $replyBody;

    /**
     * @ORM\Column(type="datetime")
     */
    private $replyDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRead = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSenderCopyDeleted = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReceiverCopyDeleted = false;

    public function getId()
    {
        return $this->id;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function getSenderId()
    {
        return $this->sender->getId();
    }

    public function getSenderName(): ?string
    {
        return $this->sender->getUserInfo()->getCompleteName();
    }

    public function setSender(User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReplyBody(): ?string
    {
        return $this->replyBody;
    }

    public function setReplyBody(string $replyBody): self
    {
        $this->replyBody = $replyBody;

        return $this;
    }

    public function getReplyDate(): ?\DateTimeInterface
    {
        return $this->replyDate;
    }

    public function setReplyDate(\DateTimeInterface $replyDate): self
    {
        $this->replyDate = $replyDate;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getIsSenderCopyDeleted(): ?bool
    {
        return $this->isSenderCopyDeleted;
    }

    public function setIsSenderCopyDeleted(bool $isSenderCopyDeleted): self
    {
        $this->isSenderCopyDeleted = $isSenderCopyDeleted;

        return $this;
    }

    public function getIsReceiverCopyDeleted(): ?bool
    {
        return $this->isReceiverCopyDeleted;
    }

    public function setIsReceiverCopyDeleted(bool $isReceiverCopyDeleted): self
    {
        $this->isReceiverCopyDeleted = $isReceiverCopyDeleted;

        return $this;
    }
}
