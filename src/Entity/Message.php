<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable()
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank()
     * @Assert\Length(min=10, max=25)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(max=500)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="receivedMessages")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $recepient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sentMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRead = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reply", mappedBy="message", orphanRemoval=true)
     */
    private $replies;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchivedBySender = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchivedByRecepient = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSenderCopyDeleted = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRecepientCopyDeleted = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

    // /**
    //  * @ORM\Column(type="datetime")
    //  */
    // private $updatedAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="uploads", fileNameProperty="fileName", size="51200")
     *
     * @var File
     */
    private $fileData;
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    // public function getUpdatedAt(): ?\DateTimeInterface
    // {
    //     return $this->updatedAt;
    // }

    // public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    // {
    //     $this->updatedAt = $updatedAt;

    //     return $this;
    // }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFileData(?File $file = null): void
    {
        $this->fileData = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->dateSent = new \DateTimeInterface('now');
        }
    }

    public function getFileData(): ?File
    {
        return $this->fileData;
    }


    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getLastReply(): ?string
    {
        return (!$this->replies->isEmpty()) ? $this->replies->last()->getReplyBody() : $this->message;
    }

    public function getDateSent(): ?\DateTimeInterface
    {
        return $this->dateSent;
    }

    public function setDateSent(\DateTimeInterface $dateSent): self
    {
        $this->dateSent = $dateSent;

        return $this;
    }

    public function getLastReplyDate(): ?\DateTimeInterface
    {
        return (!$this->replies->isEmpty()) ? $this->replies->last()->getReplyDate() : new \DateTime('now');
    }

    public function getRecepient(): ?User
    {
        return $this->recepient;
    }

    public function setRecepient(?User $recepient): self
    {
        $this->recepient = $recepient;

        return $this;
    }

    public function getRecepientName(): ?string
    {
        return $this->recepient->getUserInfo()->getCompleteName();
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSenderName(): ?string
    {
        return $this->sender->getUserInfo()->getCompleteName();
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

    /**
     * @return Collection|Reply[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReplies(Reply $replies): self
    {
        if (!$this->replies->contains($replies)) {
            $this->replies[] = $replies;
            $replies->setMessage($this);
        }

        return $this;
    }

    public function removeReplies(Reply $replies): self
    {
        if ($this->replies->contains($replies)) {
            $this->replies->removeElement($replies);
            // set the owning side to null (unless already changed)
            if ($replies->getMessage() === $this) {
                $replies->setMessage(null);
            }
        }

        return $this;
    }

    public function getIsArchivedBySender(): ?bool
    {
        return $this->isArchivedBySender;
    }

    public function setIsArchivedBySender(bool $isArchivedBySender): self
    {
        $this->isArchivedBySender = $isArchivedBySender;

        return $this;
    }

    public function getIsArchivedByRecepient(): ?bool
    {
        return $this->isArchivedByRecepient;
    }

    public function setIsArchivedByRecepient(bool $isArchivedByRecepient): self
    {
        $this->isArchivedByRecepient = $isArchivedByRecepient;

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

    public function getIsRecepientCopyDeleted(): ?bool
    {
        return $this->isRecepientCopyDeleted;
    }

    public function setIsRecepientCopyDeleted(bool $isRecepientCopyDeleted): self
    {
        $this->isRecepientCopyDeleted = $isRecepientCopyDeleted;

        return $this;
    }
}
